import { db } from "./firebase-config.js";
import {
  collection,
  doc,
  getDocs,
  updateDoc,
  serverTimestamp
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-firestore.js";
import { requireRole } from "./auth-guard.js";

const requestList = document.getElementById("requestList");

let currentAdmin = null;

function renderMessage(text) {
  requestList.innerHTML = `<p>${text}</p>`;
}

async function loadRequests() {
  requestList.innerHTML = "";

  const snap = await getDocs(collection(db, "engineer_requests"));
  const rows = [];

  snap.forEach((d) => {
    const data = d.data();
    if (data.status === "pending") {
      rows.push({ id: d.id, ...data });
    }
  });

  if (!rows.length) {
    renderMessage("No pending requests.");
    return;
  }

  rows.forEach((req) => {
    const card = document.createElement("div");
    card.style.border = "1px solid #ccc";
    card.style.padding = "12px";
    card.style.marginBottom = "12px";

    card.innerHTML = `
      <strong>${req.username}</strong><br>
      Email: ${req.email}<br>
      LGU ID: ${req.lguId}<br>
      Position/Rank: ${req.positionRank}<br>
      Municipality: ${req.municipality}<br>
      Status: ${req.status}<br><br>
      <button data-id="${req.id}">Approve</button>
    `;

    const btn = card.querySelector("button");
    btn.addEventListener("click", async () => {
      await updateDoc(doc(db, "engineer_requests", req.id), {
        status: "approved",
        approvedByAdminUid: currentAdmin.user.uid,
        approvedByAdminUsername: currentAdmin.profile.username || "",
        approvedAt: serverTimestamp()
      });

      await loadRequests();
    });

    requestList.appendChild(card);
  });
}

(async () => {
  const current = await requireRole("admin");
  if (!current) return;

  currentAdmin = current;
  await loadRequests();
})();