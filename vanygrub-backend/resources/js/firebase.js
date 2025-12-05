// Firebase configuration for VANYGROUP
// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getAuth } from "firebase/auth";
import { getFirestore } from "firebase/firestore";
import { getStorage } from "firebase/storage";

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: import.meta.env.VITE_FIREBASE_API_KEY || "AIzaSyArg9iQEsm0BNHf5jn8ujbNsvVfqit2nN8",
  authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN || "vanygroup.firebaseapp.com",
  projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID || "vanygroup",
  storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET || "vanygroup.firebasestorage.app",
  messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID || "355023468464",
  appId: import.meta.env.VITE_FIREBASE_APP_ID || "1:355023468464:web:99d10040c7b2c98f810875",
  measurementId: import.meta.env.VITE_FIREBASE_MEASUREMENT_ID || "G-GT22MNMCPP"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

// Initialize Firebase services
export const auth = getAuth(app);
export const db = getFirestore(app);
export const storage = getStorage(app);

// Initialize Analytics (only in browser environment)
let analytics = null;
if (typeof window !== 'undefined') {
  analytics = getAnalytics(app);
}
export { analytics };

export default app;
