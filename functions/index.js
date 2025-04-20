const functions = require("firebase-functions");
const admin = require("firebase-admin");

// Initialiser l'application Firebase Admin
admin.initializeApp();

// Fonction pour tester l'envoi d'une notification directe
exports.testDirectNotification = functions.https.onCall(async (data, context) => {
  try {
    const {token} = data;

    if (!token) {
      throw new functions.https.HttpsError(
          "invalid-argument",
          "Le token FCM est requis.",
      );
    }

    const message = {
      notification: {
        title: "Test de notification",
        body: "Ceci est un test de notification envoyé à " + new Date().toLocaleString(),
      },
      token: token,
    };

    const response = await admin.messaging().send(message);
    console.log("Notification de test envoyée avec succès:", response);

    return {success: true, messageId: response};
  } catch (error) {
    console.error("Erreur lors de l'envoi de la notification de test:", error);
    throw new functions.https.HttpsError(
        "internal",
        "Erreur lors de l'envoi de la notification de test: " + error.message,
        error,
    );
  }
});

// Cloud Function pour envoyer des notifications
exports.sendBreedingNotification = functions.https.onCall(async (data, context) => {
  try {
    const {title, body, data: notificationData} = data;

    // Envoyer à un sujet spécifique (tous les utilisateurs abonnés)
    const message = {
      notification: {
        title,
        body,
      },
      data: notificationData || {},
      topic: "breeding_notifications",
    };

    const response = await admin.messaging().send(message);
    console.log("Notification envoyée avec succès:", response);

    return {success: true, messageId: response};
  } catch (error) {
    console.error("Erreur lors de l'envoi de la notification:", error);
    throw new functions.https.HttpsError(
        "internal",
        "Erreur lors de l'envoi de la notification.",
        error,
    );
  }
});

// S'abonner à un sujet FCM
exports.subscribeToTopic = functions.https.onCall(async (data, context) => {
  try {
    const {token, topic} = data;

    if (!token || !topic) {
      throw new functions.https.HttpsError(
          "invalid-argument",
          "Le token et le sujet sont requis.",
      );
    }

    await admin.messaging().subscribeToTopic([token], topic);
    console.log(`Token ${token} abonné au sujet ${topic}`);

    return {success: true};
  } catch (error) {
    console.error("Erreur lors de l'abonnement au sujet:", error);
    throw new functions.https.HttpsError(
        "internal",
        "Erreur lors de l'abonnement au sujet.",
        error,
    );
  }
});

// Écouter les nouveaux documents dans la collection notifications
exports.processNotifications = functions.firestore
    .document("notifications/{notificationId}")
    .onCreate(async (snapshot, context) => {
      try {
        const notificationData = snapshot.data();

        if (notificationData.status !== "pending") {
          return null;
        }

        // Envoyer la notification via FCM
        const message = {
          notification: {
            title: notificationData.title,
            body: notificationData.body,
          },
          data: notificationData.data || {},
          topic: "breeding_notifications",
        };

        // Si nous avons un token spécifique, l'utiliser au lieu du topic
        if (notificationData.token) {
          message.token = notificationData.token;
          delete message.topic;
        }

        const response = await admin.messaging().send(message);

        // Mettre à jour le statut de la notification
        await snapshot.ref.update({
          status: "sent",
          sentAt: admin.firestore.FieldValue.serverTimestamp(),
          fcmResponse: response,
        });

        return {success: true};
      } catch (error) {
        console.error("Erreur lors du traitement de la notification:", error);

        // Mettre à jour le statut en cas d'erreur
        await snapshot.ref.update({
          status: "error",
          error: error.message,
        });

        return {success: false, error: error.message};
      }
    });
