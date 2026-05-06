import { requireRole, logoutAndGoHome } from "./auth-guard.js";

const welcomeEl = document.getElementById("welcome");
const emailEl = document.getElementById("email");
const logoutBtn = document.getElementById("logoutBtn");

(async () => {
  const current = await requireRole("engineer");

  if (!current) {
    return;
  }

  welcomeEl.textContent = `Welcome, ${current.profile.username}`;
  emailEl.textContent = `Email: ${current.profile.email}`;
})();

logoutBtn.addEventListener("click", async () => {
  await logoutAndGoHome();
});