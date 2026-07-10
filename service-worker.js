const CACHE_NAME = "accrosian-hrms-v1";

const urlsToCache = [
    "./",
    "./manifest.json",
    "./offline.html"
];

self.addEventListener("install", function(event) {

    event.waitUntil(

        caches.open(CACHE_NAME)
        .then(function(cache) {

            return cache.addAll(urlsToCache);

        })

    );

});

self.addEventListener("activate", function(event) {

    event.waitUntil(self.clients.claim());

});

self.addEventListener("fetch", function(event) {

    if(event.request.method !== "GET"){

        return;

    }

    event.respondWith(

        caches.match(event.request)

        .then(function(response){

            return response || fetch(event.request);

        })

        .catch(function(){

            return caches.match("./offline.html");

        })

    );

});