import { db } from "./firebase-config.js";
import {
  doc,
  getDoc,
  setDoc,
  serverTimestamp
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";
import { requireRole } from "./auth-guard.js";

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

(async () => {
  const current = await requireRole("admin");
  if (!current) return;

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const username = normalizeUsername(document.getElementById("username").value);
    const email = document.getElementById("email").value.trim();
    const lguId = document.getElementById("lguId").value.trim();
    const positionRank = document.getElementById("positionRank").value.trim();
    const municipality = document.getElementById("municipality").value.trim();

    if (!isValidUsername(username)) {
      showMessage("Username must be 3 to 20 characters and can only use lowercase letters, numbers, and underscores.");
      return;
    }

    if (!email || !lguId || !positionRank || !municipality) {
      showMessage("All fields are required.");
      return;
    }

    submitButton.disabled = true;
    showMessage("Saving request...", false);

    try {
      const requestRef = doc(db, "engineer_requests", username);
      const existing = await getDoc(requestRef);

      if (existing.exists()) {
        showMessage("A request for this username already exists.");
        return;
      }

      await setDoc(requestRef, {
        username,
        email,
        lguId,
        positionRank,
        municipality,
        status: "pending",
        requestedByAdminUid: current.user.uid,
        requestedByAdminUsername: current.profile.username || "",
        createdAt: serverTimestamp()
      });

      showMessage("Request saved. Approve it from the requests page.", false);
      form.reset();
    } catch (error) {
      console.error("REQUEST ERROR:", error);
      showMessage(error.message || "Failed to save request.");
    } finally {
      submitButton.disabled = false;
    }
  });
})();