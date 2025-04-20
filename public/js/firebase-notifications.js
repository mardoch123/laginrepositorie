document.addEventListener('DOMContentLoaded', function() {
    console.log('Initialisation des notifications Firebase...');
    
    // Vérifier si Firebase est défini
    if (typeof firebase === 'undefined') {
      console.error('Firebase n\'est pas chargé. Vérifiez vos scripts.');
      return;
    }
  
    // Vérifier si l'utilisateur est sur un appareil mobile
    function isMobileDevice() {
      return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    // Fonction pour afficher une notification simulée
    function showNotification(title, body) {
      // Créer un élément de notification
      const notification = document.createElement('div');
      notification.className = 'fixed top-4 right-4 bg-white border border-gray-300 rounded-lg shadow-lg p-4 max-w-sm z-50';
      notification.style.minWidth = '300px';
      
      // Contenu de la notification
      notification.innerHTML = `
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
          </div>
          <div class="ml-3 w-0 flex-1">
            <p class="text-sm font-medium text-gray-900">${title}</p>
            <p class="mt-1 text-sm text-gray-500">${body}</p>
          </div>
          <div class="ml-4 flex-shrink-0 flex">
            <button class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
              <span class="sr-only">Fermer</span>
              <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      `;
      
      // Ajouter la notification au document
      document.body.appendChild(notification);
      
      // Ajouter un gestionnaire d'événements pour le bouton de fermeture
      notification.querySelector('button').addEventListener('click', function() {
        document.body.removeChild(notification);
      });
      
      // Supprimer automatiquement la notification après 10 secondes (plus long pour être sûr de la voir)
      setTimeout(() => {
        if (document.body.contains(notification)) {
          document.body.removeChild(notification);
        }
      }, 10000);
    }
  
    // Initialiser Firebase Messaging
    try {
      const messaging = firebase.messaging();
      console.log('Firebase Messaging initialisé avec succès');
      
      // Fonction pour demander la permission et obtenir le token
      function requestPermissionAndGetToken() {
        console.log('Demande de permission pour les notifications...');
        
        Notification.requestPermission().then((permission) => {
          if (permission === 'granted') {
            console.log('Permission accordée!');
            
            // Obtenir le token FCM
            messaging.getToken()
              .then((currentToken) => {
                if (currentToken) {
                  console.log('Token FCM obtenu:', currentToken);
                  saveTokenToServer(currentToken);
                } else {
                  console.warn('Impossible d\'obtenir le token FCM');
                  alert('Impossible d\'obtenir le token de notification. Veuillez réessayer ou vérifier les paramètres de votre navigateur.');
                }
              })
              .catch((err) => {
                console.error('Erreur lors de la récupération du token FCM:', err);
                alert('Erreur lors de l\'activation des notifications: ' + err.message);
              });
          } else {
            console.warn('Permission de notification refusée');
            alert('Vous devez autoriser les notifications pour recevoir des alertes.');
          }
        });
      }
      
      // Fonction pour enregistrer le token sur le serveur
      function saveTokenToServer(token) {
        console.log('Tentative d\'enregistrement du token sur le serveur...');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/fcm-token', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ token: token })
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Erreur réseau lors de l\'enregistrement du token');
          }
          return response.json();
        })
        .then(data => {
          console.log('Token enregistré sur le serveur:', data);
          // Afficher un message de succès
          alert('Notifications activées avec succès!');
        })
        .catch(error => {
          console.error('Erreur lors de l\'enregistrement du token:', error);
          alert('Erreur lors de l\'enregistrement des notifications: ' + error.message);
        });
      }
      
      // Ajouter un gestionnaire d'événements pour le bouton de test des notifications
      const testNotificationBtn = document.getElementById('test-fcm');
      if (testNotificationBtn) {
        console.log('Bouton de test des notifications trouvé');
        testNotificationBtn.addEventListener('click', function(e) {
          e.preventDefault(); // Empêcher le comportement par défaut du bouton
          console.log('Bouton de test des notifications cliqué');
          
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          
          // Vérifier si nous sommes sur un ordinateur de bureau pour utiliser le mode test
          const useTestMode = !isMobileDevice();
          const url = useTestMode ? '/send-fcm-notification?test_mode=1' : '/send-fcm-notification';
          
          fetch(url, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            console.log('Réponse du serveur:', data);
            
            if (data.success) {
              if (data.test_mode) {
                // Afficher une notification simulée pour le mode test
                showNotification('Test de notification', 'Ceci est une simulation de notification (mode test)');
                console.log('Notification simulée affichée en mode test');
              } else {
                alert('Notification envoyée avec succès!');
              }
            } else {
              if (useTestMode) {
                // Même en cas d'erreur, afficher une notification simulée en mode test
                showNotification('Test de notification', 'Ceci est une simulation de notification (mode test)');
                console.log('Notification simulée affichée malgré l\'erreur (mode test)');
              } else {
                alert('Erreur: ' + data.message);
                
                // Si aucun token n'est trouvé, proposer d'en obtenir un
                if (data.message && data.message.includes('Aucun token FCM trouvé')) {
                  if (confirm('Voulez-vous activer les notifications maintenant?')) {
                    requestPermissionAndGetToken();
                  }
                }
              }
            }
          })
          .catch(error => {
            console.error('Erreur:', error);
            
            if (useTestMode) {
              // Même en cas d'erreur réseau, afficher une notification simulée en mode test
              showNotification('Test de notification', 'Ceci est une simulation de notification (mode test)');
              console.log('Notification simulée affichée malgré l\'erreur réseau (mode test)');
            } else {
              alert('Erreur lors de l\'envoi de la notification: ' + error.message);
            }
          });
        });
      } else {
        console.warn('Bouton de test des notifications non trouvé');
      }
      
      // Ajouter un gestionnaire d'événements pour le bouton d'activation des notifications
      const enablePushBtn = document.getElementById('enable-push');
      if (enablePushBtn) {
        console.log('Bouton d\'activation des notifications trouvé');
        enablePushBtn.addEventListener('click', function() {
          console.log('Bouton d\'activation des notifications cliqué');
          requestPermissionAndGetToken();
        });
      } else {
        console.warn('Bouton d\'activation des notifications non trouvé');
      }
      
    } catch (error) {
      console.error('Erreur lors de l\'initialisation de Firebase Messaging:', error);
    }
  });