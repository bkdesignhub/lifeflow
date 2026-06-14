const CACHE_NAME = 'lifeflow-v1';
const APP_SHELL = [
    '/offline',
    '/assets/app.css',
    '/assets/app.js',
    '/icons/icon.svg'
];

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(APP_SHELL)));
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(caches.keys().then((keys) => Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key)))));
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request).then((cached) => cached || caches.match('/offline')))
    );
});

self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : {};
    event.waitUntil(self.registration.showNotification(data.title || 'LifeFlow', {
        body: data.body || 'You have a new LifeFlow reminder.',
        icon: '/icons/icon.svg',
        badge: '/icons/icon.svg'
    }));
});
