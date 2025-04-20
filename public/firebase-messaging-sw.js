// Firebase Cloud Messaging Service Worker
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

// Configuration Firebase - doit correspondre à celle de votre application
firebase.initializeApp({
  apiKey: "AIzaSyDe3mBx2I983oIaTjSV83moKLrqYR-zMuA",
  authDomain: "elevage-projet.firebaseapp.com",
  projectId: "elevage-projet",
  storageBucket: "elevage-projet.firebasestorage.app",
  messagingSenderId: "1061048497751",
  appId: "1:1061048497751:web:5d7118fd7d9c834aa8d512"
});

const messaging = firebase.messaging();

// Gérer les messages en arrière-plan
messaging.onBackgroundMessage((payload) => {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  
  // Personnaliser la notification
  const notificationTitle = payload.notification.title || 'Nouvelle notification';
  const notificationOptions = {
    body: payload.notification.body || '',
    icon: '/logo192.png', // Assurez-vous que ce fichier existe
    badge: '/logo192.png',
    data: payload.data
  };

  // Afficher la notification
  self.registration.showNotification(notificationTitle, notificationOptions);
});

// Gérer le clic sur la notification
self.addEventListener('notificationclick', (event) => {
  console.log('[firebase-messaging-sw.js] Notification click Received.');

  // Fermer la notification
  event.notification.close();

  // Gérer la navigation en fonction des données
  let url = '/dashboard';
  
  if (event.notification.data) {
    if (event.notification.data.url) {
      url = event.notification.data.url;
    } else if (event.notification.data.type === 'breeding_ready') {
      url = '/dashboard/breeding-ready';
    }
  }

  // Ouvrir l'URL
  event.waitUntil(
    clients.matchAll({type: 'window'}).then(windowClients => {
      // Vérifier si une fenêtre est déjà ouverte et la focaliser
      for (let i = 0; i < windowClients.length; i++) {
        const client = windowClients[i];
        if (client.url === url && 'focus' in client) {
          return client.focus();
        }
      }
      
      // Sinon, ouvrir une nouvelle fenêtre
      if (clients.openWindow) {
        return clients.openWindow(url);
      }
    })
  );
});