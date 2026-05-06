import { requireRole, logoutAndGoHome } from "./auth-guard.js";

const welcomeEl = document.getElementById("welcome");
const emailEl = document.getElementById("email");
const logoutBtn = document.getElementById("logoutBtn");
const goToRegisterBtn = document.getElementById("goToRegister");

(async () => {
  const current = await requireRole("admin");

  if (!current) {
    return;
  }

  welcomeEl.textContent = `Welcome, ${current.profile.username}`;
  emailEl.textContent = `Email: ${current.profile.email}`;
})();

goToRegisterBtn.addEventListener("click", () => {
  window.location.href = "register-engineer.html";
});

logoutBtn.addEventListener("click", async () => {
  await logoutAndGoHome();
});