import { getFirestore, collection, addDoc, Timestamp } from 'firebase/firestore';
import { getMessaging, getToken } from 'firebase/messaging';
import { initializeApp } from 'firebase/app';
import { firebaseConfig } from '../firebase';

// Initialiser Firebase si ce n'est pas déjà fait
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);
const messaging = getMessaging(app);

// Fonction pour tester l'envoi d'une notification
export const testSendNotification = async () => {
  try {
    // Obtenir le token de l'appareil actuel
    const currentToken = await getToken(messaging, {
      vapidKey: 'BC3ib1ok_YbC5aRcLH0PLjjyM1rUw8QKonAioAOglhIaRp4D9ejhUsqoBSX1_ArjMW0O0Yf8OzIF3XT_dvETmWo'
    });
    
    if (!currentToken) {
      console.error('Impossible d\'obtenir le token. Permissions refusées?');
      return false;
    }
    
    // Créer un document de test dans une collection de notifications
    await addDoc(collection(db, 'notifications'), {
      title: 'Test de notification d\'élevage',
      body: 'Ceci est un test pour les femelles prêtes à l\'accouplement',
      token: currentToken,
      type: 'breeding_ready',
      created: Timestamp.now(),
      data: {
        count: '3',
        femaleIds: JSON.stringify(['id1', 'id2', 'id3'])
      }
    });
    
    console.log('Notification de test envoyée avec succès!');
    return true;
  } catch (error) {
    console.error('Erreur lors de l\'envoi de la notification de test:', error);
    return false;
  }
};

// Fonction pour simuler des femelles prêtes à l'accouplement
export const createTestBreedingReadyFemales = async () => {
  try {
    const today = new Date();
    const oneYearAgo = new Date();
    oneYearAgo.setFullYear(today.getFullYear() - 1);
    
    const sevenMonthsAgo = new Date();
    sevenMonthsAgo.setMonth(today.getMonth() - 7);
    
    // Créer quelques femelles de test
    const females = [
      {
        nom: 'Femelle Test 1',
        sexe: 'femelle',
        statut: 'adulte',
        dateNaissance: Timestamp.fromDate(oneYearAgo),
        dernierAccouchement: null,
        race: 'Race Test',
        poids: '45kg'
      },
      {
        nom: 'Femelle Test 2',
        sexe: 'femelle',
        statut: 'adulte',
        dateNaissance: Timestamp.fromDate(oneYearAgo),
        dernierAccouchement: Timestamp.fromDate(sevenMonthsAgo),
        race: 'Race Test',
        poids: '50kg'
      }
    ];
    
    // Ajouter les femelles à la base de données
    for (const female of females) {
      await addDoc(collection(db, 'animaux'), female);
    }
    
    console.log('Femelles de test créées avec succès!');
    return true;
  } catch (error) {
    console.error('Erreur lors de la création des femelles de test:', error);
    return false;
  }
};