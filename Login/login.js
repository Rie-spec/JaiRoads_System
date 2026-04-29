import { auth, db } from "./firebase-config.js";
import {
  signInWithEmailAndPassword,
  signOut
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import { doc, getDoc } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

const form = document.getElementById("loginForm");
const messageEl = document.getElementById("message");

function showMessage(text, isError = true) {
  messageEl.textContent = text;
  messageEl.style.color = isError ? "crimson" : "green";
}

function normalizeUsername(value) {
  return value.trim().toLowerCase();
}

form.addEventListener("submit", async (event) => {
  event.preventDefault();

  const username = normalizeUsername(document.getElementById("username").value);
  const password = document.getElementById("password").value;

  if (!username || !password) {
    showMessage("All fields are required.");
    return;
  }

  try {
    await signOut(auth).catch(() => {});

    const usernameRef = doc(db, "usernames", username);
    const usernameSnap = await getDoc(usernameRef);

    if (!usernameSnap.exists()) {
      showMessage("Username not found.");
      return;
    }

    const uid = usernameSnap.data().uid;

    const userRef = doc(db, "users", uid);
    const userSnap = await getDoc(userRef);

    if (!userSnap.exists()) {
      showMessage("User data missing.");
      return;
    }

    const userData = userSnap.data();
    const email = userData.email;
    const role = userData.role;

    await signInWithEmailAndPassword(auth, email, password);

    if (role === "admin") {
      window.location.href = "admin-dashboard.html";
    } else {
      window.location.href = "dashboard.html";
    }
  } catch (error) {
    console.log("LOGIN ERROR OBJECT:", error);
    console.log("ERROR CODE:", error.code);
    console.log("ERROR MESSAGE:", error.message);

    if (error.code === "auth/wrong-password" || error.code === "auth/invalid-credential") {
      showMessage("Incorrect username or password.");
    } else if (error.code === "auth/user-not-found") {
      showMessage("User not found.");
    } else if (error.code === "auth/network-request-failed") {
      showMessage("Network error. Try again.");
    } else {
      showMessage(`Login failed: ${error.code || error.message}`);
    }
  }
});