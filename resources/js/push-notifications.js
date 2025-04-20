const enablePushNotifications = async () => {
    try {
        const serviceWorkerRegistration = await navigator.serviceWorker.register('/sw.js');
        const subscription = await serviceWorkerRegistration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: window.vapidPublicKey
        });

        // Envoyer l'abonnement au serveur
        await fetch('/push-subscriptions', {
            method: 'POST',
            body: JSON.stringify(subscription),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        console.log('Push notification subscription successful');
    } catch (error) {
        console.error('Error enabling push notifications:', error);
    }
};

// Vérifier si le navigateur prend en charge les notifications
if ('serviceWorker' in navigator && 'PushManager' in window) {
    // Attendre que la page soit complètement chargée
    window.addEventListener('load', () => {
        // Demander la permission pour les notifications
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                enablePushNotifications();
            }
        });
    });
}