import { getFirestore, collection, query, where, getDocs, Timestamp, addDoc } from 'firebase/firestore';
import { getMessaging, getToken } from 'firebase/messaging';
import { getFunctions, httpsCallable } from 'firebase/functions';
import { db } from '../firebase';

// Fonction pour vérifier les femelles prêtes pour l'accouplement
export async function checkBreedingReadyFemales() {
  try {
    const today = new Date();
    const sixMonthsAgo = new Date();
    sixMonthsAgo.setMonth(today.getMonth() - 6);
    
    // Récupérer toutes les femelles
    const femalesQuery = query(
      collection(db, 'animaux'),
      where('sexe', '==', 'femelle'),
      where('statut', '!=', 'enfant')
    );
    
    const femalesSnapshot = await getDocs(femalesQuery);
    const readyFemales = [];
    
    femalesSnapshot.forEach(doc => {
      const female = doc.data();
      
      // Vérifier l'âge (au moins 6 mois)
      const birthDate = female.dateNaissance?.toDate();
      if (!birthDate || birthDate > sixMonthsAgo) {
        return; // Trop jeune ou date de naissance manquante
      }
      
      // Vérifier le temps écoulé depuis le dernier accouchement
      const lastBirthDate = female.dernierAccouchement?.toDate();
      if (lastBirthDate) {
        const recoveryPeriod = new Date(lastBirthDate);
        recoveryPeriod.setMonth(lastBirthDate.getMonth() + 6); // 6 mois de récupération
        
        if (today < recoveryPeriod) {
          return; // Pas assez de temps écoulé depuis le dernier accouchement
        }
      }
      
      // Cette femelle est prête pour l'accouplement
      readyFemales.push({
        id: doc.id,
        nom: female.nom,
        age: calculateAge(birthDate),
        dernierAccouchement: lastBirthDate ? calculateTimeSince(lastBirthDate) : 'Aucun'
      });
    });
    
    // Envoyer une notification si des femelles sont prêtes
    if (readyFemales.length > 0) {
      await sendBreedingReadyNotification(readyFemales);
    }
    
    return readyFemales;
  } catch (error) {
    console.error('Erreur lors de la vérification des femelles prêtes:', error);
    throw error;
  }
}

// Calculer l'âge en mois
function calculateAge(birthDate) {
  const today = new Date();
  let months = (today.getFullYear() - birthDate.getFullYear()) * 12;
  months -= birthDate.getMonth();
  months += today.getMonth();
  return months;
}

// Calculer le temps écoulé depuis le dernier accouchement
function calculateTimeSince(lastBirthDate) {
  const today = new Date();
  let months = (today.getFullYear() - lastBirthDate.getFullYear()) * 12;
  months -= lastBirthDate.getMonth();
  months += today.getMonth();
  return `${months} mois`;
}

// Envoyer une notification pour les femelles prêtes
async function sendBreedingReadyNotification(readyFemales) {
  try {
    // Méthode 1: Utiliser Cloud Functions (recommandé)
    const functions = getFunctions();
    const sendNotification = httpsCallable(functions, 'sendBreedingNotification');
    
    await sendNotification({
      title: 'Femelles prêtes pour l\'accouplement',
      body: `${readyFemales.length} femelles sont prêtes pour l'accouplement.`,
      data: {
        type: 'breeding_ready',
        count: readyFemales.length.toString(),
        femaleIds: readyFemales.map(f => f.id)
      }
    });
    
    console.log('Notification envoyée pour les femelles prêtes à l\'accouplement');
    return true;
  } catch (error) {
    console.error('Erreur lors de l\'envoi de la notification:', error);
    
    // Méthode alternative: Créer un document dans Firestore pour déclencher une notification
    try {
      await addDoc(collection(db, 'notifications'), {
        title: 'Femelles prêtes pour l\'accouplement',
        body: `${readyFemales.length} femelles sont prêtes pour l'accouplement.`,
        type: 'breeding_ready',
        created: Timestamp.now(),
        data: {
          count: readyFemales.length.toString(),
          femaleIds: JSON.stringify(readyFemales.map(f => f.id))
        },
        status: 'pending'
      });
      
      console.log('Document de notification créé avec succès');
      return true;
    } catch (secondError) {
      console.error('Échec de la méthode alternative:', secondError);
      throw secondError;
    }
  }
}

// Exécuter la vérification quotidiennement
export function scheduleBreedingReadyCheck() {
  // Vérifier une fois par jour à minuit
  const now = new Date();
  const night = new Date(
    now.getFullYear(),
    now.getMonth(),
    now.getDate() + 1, // demain
    0, 0, 0 // minuit
  );
  const timeToMidnight = night.getTime() - now.getTime();
  
  console.log(`Vérification des femelles prêtes programmée dans ${Math.floor(timeToMidnight/1000/60)} minutes`);
  
  setTimeout(() => {
    checkBreedingReadyFemales();
    // Programmer la prochaine vérification dans 24 heures
    setInterval(checkBreedingReadyFemales, 24 * 60 * 60 * 1000);
  }, timeToMidnight);
}