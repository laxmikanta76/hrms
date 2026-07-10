const CACHE_NAME = "accrosian-hrms-v1";

const urlsToCache = [
    "/",
    "/assets/css/bootstrap.min.css",
    "/assets/css/style.css",
    "/assets/js/jquery.min.js",
    "/assets/js/bootstrap.min.js",
    "/assets/img/logo.png"
];

self.addEventListener("install", event => {

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );

});

self.addEventListener("fetch", event => {

    event.respondWith(

        caches.match(event.request)

        .then(response => {

            return response || fetch(event.request);

        })

    );

});

self.addEventListener("fetch", function(event){

event.respondWith(

fetch(event.request)

.catch(function(){

return caches.match("offline.html");

})

);

});