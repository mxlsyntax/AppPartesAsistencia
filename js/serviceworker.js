//NG20250507 serviceworker.js CREADO PARA NOTIFICACIONES.


// Maneja notificaciones push (desde servidor real)
self.addEventListener('push', function(event) {
  let data = {};
  try {
    data = event.data.json();
  } catch (e) {
    data = {
      title: 'Notificación',
      body: event.data.text() || 'Tienes una nueva notificación',
      icon: '/icon.png'
    };
  }

  const title = data.title || 'Notificación';
  const options = {
    body: data.body || 'Tienes novedades',
    icon: data.icon || '/icon.png',
    badge: data.badge || '/badge.png',
    data: data.url || '/'
  };

  event.waitUntil(
    self.registration.showNotification(title, options)
  );
});

// Maneja notificaciones enviadas desde JS principal con postMessage
self.addEventListener('message', function(event) {
  const data = event.data;

  if (data && data.action === 'notificar') {
    const title = data.title || 'Notificación local';
    const options = {
      body: data.body || 'Mensaje desde la app',
      icon: data.icon || '/icon.png',
      badge: data.badge || '/badge.png',
      data: data.url || '/'
    };

    self.registration.showNotification(title, options);
  }
});

// Acciones cuando el usuario hace clic en la notificación
self.addEventListener('notificationclick', function(event) {
  event.notification.close();
  const urlToOpen = event.notification.data || '/';

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
      for (const client of clientList) {
        if (client.url === urlToOpen && 'focus' in client) {
          return client.focus();
        }
      }
      if (clients.openWindow) {
        return clients.openWindow(urlToOpen);
      }
    })
  );
});
