const CACHE_NAME = "hrms-v1";

const STATIC_CACHE = [
    "./",
    "./offline.html",
    "./manifest.json"
];

self.addEventListener("install", event => {

    self.skipWaiting();

    event.waitUntil(

        caches.open(CACHE_NAME)

        .then(cache => cache.addAll(STATIC_CACHE))

    );

});

self.addEventListener("activate", event => {

    event.waitUntil(

        caches.keys().then(keys => {

            return Promise.all(

                keys.map(key => {

                    if(key !== CACHE_NAME){

                        return caches.delete(key);

                    }

                })

            );

        })

    );

    self.clients.claim();

});

self.addEventListener("fetch", event => {

    if(event.request.method !== "GET"){

        return;

    }

    const url = new URL(event.request.url);

    // Never cache dynamic modules
    if(

        url.pathname.includes("/attendance") ||

        url.pathname.includes("/leave") ||

        url.pathname.includes("/employee") ||

        url.pathname.includes("/payroll") ||

        url.pathname.includes("/loan") ||

        url.pathname.includes("/reports") ||

        url.pathname.includes("/logout")

    ){

        event.respondWith(fetch(event.request));

        return;

    }

    // Cache static assets automatically
    if(

        url.pathname.includes("/assets/") ||

        url.pathname.endsWith(".css") ||

        url.pathname.endsWith(".js") ||

        url.pathname.endsWith(".png") ||

        url.pathname.endsWith(".jpg") ||

        url.pathname.endsWith(".jpeg") ||

        url.pathname.endsWith(".svg") ||

        url.pathname.endsWith(".woff") ||

        url.pathname.endsWith(".woff2")

    ){

        event.respondWith(

            caches.match(event.request)

            .then(response=>{

                return response ||

                fetch(event.request)

                .then(network=>{

                    caches.open(CACHE_NAME)

                    .then(cache=>{

                        cache.put(event.request,network.clone());

                    });

                    return network;

                });

            })

        );

        return;

    }

    // Network First
    event.respondWith(

        fetch(event.request)

        .catch(()=>{

            return caches.match("./offline.html");

        })

    );

});