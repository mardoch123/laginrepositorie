document.addEventListener('DOMContentLoaded', function() {
    // Ajouter un bouton de débogage
    const debugButton = document.createElement('button');
    debugButton.textContent = 'Déboguer Notifications';
    debugButton.style.position = 'fixed';
    debugButton.style.bottom = '10px';
    debugButton.style.right = '10px';
    debugButton.style.zIndex = '9999';
    debugButton.style.padding = '10px';
    debugButton.style.backgroundColor = '#4CAF50';
    debugButton.style.color = 'white';
    debugButton.style.border = 'none';
    debugButton.style.borderRadius = '5px';
    debugButton.style.cursor = 'pointer';
    
    debugButton.addEventListener('click', function() {
        checkNotificationStatus();
    });
    
    document.body.appendChild(debugButton);
    
    function checkNotificationStatus() {
        const debugInfo = document.createElement('div');
        debugInfo.style.position = 'fixed';
        debugInfo.style.top = '50%';
        debugInfo.style.left = '50%';
        debugInfo.style.transform = 'translate(-50%, -50%)';
        debugInfo.style.backgroundColor = 'white';
        debugInfo.style.padding = '20px';
        debugInfo.style.borderRadius = '5px';
        debugInfo.style.boxShadow = '0 0 10px rgba(0,0,0,0.5)';
        debugInfo.style.zIndex = '10000';
        debugInfo.style.maxWidth = '80%';
        debugInfo.style.maxHeight = '80%';
        debugInfo.style.overflow = 'auto';
        
        let html = '<h3>Statut des Notifications</h3>';
        
        // Vérifier si les notifications sont supportées
        html += '<p><strong>Notifications supportées:</strong> ' + ('Notification' in window ? 'Oui' : 'Non') + '</p>';
        
        // Vérifier si le Service Worker est supporté
        html += '<p><strong>Service Worker supporté:</strong> ' + ('serviceWorker' in navigator ? 'Oui' : 'Non') + '</p>';
        
        // Vérifier si Push API est supportée
        html += '<p><strong>Push API supportée:</strong> ' + ('PushManager' in window ? 'Oui' : 'Non') + '</p>';
        
        // Vérifier la permission des notifications
        html += '<p><strong>Permission des notifications:</strong> ' + Notification.permission + '</p>';
        
        // Ajouter un bouton pour demander la permission
        html += '<button id="request-permission" style="margin-top: 10px; padding: 5px 10px; background-color: #4CAF50; color: white; border: none; border-radius: 3px; cursor: pointer;">Demander la permission</button>';
        
        // Ajouter un bouton pour vérifier l'enregistrement du Service Worker
        html += '<button id="check-sw" style="margin-top: 10px; margin-left: 10px; padding: 5px 10px; background-color: #2196F3; color: white; border: none; border-radius: 3px; cursor: pointer;">Vérifier Service Worker</button>';
        
        // Ajouter un bouton pour envoyer une notification de test
        html += '<button id="test-notification" style="margin-top: 10px; margin-left: 10px; padding: 5px 10px; background-color: #FF9800; color: white; border: none; border-radius: 3px; cursor: pointer;">Tester Notification</button>';
        
        // Ajouter un bouton pour fermer
        html += '<button id="close-debug" style="margin-top: 10px; margin-left: 10px; padding: 5px 10px; background-color: #f44336; color: white; border: none; border-radius: 3px; cursor: pointer;">Fermer</button>';
        
        debugInfo.innerHTML = html;
        document.body.appendChild(debugInfo);
        
        // Gérer le clic sur le bouton de permission
        document.getElementById('request-permission').addEventListener('click', function() {
            Notification.requestPermission().then(function(permission) {
                document.querySelector('p:nth-child(4)').innerHTML = '<strong>Permission des notifications:</strong> ' + permission;
            });
        });
        
        // Gérer le clic sur le bouton de vérification du Service Worker
        document.getElementById('check-sw').addEventListener('click', function() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.getRegistrations().then(function(registrations) {
                    let swInfo = '<h4>Service Workers enregistrés:</h4>';
                    if (registrations.length === 0) {
                        swInfo += '<p>Aucun Service Worker enregistré</p>';
                    } else {
                        registrations.forEach(function(registration, i) {
                            swInfo += '<p><strong>SW #' + (i+1) + ':</strong> ' + registration.scope + '</p>';
                            swInfo += '<p>État: ' + registration.active ? 'Actif' : 'Inactif' + '</p>';
                        });
                    }
                    
                    const swInfoElement = document.createElement('div');
                    swInfoElement.innerHTML = swInfo;
                    debugInfo.appendChild(swInfoElement);
                });
            }
        });
        
        // Gérer le clic sur le bouton de test de notification
        document.getElementById('test-notification').addEventListener('click', function() {
            if ('Notification' in window && Notification.permission === 'granted') {
                navigator.serviceWorker.ready.then(function(registration) {
                    registration.showNotification('Notification de Test', {
                        body: 'Ceci est une notification de test locale',
                        icon: '/images/icon-192x192.png',
                        badge: '/images/badge.png'
                    });
                });
            } else {
                alert('Les notifications ne sont pas autorisées');
            }
        });
        
        // Gérer le clic sur le bouton de fermeture
        document.getElementById('close-debug').addEventListener('click', function() {
            document.body.removeChild(debugInfo);
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Ajouter un gestionnaire d'événements pour les formulaires de test de notification
    const notificationForms = document.querySelectorAll('form[action*="test-notification"]');
    
    notificationForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Réponse du serveur:', data);
                
                // Afficher un message de succès
                const successMessage = document.createElement('div');
                successMessage.className = 'fixed top-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md z-50';
                successMessage.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">${data.message}</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(successMessage);
                
                // Supprimer le message après 3 secondes
                setTimeout(() => {
                    document.body.removeChild(successMessage);
                }, 3000);
            })
            .catch(error => {
                console.error('Erreur:', error);
                
                // Afficher un message d'erreur
                const errorMessage = document.createElement('div');
                errorMessage.className = 'fixed top-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md z-50';
                errorMessage.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">Erreur lors de l'envoi de la notification. Veuillez réessayer.</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(errorMessage);
                
                // Supprimer le message après 3 secondes
                setTimeout(() => {
                    document.body.removeChild(errorMessage);
                }, 3000);
            });
        });
    });
});