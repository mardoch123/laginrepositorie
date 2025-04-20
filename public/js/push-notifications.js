// Service Worker
// Vérifier si le navigateur supporte les Service Workers
if ('serviceWorker' in navigator && 'PushManager' in window) {
    window.addEventListener('load', function() {
        // Enregistrer le Service Worker
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('Service Worker enregistré avec succès:', registration);
                initPushNotifications(registration);
            })
            .catch(function(error) {
                console.log('Erreur lors de l\'enregistrement du Service Worker:', error);
            });
    });
}

// Initialiser les notifications push
function initPushNotifications(registration) {
    const pushButton = document.getElementById('enable-push');
    if (!pushButton) return;
    
    let isSubscribed = false;
    
    // Vérifier si déjà abonné
    registration.pushManager.getSubscription()
        .then(function(subscription) {
            isSubscribed = subscription !== null;
            updatePushButton(isSubscribed);
            
            if (isSubscribed) {
                console.log('Utilisateur déjà abonné aux notifications push');
                saveSubscription(subscription);
            }
        });
    
    // Gérer le clic sur le bouton
    pushButton.addEventListener('click', function() {
        if (isSubscribed) {
            unsubscribeFromPush(registration);
        } else {
            subscribeToPush(registration);
        }
    });
}

// S'abonner aux notifications push
function subscribeToPush(registration) {
    const vapidPublicKeyMeta = document.querySelector('meta[name="vapid-public-key"]');
    
    if (!vapidPublicKeyMeta) {
        console.error('La clé VAPID publique n\'est pas définie dans les meta tags');
        alert('Configuration des notifications incomplète. Veuillez contacter l\'administrateur.');
        return;
    }
    
    const vapidPublicKey = vapidPublicKeyMeta.getAttribute('content');
    
    if (!vapidPublicKey) {
        console.error('La clé VAPID publique est vide');
        alert('Configuration des notifications incomplète. Veuillez contacter l\'administrateur.');
        return;
    }
    
    const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);
    
    registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: convertedVapidKey
    })
    .then(function(subscription) {
        console.log('Abonné aux notifications push:', subscription);
        saveSubscription(subscription);
        updatePushButton(true);
    })
    .catch(function(error) {
        console.error('Erreur lors de l\'abonnement:', error);
        if (Notification.permission === 'denied') {
            console.warn('Les notifications sont bloquées par l\'utilisateur');
            alert('Vous avez bloqué les notifications. Veuillez les autoriser dans les paramètres de votre navigateur.');
        }
    });
}

// Se désabonner des notifications push
function unsubscribeFromPush(registration) {
    registration.pushManager.getSubscription()
        .then(function(subscription) {
            if (subscription) {
                return subscription.unsubscribe();
            }
        })
        .then(function() {
            console.log('Désabonné des notifications push');
            deleteSubscription();
            updatePushButton(false);
        })
        .catch(function(error) {
            console.error('Erreur lors du désabonnement:', error);
        });
}

// Mettre à jour l'apparence du bouton
function updatePushButton(isSubscribed) {
    const pushButton = document.getElementById('enable-push');
    if (!pushButton) return;
    
    if (isSubscribed) {
        pushButton.textContent = 'Désactiver les notifications';
        pushButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
        pushButton.classList.add('bg-red-600', 'hover:bg-red-700');
    } else {
        pushButton.textContent = 'Activer les notifications';
        pushButton.classList.remove('bg-red-600', 'hover:bg-red-700');
        pushButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
    }
}

// Envoyer l'abonnement au serveur
function saveSubscription(subscription) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Convertir la souscription en objet JSON
    const subscriptionJson = subscription.toJSON();
    
    // Ajouter l'encodage du contenu
    subscriptionJson.contentEncoding = (PushManager.supportedContentEncodings || ['aes128gcm'])[0];
    
    console.log('Envoi de la souscription au serveur:', subscriptionJson);
    
    fetch('/push-subscriptions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify(subscriptionJson)
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error('Erreur réseau lors de l\'enregistrement de l\'abonnement');
        }
        return response.json();
    })
    .then(function(data) {
        console.log('Abonnement enregistré sur le serveur:', data);
    })
    .catch(function(error) {
        console.error('Erreur lors de l\'enregistrement de l\'abonnement:', error);
    });
}

// Supprimer l'abonnement du serveur
function deleteSubscription() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/push-subscriptions', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        }
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        console.log('Abonnement supprimé du serveur:', data);
    })
    .catch(function(error) {
        console.error('Erreur lors de la suppression de l\'abonnement:', error);
    });
}

// Convertir la clé VAPID en format approprié
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');
    
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}