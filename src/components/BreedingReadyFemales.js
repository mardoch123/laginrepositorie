import React, { useState, useEffect } from 'react';
import { collection, query, where, getDocs, Timestamp } from 'firebase/firestore';
import { db } from '../firebase';
import { Card, Table, Button, Badge } from 'react-bootstrap';

const BreedingReadyFemales = () => {
  const [readyFemales, setReadyFemales] = useState([]);
  const [loading, setLoading] = useState(true);
  const [expanded, setExpanded] = useState(false);

  useEffect(() => {
    // Charger seulement un aperçu au début
    fetchBreedingReadyFemales(expanded ? null : 5);
  }, [expanded]);

  const fetchBreedingReadyFemales = async (limit = null) => {
    try {
      setLoading(true);
      const today = new Date();
      const sixMonthsAgo = new Date();
      sixMonthsAgo.setMonth(today.getMonth() - 6);
      
      // Créer une requête pour les femelles non enfants
      let femalesQuery = query(
        collection(db, 'animaux'),
        where('sexe', '==', 'femelle'),
        where('statut', '!=', 'enfant')
      );
      
      const femalesSnapshot = await getDocs(femalesQuery);
      const females = [];
      
      femalesSnapshot.forEach(doc => {
        const female = doc.data();
        const birthDate = female.dateNaissance?.toDate();
        
        // Vérifier l'âge (au moins 6 mois)
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
        
        // Calculer l'âge en mois
        const ageInMonths = calculateAgeInMonths(birthDate);
        
        // Calculer le temps depuis le dernier accouchement
        const timeSinceLastBirth = lastBirthDate 
          ? calculateTimeSince(lastBirthDate) 
          : 'Aucun accouchement';
        
        females.push({
          id: doc.id,
          nom: female.nom,
          age: ageInMonths,
          dernierAccouchement: timeSinceLastBirth,
          race: female.race || 'Non spécifiée',
          poids: female.poids || 'Non spécifié'
        });
      });
      
      // Trier par temps écoulé depuis le dernier accouchement (les plus anciennes d'abord)
      females.sort((a, b) => {
        if (a.dernierAccouchement === 'Aucun accouchement') return -1;
        if (b.dernierAccouchement === 'Aucun accouchement') return 1;
        return b.dernierAccouchement.months - a.dernierAccouchement.months;
      });
      
      // Limiter le nombre de résultats si nécessaire
      const limitedFemales = limit ? females.slice(0, limit) : females;
      
      setReadyFemales(limitedFemales);
      setLoading(false);
    } catch (error) {
      console.error('Erreur lors de la récupération des femelles prêtes:', error);
      setLoading(false);
    }
  };

  const calculateAgeInMonths = (birthDate) => {
    const today = new Date();
    let months = (today.getFullYear() - birthDate.getFullYear()) * 12;
    months -= birthDate.getMonth();
    months += today.getMonth();
    return months;
  };

  const calculateTimeSince = (lastBirthDate) => {
    const today = new Date();
    let months = (today.getFullYear() - lastBirthDate.getFullYear()) * 12;
    months -= lastBirthDate.getMonth();
    months += today.getMonth();
    
    return {
      text: `${months} mois`,
      months: months
    };
  };

  const toggleExpanded = () => {
    setExpanded(!expanded);
  };

  return (
    <Card className="mb-4">
      <Card.Header className="d-flex justify-content-between align-items-center">
        <div>
          <h5 className="mb-0">Femelles prêtes pour l'accouplement</h5>
          {!expanded && readyFemales.length > 0 && (
            <Badge bg="primary" className="ms-2">{readyFemales.length}</Badge>
          )}
        </div>
        <Button 
          variant="outline-primary" 
          size="sm" 
          onClick={toggleExpanded}
        >
          {expanded ? 'Réduire' : 'Voir tout'}
        </Button>
      </Card.Header>
      <Card.Body>
        {loading ? (
          <p>Chargement...</p>
        ) : readyFemales.length === 0 ? (
          <p>Aucune femelle prête pour l'accouplement actuellement.</p>
        ) : (
          <Table responsive striped hover>
            <thead>
              <tr>
                <th>Nom</th>
                <th>Race</th>
                <th>Âge</th>
                <th>Dernier accouchement</th>
                <th>Poids</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {readyFemales.map(female => (
                <tr key={female.id}>
                  <td>{female.nom}</td>
                  <td>{female.race}</td>
                  <td>{female.age} mois</td>
                  <td>
                    {female.dernierAccouchement === 'Aucun accouchement' 
                      ? 'Aucun' 
                      : female.dernierAccouchement.text}
                  </td>
                  <td>{female.poids}</td>
                  <td>
                    <Button 
                      variant="outline-success" 
                      size="sm" 
                      href={`/animaux/${female.id}`}
                    >
                      Détails
                    </Button>
                  </td>
                </tr>
              ))}
            </tbody>
          </Table>
        )}
      </Card.Body>
    </Card>
  );
};

export default BreedingReadyFemales;