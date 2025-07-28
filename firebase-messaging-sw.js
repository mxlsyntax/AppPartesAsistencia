//serviceworker NG20250508
// firebase-messaging-sw.js
importScripts("https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js");

firebase.initializeApp({
  apiKey: "AIzaSyDXux48KU--RLU6IZ7i0iBUGLZb6-Wxih4",
  authDomain: "prueba-notificaciones-global.firebaseapp.com",
  projectId: "prueba-notificaciones-global",
  messagingSenderId: "38937493830",
  appId: "1:38937493830:web:eed2c5374d80ae53392547"
});

//"BIeniroSdMLS1HW3gGmvjyAFLGKdD912g7SxBAaa-IurKN3kf1Gpa_mHFhdeM3v2Jc7bXHU1j5fVDcZ-uCSsBo8"

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  const { title, body, icon } = payload.notification;
  self.registration.showNotification(title, { body, icon });
});
