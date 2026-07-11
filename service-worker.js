const CACHE_NAME = "accrosian-hrms-v2";

self.addEventListener("install", (event) => {
    console.log("Service Worker Installed");
    self.skipWaiting();
});

self.addEventListener("activate", (event) => {
    console.log("Service Worker Activated");
    event.waitUntil(self.clients.claim());
});

self.addEventListener("fetch", (event) => {
    // Let browser handle requests normally
});