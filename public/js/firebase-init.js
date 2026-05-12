// Firebase Web Push Notification Setup
import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js';
import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging.js';

// Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyDbf5dRkYhalOeNqZRXm6dznLNKdmjcGxw",
  authDomain: "jobone-ea4e2.firebaseapp.com",
  projectId: "jobone-ea4e2",
  storageBucket: "jobone-ea4e2.firebasestorage.app",
  messagingSenderId: "306245555103",
  appId: "1:306245555103:web:YOUR_WEB_APP_ID"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Request notification permission and get token
export async function requestNotificationPermission() {
  try {
    const permission = await Notification.requestPermission();
    
    if (permission === 'granted') {
      console.log('Notification permission granted.');
      
      // Get FCM token
      const token = await getToken(messaging, {
        vapidKey: 'YOUR_VAPID_KEY' // You need to generate this in Firebase Console
      });
      
      if (token) {
        console.log('FCM Token:', token);
        // Send token to your server to save it
        await saveTokenToServer(token);
        return token;
      } else {
        console.log('No registration token available.');
      }
    } else {
      console.log('Notification permission denied.');
    }
  } catch (error) {
    console.error('Error getting notification permission:', error);
  }
}

// Save token to server
async function saveTokenToServer(token) {
  try {
    await fetch('/api/save-fcm-token', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ token })
    });
  } catch (error) {
    console.error('Error saving token:', error);
  }
}

// Handle foreground messages
onMessage(messaging, (payload) => {
  console.log('Message received in foreground:', payload);
  
  // Show notification
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/favicon.ico'
  };
  
  new Notification(notificationTitle, notificationOptions);
});

// Auto-request permission on page load
if ('Notification' in window && 'serviceWorker' in navigator) {
  // Register service worker
  navigator.serviceWorker.register('/firebase-messaging-sw.js')
    .then((registration) => {
      console.log('Service Worker registered:', registration);
    })
    .catch((error) => {
      console.error('Service Worker registration failed:', error);
    });
}
