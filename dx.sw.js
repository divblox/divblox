const PRECACHE = 'precache-v1584952839';
const RUNTIME = 'runtime';
// A list of local resources we always want to be cached.
const PRECACHE_URLS = [
	'index.html',
	'project/assets/css/project.css',
    'project/assets/css/theme.css',
	'divblox/assets/js/divblox.js',
	'project/assets/js/project.js',
    'project/assets/images/app_logo.png',
	'project/assets/images/favicon.ico',
	'project/assets/images/app_icon_192.png',
	'project/assets/images/app_icon_512.png',
	'project/assets/images/app_icon_192.jpg',
	'project/assets/images/app_icon_512.jpg',
	'project/assets/images/apple_splash_2048.jpeg',
	'project/assets/images/apple_splash_1668.jpeg',
	'project/assets/images/apple_splash_1536.jpeg',
	'project/assets/images/apple_splash_1125.jpeg',
	'project/assets/images/apple_splash_1242.jpeg',
	'project/assets/images/apple_splash_750.jpeg',
	'project/assets/images/apple_splash_640.jpeg'
];

// The install handler takes care of precaching the resources we always need.
self.addEventListener('install', event => {
	event.waitUntil(
		caches.open(PRECACHE)
			.then(cache => cache.addAll(PRECACHE_URLS))
			.then(self.skipWaiting())
	);
});

self.addEventListener('message', function (event) {
	if (event.data.action === 'skipWaiting') {
		self.skipWaiting();
	}
});

// The activate handler takes care of cleaning up old caches.
self.addEventListener('activate', event => {
	const currentCaches = [PRECACHE, RUNTIME];
	event.waitUntil(
		caches.keys().then(cacheNames => {
			return cacheNames.filter(cacheName => !currentCaches.includes(cacheName));
		}).then(cachesToDelete => {
			return Promise.all(cachesToDelete.map(cacheToDelete => {
				return caches.delete(cacheToDelete);
			}));
		}).then(() => self.clients.claim())
	);
});

self.addEventListener('fetch', function(event) {
	let req = event.request.clone();
	if (req.clone().method == "GET") {
		event.respondWith(
			caches.match(event.request)
				.then(function(response) {
					// Cache hit - return response
					if (response) {
						return response;
					}
					
					// IMPORTANT: Clone the request. A request is a stream and
					// can only be consumed once. Since we are consuming this
					// once by cache and once by the browser for fetch, we need
					// to clone the response.
					var fetchRequest = event.request.clone();
					
					return fetch(fetchRequest).then(
						function(response) {
							// Check if we received a valid response
							if(!response || response.status !== 200 || response.type !== 'basic') {
								return response;
							}
							
							// IMPORTANT: Clone the response. A response is a stream
							// and because we want the browser to consume the response
							// as well as the cache consuming the response, we need
							// to clone it so we have two streams.
							var responseToCache = response.clone();
							
							caches.open(PRECACHE)
								.then(function(cache) {
									cache.put(event.request, responseToCache);
								});
							
							return response;
						}
					);
				})
		);
	}
});