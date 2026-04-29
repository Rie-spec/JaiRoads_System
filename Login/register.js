import { auth, db } from "./firebase-config.js";
import { createUserWithEmailAndPassword,  deleteUser} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import { doc, runTransaction, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

const form = document.getElementById("registerForm");
const messageEl = document.getElementById("message");
const submitButton = form.querySelector('button[type="submit"]');

function showMessage(text, isError = true) {
  messageEl.textContent = text;
  messageEl.style.color = isError ? "crimson" : "green";
}

function normalizeUsername(value) {
  return value.trim().toLowerCase();
}

function isValidUsername(username) {
  return /^[a-z0-9_]{3,20}$/.test(username);
}

function isValidRole(role) {
  return role === "engineer" || role === "admin";
}

form.addEventListener("submit", async (event) => {
  event.preventDefault();

  const username = normalizeUsername(document.getElementById("username").value);
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;
  const role = document.getElementById("role").value;

  if (!isValidUsername(username)) {
    showMessage("Username must be 3 to 20 characters and can only use lowercase letters, numbers, and underscores.");
    return;
  }

  if (!isValidRole(role)) {
    showMessage("Please select either Engineer or Admin.");
    return;
  }

  if (password.length < 6) {
    showMessage("Password must be at least 6 characters.");
    return;
  }

  submitButton.disabled = true;
  showMessage("Creating account...", false);

  let createdUser = null;

  try {
    const userCredential = await createUserWithEmailAndPassword(auth, email, password);
    createdUser = userCredential.user;

    const usernameRef = doc(db, "usernames", username);
    const userRef = doc(db, "users", createdUser.uid);

    await runTransaction(db, async (transaction) => {
      const usernameSnap = await transaction.get(usernameRef);

      if (usernameSnap.exists()) {
        throw new Error("USERNAME_TAKEN");
      }

      transaction.set(usernameRef, {
        uid: createdUser.uid,
        createdAt: serverTimestamp()
      });

      transaction.set(userRef, {
        uid: createdUser.uid,
        username: username,
        email: email,
        role: role,
        createdAt: serverTimestamp()
      });
    });

    showMessage("Registration successful!", false);
    window.location.href = "login.html";
  } catch (error) {
    if (createdUser) {
      try {
        await deleteUser(createdUser);
      } catch (cleanupError) {
        console.error("Failed to clean up Auth user:", cleanupError);
      }
    }

    if (error?.message === "USERNAME_TAKEN") {
      showMessage("Username already taken. Please choose another one.");
    } else if (error?.code === "auth/email-already-in-use") {
      showMessage("That email is already registered.");
    } else if (error?.code === "auth/invalid-email") {
      showMessage("Enter a valid email address.");
    } else if (error?.code === "auth/weak-password") {
      showMessage("Password must be at least 6 characters.");
    } else {
      showMessage(error?.message || "Registration failed.");
    }
  } finally {
    submitButton.disabled = false;
  }
});