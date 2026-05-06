import { auth, db } from "./firebase-config.js";
import {
  onAuthStateChanged,
  signOut
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";
import {
  doc,
  getDoc
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";

export function waitForAuthUser() {
  return new Promise((resolve) => {
    const unsubscribe = onAuthStateChanged(auth, (user) => {
      unsubscribe();
      resolve(user);
    });
  });
}

export async function getCurrentProfile() {
  const user = await waitForAuthUser();

  if (!user) {
    return null;
  }

  const snap = await getDoc(doc(db, "users", user.uid));

  return {
    user,
    profile: snap.exists() ? snap.data() : null
  };
}

export async function requireLogin(redirectTo = "login.html") {
  const user = await waitForAuthUser();

  if (!user) {
    window.location.href = redirectTo;
    return null;
  }

  return user;
}

export async function requireRole(requiredRole) {
  const current = await getCurrentProfile();

  if (!current || !current.profile) {
    window.location.href = "login.html";
    return null;
  }

  const role = String(current.profile.role || "").toLowerCase();

  if (role !== requiredRole.toLowerCase()) {
    if (role === "admin") {
      window.location.href = "admin-dashboard.html";
    } else {
      window.location.href = "dashboard.html";
    }
    return null;
  }

  return current;
}

export async function logoutAndGoHome() {
  await signOut(auth);
  window.location.href = "login.html";
}