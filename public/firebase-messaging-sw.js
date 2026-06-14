importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: 'FIREBASE_API_KEY',
    authDomain: 'FIREBASE_AUTH_DOMAIN',
    projectId: 'FIREBASE_PROJECT_ID',
    messagingSenderId: 'FIREBASE_MESSAGING_SENDER_ID',
    appId: 'FIREBASE_APP_ID'
});

firebase.messaging().onBackgroundMessage((payload) => {
    self.registration.showNotification(payload.notification?.title || 'LifeFlow', {
        body: payload.notification?.body || 'You have a new reminder.',
        icon: '/icons/icon.svg'
    });
});
