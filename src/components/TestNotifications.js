import React, { useState } from 'react';
import { Card, Button, Alert } from 'react-bootstrap';
import { testSendNotification, createTestBreedingReadyFemales } from '../utils/testBreedingNotifications';
import { checkBreedingReadyFemales } from '../services/breedingNotificationService';

const TestNotifications = () => {
  const [testResult, setTestResult] = useState(null);
  const [loading, setLoading] = useState(false);

  const handleTestNotification = async () => {
    setLoading(true);
    try {
      const result = await testSendNotification();
      setTestResult({
        success: result,
        message: result 
          ? 'Notification de test envoyée avec succès!' 
          : 'Échec de l\'envoi de la notification de test.'
      });
    } catch (error) {
      setTestResult({
        success: false,
        message: `Erreur: ${error.message}`
      });
    } finally {
      setLoading(false);
    }
  };

  const handleCreateTestData = async () => {
    setLoading(true);
    try {
      const result = await createTestBreedingReadyFemales();
      setTestResult({
        success: result,
        message: result 
          ? 'Données de test créées avec succès!' 
          : 'Échec de la création des données de test.'
      });
    } catch (error) {
      setTestResult({
        success: false,
        message: `Erreur: ${error.message}`
      });
    } finally {
      setLoading(false);
    }
  };

  const handleCheckBreedingReady = async () => {
    setLoading(true);
    try {
      const females = await checkBreedingReadyFemales();
      setTestResult({
        success: true,
        message: `${females.length} femelles prêtes à l'accouplement trouvées.`
      });
    } catch (error) {
      setTestResult({
        success: false,
        message: `Erreur: ${error.message}`
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <Card className="mb-4">
      <Card.Header>
        <h5>Tester les notifications d'élevage</h5>
      </Card.Header>
      <Card.Body>
        {testResult && (
          <Alert variant={testResult.success ? 'success' : 'danger'} className="mb-3">
            {testResult.message}
          </Alert>
        )}
        
        <div className="d-flex gap-2 mb-3">
          <Button 
            variant="primary" 
            onClick={handleTestNotification}
            disabled={loading}
          >
            {loading ? 'Envoi...' : 'Tester l\'envoi de notification'}
          </Button>
          
          <Button 
            variant="secondary" 
            onClick={handleCreateTestData}
            disabled={loading}
          >
            {loading ? 'Création...' : 'Créer des données de test'}
          </Button>
          
          <Button 
            variant="info" 
            onClick={handleCheckBreedingReady}
            disabled={loading}
          >
            {loading ? 'Vérification...' : 'Vérifier les femelles prêtes'}
          </Button>
        </div>
        
        <div className="small text-muted">
          <p>
            <strong>Note:</strong> Pour recevoir des notifications, assurez-vous que:
          </p>
          <ol>
            <li>Les permissions de notification sont activées dans votre navigateur</li>
            <li>Le service worker Firebase est correctement enregistré</li>
            <li>Vous avez configuré la clé VAPID publique</li>
          </ol>
        </div>
      </Card.Body>
    </Card>
  );
};

export default TestNotifications;