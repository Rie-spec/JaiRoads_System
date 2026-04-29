import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { getAuth } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

const firebaseConfig = {
  apiKey: "AIzaSyAMWkuployP7Vx0p7PpxE_UgwjqxmjuaIk",
  authDomain: "maintain-36ddd.firebaseapp.com",
  databaseURL: "https://maintain-36ddd-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "maintain-36ddd",
  storageBucket: "maintain-36ddd.firebasestorage.app",
  messagingSenderId: "513735623640",
  appId: "1:513735623640:web:00f8e8cf0e4419fa1028ad",
  measurementId: "G-9CJW36738W"
};

const app = initializeApp(firebaseConfig);

export const auth = getAuth(app);
export const db = getFirestore(app);