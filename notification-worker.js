self.addEventListener('install', function(event) {
    self.skipWaiting();
});

self.addEventListener('activate', function(event) {
    event.waitUntil(
        Promise.all([
            self.clients.claim(),
            // Clear any existing notifications
            self.registration.getNotifications().then(notifications => {
                notifications.forEach(notification => notification.close());
            })
        ])
    );
});

self.addEventListener('push', function(event) {
    if (event.data) {
        const data = event.data.json();
        
        const options = {
            body: data.message,
            tag: 'new-reservation',
            renotify: true
        };

        event.waitUntil(
            self.registration.showNotification('Нова Резервация!', options)
        );
    }
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow('/worker-dashboard.php')
    );
}); 