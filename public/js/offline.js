// Initialisation de l'application hors ligne
document.addEventListener('DOMContentLoaded', () => {
  // Enregistrer le service worker
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
      .then(registration => {
        console.log('Service Worker enregistré avec succès:', registration);
      })
      .catch(error => {
        console.log('Échec de l\'enregistrement du Service Worker:', error);
      });
  }
  
  // Initialiser la base de données locale
  initDatabase();
  
  // Vérifier si nous sommes en ligne ou hors ligne
  updateOnlineStatus();
  window.addEventListener('online', updateOnlineStatus);
  window.addEventListener('offline', updateOnlineStatus);
  
  // Charger les données initiales si nécessaire
  loadInitialData();
  
  // Configurer les gestionnaires d'événements pour les formulaires
  setupFormHandlers();
});

// Fonction pour initialiser la base de données
function initDatabase() {
  const request = indexedDB.open('gestionElevageOffline', 1);
  
  request.onupgradeneeded = event => {
    const db = event.target.result;
    
    // Créer les object stores nécessaires
    if (!db.objectStoreNames.contains('rabbits')) {
      db.createObjectStore('rabbits', { keyPath: 'id', autoIncrement: true });
    }
    
    if (!db.objectStoreNames.contains('cages')) {
      db.createObjectStore('cages', { keyPath: 'id', autoIncrement: true });
    }
    
    if (!db.objectStoreNames.contains('treatments')) {
      db.createObjectStore('treatments', { keyPath: 'id', autoIncrement: true });
    }
    
    if (!db.objectStoreNames.contains('pendingChanges')) {
      db.createObjectStore('pendingChanges', { keyPath: 'id', autoIncrement: true });
    }
    
    // Autres stores nécessaires...
  };
  
  request.onsuccess = event => {
    console.log('Base de données initialisée avec succès');
  };
  
  request.onerror = event => {
    console.error('Erreur lors de l\'initialisation de la base de données:', event.target.error);
  };
}

// Fonction pour mettre à jour le statut en ligne/hors ligne
function updateOnlineStatus() {
  const status = navigator.onLine ? 'en ligne' : 'hors ligne';
  console.log(`Application ${status}`);
  
  const statusElement = document.getElementById('online-status');
  if (statusElement) {
    statusElement.textContent = status;
    statusElement.className = navigator.onLine ? 'text-green-500' : 'text-red-500';
  }
  
  if (navigator.onLine) {
    // Tenter de synchroniser les données
    syncData();
  }
}

// Fonction pour charger les données initiales
async function loadInitialData() {
  // Vérifier si nous avons déjà des données en cache
  const db = await openDatabase();
  const rabbitsCount = await getCount(db, 'rabbits');
  
  if (rabbitsCount === 0 && navigator.onLine) {
    // Aucune donnée en cache et nous sommes en ligne, télécharger les données
    try {
      const response = await fetch('/offline/download');
      const data = await response.json();
      
      // Stocker les données dans IndexedDB
      await storeData(db, 'rabbits', data.rabbits);
      await storeData(db, 'cages', data.cages);
      await storeData(db, 'treatments', data.treatments);
      // Stocker d'autres types de données...
      
      console.log('Données initiales chargées avec succès');
    } catch (error) {
      console.error('Erreur lors du chargement des données initiales:', error);
    }
  } else {
    console.log('Utilisation des données en cache');
    // Charger les données depuis IndexedDB et les afficher
    displayCachedData();
  }
}

// Fonction pour configurer les gestionnaires d'événements des formulaires
function setupFormHandlers() {
  // Intercepter les soumissions de formulaire pour les traiter hors ligne si nécessaire
  document.addEventListener('submit', async event => {
    const form = event.target;
    
    // Vérifier si c'est un formulaire que nous voulons intercepter
    if (form.dataset.offlineEnabled === 'true') {
      event.preventDefault();
      
      // Collecter les données du formulaire
      const formData = new FormData(form);
      const data = {};
      for (const [key, value] of formData.entries()) {
        data[key] = value;
      }
      
      // Ajouter le type d'entité et l'action
      data.entityType = form.dataset.entityType;
      data.action = form.dataset.action || 'create';
      
      // Stocker les données localement
      await storeOfflineChange(data);
      
      // Tenter de synchroniser si en ligne
      if (navigator.onLine) {
        syncData();
      } else {
        // Afficher un message à l'utilisateur
        alert('Vos modifications ont été enregistrées localement et seront synchronisées lorsque vous serez en ligne.');
      }
      
      // Rediriger ou mettre à jour l'interface utilisateur
      if (form.dataset.redirectUrl) {
        window.location.href = form.dataset.redirectUrl;
      }
    }
  });
}

// Fonction pour stocker un changement hors ligne
async function storeOfflineChange(data) {
  const db = await openDatabase();
  const transaction = db.transaction(['pendingChanges'], 'readwrite');
  const store = transaction.objectStore('pendingChanges');
  
  // Ajouter un timestamp
  data.timestamp = Date.now();
  
  // Stocker le changement
  await store.add(data);
  
  return new Promise((resolve, reject) => {
    transaction.oncomplete = () => resolve();
    transaction.onerror = event => reject(event.target.error);
  });
}

// Fonction pour synchroniser les données
async function syncData() {
  if (!navigator.onLine) return;
  
  try {
    // Vérifier s'il y a des changements en attente
    const db = await openDatabase();
    const pendingChanges = await getAllData(db, 'pendingChanges');
    
    if (pendingChanges.length > 0) {
      // Envoyer les changements au serveur
      const response = await fetch('/offline/upload', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ changes: pendingChanges })
      });
      
      if (response.ok) {
        // Effacer les changements synchronisés
        const transaction = db.transaction(['pendingChanges'], 'readwrite');
        const store = transaction.objectStore('pendingChanges');
        await store.clear();
        
        console.log('Données synchronisées avec succès');
        
        // Mettre à jour les données locales avec les dernières du serveur
        await loadInitialData();
      } else {
        console.error('Erreur lors de la synchronisation des données');
      }
    }
  } catch (error) {
    console.error('Erreur lors de la synchronisation:', error);
  }
}

// Fonction pour ouvrir la base de données
function openDatabase() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('gestionElevageOffline', 1);
    request.onsuccess = event => resolve(event.target.result);
    request.onerror = event => reject(event.target.error);
  });
}

// Fonction pour obtenir le nombre d'éléments dans un store
function getCount(db, storeName) {
  return new Promise((resolve, reject) => {
    const transaction = db.transaction([storeName], 'readonly');
    const store = transaction.objectStore(storeName);
    const countRequest = store.count();
    
    countRequest.onsuccess = () => resolve(countRequest.result);
    countRequest.onerror = event => reject(event.target.error);
  });
}

// Fonction pour stocker des données dans un store
function storeData(db, storeName, data) {
  return new Promise((resolve, reject) => {
    const transaction = db.transaction([storeName], 'readwrite');
    const store = transaction.objectStore(storeName);
    
    // Effacer les données existantes
    store.clear();
    
    // Ajouter les nouvelles données
    data.forEach(item => {
      store.add(item);
    });
    
    transaction.oncomplete = () => resolve();
    transaction.onerror = event => reject(event.target.error);
  });
}

// Fonction pour récupérer toutes les données d'un store
function getAllData(db, storeName) {
  return new Promise((resolve, reject) => {
    const transaction = db.transaction([storeName], 'readonly');
    const store = transaction.objectStore(storeName);
    const request = store.getAll();
    
    request.onsuccess = () => resolve(request.result);
    request.onerror = event => reject(event.target.error);
  });
}

// Fonction pour afficher les données en cache
async function displayCachedData() {
  const db = await openDatabase();
  
  // Récupérer et afficher les données selon la page actuelle
  const currentPath = window.location.pathname;
  
  if (currentPath.includes('/rabbits')) {
    const rabbits = await getAllData(db, 'rabbits');
    displayRabbits(rabbits);
  } else if (currentPath.includes('/cages')) {
    const cages = await getAllData(db, 'cages');
    displayCages(cages);
  } else if (currentPath.includes('/treatments')) {
    const treatments = await getAllData(db, 'treatments');
    displayTreatments(treatments);
  }
  // Ajouter d'autres pages selon les besoins
}

// Fonctions d'affichage spécifiques à chaque type de données
function displayRabbits(rabbits) {
  const container = document.getElementById('rabbits-container');
  if (!container) return;
  
  container.innerHTML = '';
  
  rabbits.forEach(rabbit => {
    const element = document.createElement('div');
    element.className = 'bg-white p-4 rounded shadow mb-4';
    element.innerHTML = `
      <h3 class="text-lg font-semibold">${rabbit.name}</h3>
      <p>Numéro de tatouage: ${rabbit.tattoo_number}</p>
      <p>Sexe: ${rabbit.gender}</p>
      <p>Date de naissance: ${new Date(rabbit.birth_date).toLocaleDateString()}</p>
    `;
    container.appendChild(element);
  });
}

// Fonctions similaires pour les autres types de données...