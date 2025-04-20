import React, { useState, useEffect } from 'react';
import { Card, Button, Alert, Form } from 'react-bootstrap';
import { getMessaging, getToken } from 'firebase/messaging';
import { getFunctions, httpsCallable } from 'firebase/functions';
import { collection, addDoc, Timestamp } from 'firebase/firestore';
import { db } from '../firebase';

const NotificationTester = () => {
  const [fcmToken, setFcmToken] = useState('');
  const [status, setStatus] = useState({ type: '', message: '' });
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    // Récupérer le token FCM au chargement
    const getMessagingToken = async () => {
      try {
        const messaging = getMessaging();
        const currentToken = await getToken(messaging, {
          vapidKey: 'BC3ib1ok_YbC5aRcLH0PLjjyM1rUw8QKonAioAOglhIaRp4D9ejhUsqoBSX1_ArjMW0O0Yf8OzIF3XT_dvETmWo' // Remplacez par votre clé VAPID
        });

        if (currentToken) {
          setFcmToken(currentToken);
          console.log('Token FCM obtenu:', currentToken);
        } else {
          console.log('Aucun token d\'enregistrement disponible. Demandez la permission d\'envoyer des notifications.');
          setStatus({
            type: 'warning',
            message: 'Permissions de notification non accordées. Veuillez autoriser les notifications.'
          });
        }
      } catch (error) {
        console.error('Erreur lors de la récupération du token:', error);
        setStatus({
          type: 'danger',
          message: `Erreur: ${error.message}`
        });
      }
    };

    getMessagingToken();
  }, []);

  const handleSubscribeToTopic = async () => {
    if (!fcmToken) {
      setStatus({
        type: 'warning',
        message: 'Aucun token FCM disponible. Vérifiez les permissions de notification.'
      });
      return;
    }

    setLoading(true);
    try {
      const functions = getFunctions();
      const subscribeToTopic = httpsCallable(functions, 'subscribeToTopic');
      
      const result = await subscribeToTopic({
        token: fcmToken,
        topic: 'breeding_notifications'
      });
      
      console.log('Résultat de l\'abonnement:', result.data);
      setStatus({
        type: 'success',
        message: 'Abonné avec succès aux notifications d\'élevage!'
      });
    } catch (error) {
      console.error('Erreur lors de l\'abonnement:', error);
      setStatus({
        type: 'danger',
        message: `Erreur: ${error.message}`
      });
    } finally {
      setLoading(false);
    }
  };

  const handleTestTopicNotification = async () => {
    setLoading(true);
    try {
      const functions = getFunctions();
      const sendNotification = httpsCallable(functions, 'sendBreedingNotification');
      
      const result = await sendNotification({
        title: 'Test de notification de sujet',
        body: 'Ceci est un test de notification envoyé à tous les abonnés à ' + new Date().toLocaleString(),
        data: {
          type: 'breeding_ready',
          count: '3',
          url: '/dashboard'
        }
      });
      
      console.log('Résultat de l\'envoi:', result.data);
      setStatus({
        type: 'success',
        message: 'Notification de sujet envoyée avec succès!'
      });
    } catch (error) {
      console.error('Erreur lors de l\'envoi de la notification:', error);
      setStatus({
        type: 'danger',
        message: `Erreur: ${error.message}`
      });
    } finally {
      setLoading(false);
    }
  };

  const handleTestDirectNotification = async () => {
    if (!fcmToken) {
      setStatus({
        type: 'warning',
        message: 'Aucun token FCM disponible. Vérifiez les permissions de notification.'
      });
      return;
    }

    setLoading(true);
    try {
      const functions = getFunctions();
      const testDirectNotification = httpsCallable(functions, 'testDirectNotification');
      
      const result = await testDirectNotification({
        token: fcmToken
      });
      
      console.log('Résultat de l\'envoi direct:', result.data);
      setStatus({
        type: 'success',
        message: 'Notification directe envoyée avec succès!'
      });
    } catch (error) {
      console.error('Erreur lors de l\'envoi de la notification directe:', error);
      setStatus({
        type: 'danger',
        message: `Erreur: ${error.message}`
      });
    } finally {
      setLoading(false);
    }
  };

  const handleTestFirestoreNotification = async () => {
    if (!fcmToken) {
      setStatus({
        type: 'warning',
        message: 'Aucun token FCM disponible. Vérifiez les permissions de notification.'
      });
      return;
    }

    setLoading(true);
    try {
      // Créer un document dans la collection notifications
      await addDoc(collection(db, 'notifications'), {
        title: 'Test via Firestore',
        body: 'Notification créée via Firestore à ' + new Date().toLocaleString(),
        token: fcmToken, // Utiliser le token spécifique
        type: 'test',
        status: 'pending',
        created: Timestamp.now(),
        data: {
          url: '/dashboard',
          type: 'test_notification'
        }
      });
      
      setStatus({
        type: 'success',
        message: 'Document de notification créé avec succès dans Firestore!'
      });
    } catch (error) {
      console.error('Erreur lors de la création du document de notification:', error);
      setStatus({
        type: 'danger',
        message: `Erreur: ${error.message}`
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <Card className="mb-4">
      <Card.Header>
        <h5>Testeur de notifications</h5>
      </Card.Header>
      <Card.Body>
        {status.type && (
          <Alert variant={status.type} dismissible onClose={() => setStatus({ type: '', message: '' })}>
            {status.message}
          </Alert>
        )}
        
        <Form.Group className="mb-3">
          <Form.Label>Token FCM</Form.Label>
          <Form.Control 
            as="textarea" 
            rows={3} 
            value={fcmToken} 
            readOnly 
            placeholder="Chargement du token FCM..." 
          />
          <Form.Text className="text-muted">
            Ce token est utilisé pour identifier votre appareil pour les notifications.
          </Form.Text>
        </Form.Group>
        
        <div className="d-flex flex-wrap gap-2 mb-3">
          <Button 
            variant="primary" 
            onClick={handleSubscribeToTopic}
            disabled={loading || !fcmToken}
          >
            {loading ? 'Abonnement...' : 'S\'abonner au sujet'}
          </Button>
          
          <Button 
            variant="info" 
            onClick={handleTestTopicNotification}
            disabled={loading}
          >
            {loading ? 'Envoi...' : 'Tester notification de sujet'}
          </Button>
          
          <Button 
            variant="success" 
            onClick={handleTestDirectNotification}
            disabled={loading || !fcmToken}
          >
            {loading ? 'Envoi...' : 'Tester notification directe'}
          </Button>
          
          <Button 
            variant="secondary" 
            onClick={handleTestFirestoreNotification}
            disabled={loading || !fcmToken}
          >
            {loading ? 'Création...' : 'Tester via Firestore'}
          </Button>
        </div>
        
        <div className="small text-muted mt-3">
          <p>
            <strong>Dépannage des notifications:</strong>
          </p>
          <ol>
            <li>Vérifiez que les permissions de notification sont activées dans votre navigateur</li>
            <li>Assurez-vous que le service worker Firebase est correctement enregistré</li>
            <li>Vérifiez que votre application est en premier plan ou en arrière-plan (pas fermée)</li>
            <li>Consultez la console du navigateur pour les erreurs</li>
            <li>Vérifiez les journaux Firebase Functions dans la console Firebase</li>
          </ol>
        </div>
      </Card.Body>
    </Card>
  );
};

export default NotificationTester;