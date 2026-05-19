const CACHE_NAME = "cab-booking-cache-v1";

const urlsToCache = [
    "/",
    "/manifest.json",
    "/images/icon-192.png",
    "/images/icon-512.png"
];

self.addEventListener("install", event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(urlsToCache);
        })
    );
});

self.addEventListener("fetch", event => {
    event.respondWith(
        fetch(event.request).catch(() => {
            return caches.match(event.request);
        })
    );
});