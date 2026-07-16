const CACHE_NAME = 'cantcome-v1';
const STATIC_ASSETS = [
    '/manifest.json',
    '/logo.png',
    '/favicon.ico'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;

    if (event.request.url.includes('/api/') || event.request.url.includes('/login') || event.request.url.includes('/logout')) {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match(event.request);
            })
        );
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                if (response && response.status === 200) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                return caches.match(event.request).then((cached) => {
                    if (cached) return cached;
                    if (event.request.destination === 'document') {
                        return caches.match('/dashboard');
                    }
                    return new Response('', { status: 408, statusText: 'Request Timeout' });
                });
            })
    );
});

self.addEventListener('beforeinstallprompt', (event) => {
    event.userChoice.then((choice) => {
        if (choice.outcome === 'accepted') {
            self.clients.matchAll().then((clients) => {
                clients.forEach((client) => {
                    client.postMessage({ type: 'INSTALL_ACCEPTED' });
                });
            });
        }
    });
});
