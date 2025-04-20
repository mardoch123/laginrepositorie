import { initializeApp } from 'firebase/app';
import { getFirestore } from 'firebase/firestore';
import { getAuth } from 'firebase/auth';
import { getStorage } from 'firebase/storage';
import { getMessaging, getToken, isSupported } from 'firebase/messaging';

// Configuration Firebase
export const firebaseConfig = {
  apiKey: "AIzaSyDe3mBx2I983oIaTjSV83moKLrqYR-zMuA",
  authDomain: "elevage-projet.firebaseapp.com",
  projectId: "elevage-projet",
  storageBucket: "elevage-projet.firebasestorage.app",
  messagingSenderId: "1061048497751",
  appId: "1:1061048497751:web:5d7118fd7d9c834aa8d512"
};

// Initialiser Firebase
const app = initializeApp(firebaseConfig);

// Initialiser les services
export const db = getFirestore(app);
export const auth = getAuth(app);
export const storage = getStorage(app);

// Clé VAPID publique pour les notifications web - Remplacez par votre vraie clé VAPID
// Vous pouvez la trouver dans la console Firebase: Paramètres du projet > Cloud Messaging > Web configuration > Paire de clés
export const vapidKey = "BC3ib1ok_YbC5aRcLH0PLjjyM1rUw8QKonAioAOglhIaRp4D9ejhUsqoBSX1_ArjMW0O0Yf8OzIF3XT_dvETmWo";

// Variable pour stocker le token FCM
let fcmToken = null;

// Initialiser Firebase Messaging et obtenir le token
export const initializeMessaging = async () => {
  try {
    // Vérifier si le navigateur supporte Firebase Messaging
    const isSupportedMessaging = await isSupported();
    if (!isSupportedMessaging) {
      console.log('Firebase Messaging n\'est pas supporté dans cet environnement');
      return null;
    }

    // Vérifier si le service worker est enregistré
    if (!('serviceWorker' in navigator)) {
      console.log('Les Service Workers ne sont pas supportés dans ce navigateur');
      return null;
    }

    // Enregistrer le service worker si ce n'est pas déjà fait
    const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
    console.log('Service Worker enregistré avec succès:', registration);

    // Initialiser Firebase Messaging
    const messaging = getMessaging(app);
    
    // Demander la permission et obtenir le token
    try {
      const permission = await Notification.requestPermission();
      if (permission === 'granted') {
        console.log('Permission de notification accordée');
        
        // Obtenir le token FCM
        fcmToken = await getToken(messaging, { vapidKey });
        if (fcmToken) {
          console.log('Token FCM obtenu:', fcmToken);
          // Stocker le token dans localStorage pour le réutiliser
          localStorage.setItem('fcmToken', fcmToken);
          return messaging;
        } else {
          console.log('Impossible d\'obtenir le token FCM');
          // Essayer de récupérer un token précédemment stocké
          const storedToken = localStorage.getItem('fcmToken');
          if (storedToken) {
            console.log('Utilisation d\'un token FCM précédemment stocké');
            fcmToken = storedToken;
            return messaging;
          }
        }
      } else {
        console.log('Permission de notification refusée');
      }
    } catch (tokenError) {
      console.error('Erreur lors de l\'obtention du token FCM:', tokenError);
    }
    
    return messaging;
  } catch (error) {
    console.error('Erreur lors de l\'initialisation de Firebase Messaging:', error);
    return null;
  }
};

// Fonction pour obtenir le token FCM actuel
export const getFCMToken = async () => {
  // Si nous avons déjà un token, le retourner
  if (fcmToken) {
    return fcmToken;
  }
  
  // Sinon, vérifier dans le localStorage
  const storedToken = localStorage.getItem('fcmToken');
  if (storedToken) {
    fcmToken = storedToken;
    return fcmToken;
  }
  
  // Si toujours pas de token, essayer d'en obtenir un nouveau
  try {
    const messaging = await initializeMessaging();
    if (messaging) {
      const newToken = await getToken(messaging, { vapidKey });
      if (newToken) {
        fcmToken = newToken;
        localStorage.setItem('fcmToken', newToken);
        return newToken;
      }
    }
  } catch (error) {
    console.error('Erreur lors de l\'obtention d\'un nouveau token FCM:', error);
  }
  
  return null;
};

// Initialiser le messaging au démarrage de l'application
initializeMessaging();