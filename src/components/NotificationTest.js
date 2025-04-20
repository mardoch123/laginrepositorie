import React, { useState, useEffect } from 'react';
import { Button, Alert, Card, Spinner } from 'react-bootstrap';
import { getFCMToken, initializeMessaging } from '../firebase';
import { getFunctions, httpsCallable } from 'firebase/functions';

const NotificationTest = () => {
  const [token, setToken] = useState('');
  const [status, setStatus] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  useEffect(() => {
    const getToken = async () => {
      try {
        setLoading(true);
        // Initialiser messaging et obtenir le token
        await initializeMessaging();
        const fcmToken = await getFCMToken();
        
        if (fcmToken) {
          setToken(fcmToken);
          setStatus('Token obtenu avec succès');
        } else {
          setError('Impossible d\'obtenir le token FCM. Vérifiez les permissions de notification.');
        }
      } catch (err) {
        setError(`Erreur: ${err.message}`);
      } finally {
        setLoading(false);
      }
    };

    getToken();
  }, []);

  const handleTestNotification = async () => {
    if (!token) {
      setError('Aucun token FCM disponible');
      return;
    }

    try {
      setLoading(true);
      setStatus('Envoi de la notification de test...');
      
      // Appeler la Cloud Function pour envoyer une notification de test
      const functions = getFunctions();
      const testNotification = httpsCallable(functions, 'testDirectNotification');
      
      const result = await testNotification({ token });
      
      setStatus('Notification envoyée avec succès!');
      console.log('Résultat:', result.data);
    } catch (err) {
      setError(`Erreur lors de l'envoi de la notification: ${err.message}`);
    } finally {
      setLoading(false);
    }
  };

  return (
    <Card className="mb-4">
      <Card.Header>Test de Notifications Push</Card.Header>
      <Card.Body>
        {loading && (
          <div className="text-center mb-3">
            <Spinner animation="border" role="status">
              <span className="visually-hidden">Chargement...</span>
            </Spinner>
          </div>
        )}
        
        {error && (
          <Alert variant="danger" onClose={() => setError('')} dismissible>
            {error}
          </Alert>
        )}
        
        {status && !error && (
          <Alert variant="success" onClose={() => setStatus('')} dismissible>
            {status}
          </Alert>
        )}
        
        <div className="mb-3">
          <strong>Token FCM:</strong>
          <p className="text-break">{token || 'Aucun token disponible'}</p>
        </div>
        
        <Button 
          variant="primary" 
          onClick={handleTestNotification}
          disabled={!token || loading}
        >
          {loading ? 'Envoi en cours...' : 'Tester la notification'}
        </Button>
      </Card.Body>
      <Card.Footer className="text-muted">
        <small>
          Assurez-vous que les notifications sont autorisées dans votre navigateur.
          Si vous ne recevez pas de notifications, vérifiez les paramètres de votre navigateur.
        </small>
      </Card.Footer>
    </Card>
  );
};

export default NotificationTest;