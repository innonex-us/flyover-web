self.addEventListener('push', function (event) {
    let data = {};
    try { data = event.data.json(); } catch (e) {}

    const title   = data.title || 'FlyoverBD';
    const options = {
        body:  data.body  || '',
        icon:  data.icon  || '/logo.png',
        badge: '/logo.png',
        data:  { url: data.url || '/' },
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    const url = event.notification.data.url || '/';
    event.waitUntil(clients.openWindow(url));
});
