import { auth, db } from "./firebase-config.js";
import { signInWithEmailAndPassword, signOut } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import { doc, getDoc } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";
import { getCurrentProfile } from "./auth-guard.js";

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

    const usernameSnap = await getDoc(doc(db, "usernames", username));

    if (!usernameSnap.exists()) {
      showMessage("Username not found.");
      return;
    }

    const usernameData = usernameSnap.data();
    const email = usernameData.email;

    if (!email) {
      showMessage("Login index is incomplete.");
      return;
    }

    await signInWithEmailAndPassword(auth, email, password);

    const current = await getCurrentProfile();

    if (!current || !current.profile) {
      showMessage("User profile missing.");
      return;
    }

    if (String(current.profile.role || "").toLowerCase() === "admin") {
      window.location.href = "admin-dashboard.html";
    } else {
      window.location.href = "dashboard.html";
    }
  } catch (error) {
    console.log("LOGIN ERROR:", error);
    showMessage(error.message || "Login failed.");
  }
});