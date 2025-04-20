// Service Worker pour l'application hors ligne
const CACHE_NAME = 'gestion-elevage-v1';
const urlsToCache = [
  '/',
  '/offline/app',
  '/css/app.css',
  '/js/app.js',
  '/js/offline.js',
  '/images/logo.png',
  // Ajoutez d'autres ressources statiques ici
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Cache hit - return response
        if (response) {
          return response;
        }
        
        // Clone the request
        const fetchRequest = event.request.clone();
        
        return fetch(fetchRequest).then(
          response => {
            // Check if we received a valid response
            if(!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }
            
            // Clone the response
            const responseToCache = response.clone();
            
            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(event.request, responseToCache);
              });
              
            return response;
          }
        ).catch(() => {
          // If the network is unavailable, return the offline page
          if (event.request.mode === 'navigate') {
            return caches.match('/offline/app');
          }
        });
      })
  );
});

// Synchronisation en arrière-plan lorsque la connexion est rétablie
self.addEventListener('sync', event => {
  if (event.tag === 'sync-data') {
    event.waitUntil(syncData());
  }
});

// Fonction pour synchroniser les données
async function syncData() {
  const db = await openDatabase();
  const pendingData = await db.getAll('pendingChanges');
  
  if (pendingData.length > 0) {
    try {
      const response = await fetch('/offline/upload', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ changes: pendingData }),
      });
      
      if (response.ok) {
        const db = await openDatabase();
        await db.clear('pendingChanges');
        
        // Notifier l'utilisateur que la synchronisation est terminée
        self.registration.showNotification('Synchronisation terminée', {
          body: 'Vos données ont été synchronisées avec succès.',
          icon: '/images/logo.png'
        });
      }
    } catch (error) {
      console.error('Erreur lors de la synchronisation:', error);
    }
  }
}

// Fonction pour ouvrir la base de données IndexedDB
function openDatabase() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('gestionElevageOffline', 1);
    
    request.onupgradeneeded = event => {
      const db = event.target.result;
      db.createObjectStore('rabbits', { keyPath: 'id', autoIncrement: true });
      db.createObjectStore('cages', { keyPath: 'id', autoIncrement: true });
      db.createObjectStore('treatments', { keyPath: 'id', autoIncrement: true });
      db.createObjectStore('pendingChanges', { keyPath: 'id', autoIncrement: true });
      // Autres stores nécessaires
    };
    
    request.onsuccess = event => resolve(event.target.result);
    request.onerror = event => reject(event.target.error);
  });
}