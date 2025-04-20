import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';
import NotificationTest from '../components/NotificationTest';
import BreedingReadyFemales from '../components/BreedingReadyFemales';
import TestNotifications from '../components/TestNotifications';
// Import your other dashboard components

// Importez le composant
import NotificationTester from '../components/NotificationTester';

// Ajoutez-le à votre rendu
<Row>
  <Col lg={12}>
    <NotificationTester />
  </Col>
</Row>

const Dashboard = () => {
  return (
    <Container fluid>
      <h1 className="my-4">Tableau de bord</h1>
      
      {/* Composant de test des notifications */}
      <Row>
        <Col lg={12}>
          <NotificationTest />
        </Col>
      </Row>
      
      {/* Autres composants du tableau de bord */}
    </Container>
  );
};

export default Dashboard;