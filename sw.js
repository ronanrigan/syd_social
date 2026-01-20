const CACHE_NAME = 'syd-social-v2';
const ASSETS_TO_CACHE = [
    '/Syd_Social/index.php',
    '/Syd_Social/assets/css/style.css',
    '/Syd_Social/assets/images/icon-192.png'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // If the network works, clone the response and save it to cache
                return caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, response.clone());
                    return response;
                });
            })
            .catch(() => {
                // If the network fails (Offline), look for it in the cache
                return caches.match(event.request);
            })
    );
});