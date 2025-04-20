import { getMessaging, getToken } from 'firebase/messaging';
import { getFunctions, httpsCallable } from 'firebase/functions';

export const subscribeToBreedingNotifications = async () => {
  try {
    const messaging = getMessaging();
    const token = await getToken(messaging, {
      vapidKey: 'BC3ib1ok_YbC5aRcLH0PLjjyM1rUw8QKonAioAOglhIaRp4D9ejhUsqoBSX1_ArjMW0O0Yf8OzIF3XT_dvETmWo'
    });
    
    if (token) {
      const functions = getFunctions();
      const subscribeToTopic = httpsCallable(functions, 'subscribeToTopic');
      
      await subscribeToTopic({
        token,
        topic: 'breeding_notifications'
      });
      
      console.log('Abonné aux notifications d\'élevage avec succès');
      return true;
    } else {
      console.error('Impossible d\'obtenir le token FCM');
      return false;
    }
  } catch (error) {
    console.error('Erreur lors de l\'abonnement aux notifications:', error);
    return false;
  }
};