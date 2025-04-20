document.addEventListener('DOMContentLoaded', function() {
    const testButton = document.getElementById('test-notification');
    
    if (testButton) {
        testButton.addEventListener('click', function() {
            fetch('/api/test-notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Notification de test envoyée avec succès!');
                } else {
                    alert('Erreur lors de l\'envoi de la notification: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur s\'est produite lors de l\'envoi de la notification.');
            });
        });
    }
});