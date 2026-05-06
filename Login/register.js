import { auth, db } from "./firebase-config.js";
import {
  createUserWithEmailAndPassword
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import {
  doc,
  getDoc,
  setDoc,
  serverTimestamp
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

const form = document.getElementById("registerForm");
const message = document.getElementById("message");

function show(msg, error = true) {
  message.textContent = msg;
  message.style.color = error ? "red" : "green";
}

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const username = document.getElementById("username").value.trim().toLowerCase();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;

  try {
    // 1. Check request exists
    const reqRef = doc(db, "engineer_requests", username);
    const reqSnap = await getDoc(reqRef);

    if (!reqSnap.exists()) {
      show("No request found.");
      return;
    }

    const req = reqSnap.data();

    if (req.status !== "approved") {
      show("Request not approved yet.");
      return;
    }

    // 2. Create Firebase Auth account
    const cred = await createUserWithEmailAndPassword(auth, email, password);
    const user = cred.user;

    // 3. Save FULL user profile
    await setDoc(doc(db, "users", user.uid), {
      uid: user.uid,
      username,
      email,
      role: "engineer",

      lguId: req.lguId,
      positionRank: req.positionRank,
      municipality: req.municipality,

      createdByAdmin: req.approvedBy || "",
      createdAt: serverTimestamp()
    });

    // 4. Username mapping
    await setDoc(doc(db, "usernames", username), {
      uid: user.uid,
      email
    });

    show("Account created successfully!", false);

    window.location.href = "dashboard.html";

  } catch (err) {
    console.error(err);
    show(err.message);
  }
});