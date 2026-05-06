<?php
    // Path configuration
    $baseUrl = '../../'; 
    $activePage = 'lgus';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAIROADS - LGU Directory</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/layout.css">
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Directory Layout */
        .lgu-grid { display: grid; grid-template-columns: 2.5fr 2.5fr 1fr; gap: 16px; align-items: center; }
        .records-head.lgu-grid { padding: 20px 30px; border-bottom: 1px solid #e2e8f0; }
        .lgu-row { padding: 14px 30px; border-bottom: 1px solid #f1f5f9; transition: all 0.2s ease; }
        .lgu-row:hover { background-color: #f8fafc; }
        .lgu-info-cell { display: flex; align-items: center; gap: 12px; }
        
        .lgu-icon-wrapper { 
            width: 38px; height: 38px; 
            background: #eeefff; color: #5d5fef; 
            border-radius: 12px; display: flex; 
            align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        /* Fixed View Button */
        .view-btn {
            height: 38px; width: 110px;
            border: 1px solid #e2e8f0; border-radius: 12px;
            background: white; color: #5d5fef;
            font-weight: 700; font-size: 11px;
            display: flex; align-items: center; justify-content: center; 
            gap: 8px; cursor: pointer; justify-self: end;
            transition: all 0.2s ease;
        }
        .view-btn:hover { background: #5d5fef; color: white; border-color: #5d5fef; box-shadow: 0 8px 20px rgba(93, 95, 239, 0.2); }

        /* Modal & Detail Styles */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(30, 41, 59, 0.3); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 9999; }
        .modal-card { background: white; width: 100%; max-width: 680px; border-radius: 32px; padding: 35px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2); }
        .sect { margin-bottom: 25px; }
        .secthead { display: flex; align-items: center; gap: 8px; color: #94a3b8; font-size: 11px; font-weight: 900; text-transform: uppercase; margin-bottom: 12px; }
        .lbl { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; display: block; }
        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .data-box { padding: 12px 16px; background: #f8fafc; border-radius: 14px; border: 1px solid #f1f5f9; font-weight: 700; color: #1e293b; font-size: 14px; }
        
        /* Bridge Cards */
        .item-card { background: #f8fafc; padding: 12px 18px; border-radius: 16px; border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .badge { font-size: 9px; font-weight: 900; background: #eeefff; color: #5d5fef; padding: 5px 10px; border-radius: 10px; text-transform: uppercase; }
    </style>
</head>
<body>

    <div class="page-layout">
        <?php include $baseUrl . 'components/sidebar.php'; ?>

        <main class="main-content">
            <header class="page-heading-row">
                <div class="page-heading-text">
                    <h2 class="page-main-title">LGUs</h2>
                    <p class="page-subtitle">Infrastructure Partners Directory</p>
                </div>
            </header>

            <section class="filter-shell">
                <div class="filter-search">
                    <i class="filter-search-icon" data-lucide="search"></i>
                    <input type="text" id="lguSearchInput" placeholder="Search by municipality or mayor...">
                </div>
                <div class="filter-actions">
                    <button class="refresh-btn" id="refreshBtn" type="button"><i data-lucide="rotate-cw"></i></button>
                </div>
            </section>

            <section class="records-shell">
                <div class="records-head lgu-grid">
                    <div>Municipality</div>
                    <div>Head of LGU</div>
                    <div style="text-align: right; padding-right: 20px;">Action</div>
                </div>
                <div class="records-body" id="lguTableBody"></div>
                <div class="records-footer">
                    <p class="records-note">Showing <span id="lguCount">0</span> Registered LGUs</p>
                </div>
            </section>
        </main>
    </div>

    <div class="modal-overlay" id="lguModal">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div class="lgu-icon-wrapper" style="width: 52px; height: 52px; border-radius: 18px;"><i data-lucide="building-2"></i></div>
                    <div>
                        <h1 style="font-weight: 800; font-size: 24px; color: #1e293b;" id="detMuni">---</h1>
                        <p style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">LGU Profile Overview</p>
                    </div>
                </div>
                <button onclick="closeModal()" style="background:none; border:none; cursor:pointer; color:#94a3b8;"><i data-lucide="x"></i></button>
            </div>

            <div class="sect">
                <div class="secthead"><i data-lucide="info"></i><span>Government Directory</span></div>
                <div class="grid2" style="margin-bottom: 15px;">
                    <div><label class="lbl">Incumbent Mayor</label><div class="data-box" id="detMayor">---</div></div>
                    <div><label class="lbl">Province</label><div class="data-box" id="detProvince">---</div></div>
                </div>
                <div class="grid2">
                    <div><label class="lbl">Office Contact</label><div class="data-box" id="detContact">---</div></div>
                    <div><label class="lbl">Region</label><div class="data-box" id="detRegion">---</div></div>
                </div>
            </div>

            <div class="sect">
                <div class="secthead"><i data-lucide="users"></i><span>Authorized Engineers</span></div>
                <div id="associatedEngineers" style="max-height: 120px; overflow-y: auto;">
                    </div>
            </div>

            <div class="sect">
                <div class="secthead"><i data-lucide="wrench"></i><span>Maintenance Projects Involved</span></div>
                <div id="associatedProjects" style="max-height: 120px; overflow-y: auto;">
                    </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                <button type="button" onclick="closeModal()" class="primary-btn" style="width: auto; padding: 12px 35px;">CLOSE PROFILE</button>
            </div>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
        import { getFirestore, collection, onSnapshot, query, where } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-firestore.js";

        const firebaseConfig = {
            apiKey: "AIzaSyAMWkuployP7Vx0p7PpxE_UgwjqxmjuaIk",
            authDomain: "maintain-36ddd.firebaseapp.com",
            projectId: "maintain-36ddd",
            storageBucket: "maintain-36ddd.firebasestorage.app",
            messagingSenderId: "513735623640",
            appId: "1:513735623640:web:00f8e8cf0e4419fa1028ad"
        };

        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);
        let masterLguList = [];

        // XSS Protection Helper
        const escapeHTML = (str) => {
            if (!str) return '---';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        // 1. Fetch LGUs in Real-time
        onSnapshot(collection(db, "lgus"), (snapshot) => {
            masterLguList = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
            renderTable(masterLguList);
        });

        // 2. Render Table with XSS Guard & Empty State
        window.renderTable = function(data) {
            const body = document.getElementById('lguTableBody');
            const countDisplay = document.getElementById('lguCount');

            if (data.length === 0) {
                body.innerHTML = `
                    <div style="padding: 60px; text-align: center; color: #94a3b8;">
                        <i data-lucide="search-x" style="margin-bottom:10px;"></i>
                        <p>No Record Found</p>
                    </div>`;
                countDisplay.innerText = "0";
                lucide.createIcons();
                return;
            }

            body.innerHTML = data.map(lgu => `
                <div class="lgu-row lgu-grid">
                    <div class="lgu-info-cell">
                        <div class="lgu-icon-wrapper"><i data-lucide="building-2"></i></div>
                        <span class="lgu-main-text">${escapeHTML(lgu.municipality)}</span>
                    </div>
                    <div class="lgu-info-cell">
                        <span class="lgu-sub-text">${escapeHTML(lgu.mayorName || lgu.headName)}</span>
                    </div>
                    <button class="view-btn" onclick="openDetail('${lgu.id}')">
                        <i data-lucide="eye"></i> VIEW
                    </button>
                </div>
            `).join('');

            countDisplay.innerText = data.length;
            lucide.createIcons();
        }

        // 3. Search Filter
        document.getElementById('lguSearchInput').oninput = (e) => {
            const term = e.target.value.toLowerCase().trim();
            const filtered = masterLguList.filter(l => 
                (l.municipality && l.municipality.toLowerCase().includes(term)) || 
                (l.mayorName && l.mayorName.toLowerCase().includes(term))
            );
            renderTable(filtered);
        };

        // 4. Modal Detail + Bridge Logic
        window.openDetail = function(id) {
            const lgu = masterLguList.find(l => l.id === id);
            
            // Text values (Uses innerText for extra security)
            document.getElementById('detMuni').innerText = lgu.municipality || "---";
            document.getElementById('detMayor').innerText = lgu.mayorName || "---";
            document.getElementById('detProvince').innerText = lgu.province || "---";
            document.getElementById('detContact').innerText = lgu.contact || "---";
            document.getElementById('detRegion').innerText = lgu.region || "---";

            // Bridge A: Fetch Engineers for this Municipality
            const qEng = query(collection(db, "engineers"), where("municipality", "==", lgu.municipality));
            onSnapshot(qEng, (snap) => {
                const container = document.getElementById('associatedEngineers');
                const engineers = snap.docs.map(doc => doc.data());
                container.innerHTML = engineers.length > 0 ? engineers.map(e => `
                    <div class="item-card">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px; height:32px; background:#f0fdf4; color:#16a34a; border-radius:10px; display:flex; align-items:center; justify-content:center;"><i data-lucide="shield-check" style="width:16px;"></i></div>
                            <div><span style="display:block; font-weight:800; color:#1e293b;">${escapeHTML(e.fullName)}</span><span style="font-size:10px; color:#94a3b8; font-weight:700;">${escapeHTML(e.specialization)}</span></div>
                        </div>
                        <span class="badge" style="background:#dcfce7; color:#16a34a;">${escapeHTML(e.licenseNo)}</span>
                    </div>
                `).join('') : '<p style="text-align:center; color:#94a3b8; font-size:11px; padding:10px; font-style:italic;">No engineers assigned to this area.</p>';
                lucide.createIcons();
            });

            // Bridge B: Fetch Projects for this Municipality
            const qProj = query(collection(db, "projects"), where("municipality", "==", lgu.municipality));
            onSnapshot(qProj, (snap) => {
                const container = document.getElementById('associatedProjects');
                const projects = snap.docs.map(doc => doc.data());
                container.innerHTML = projects.length > 0 ? projects.map(p => `
                    <div class="item-card">
                        <div><span style="display:block; font-weight:800; color:#1e293b;">${escapeHTML(p.title)}</span><span style="font-size:10px; color:#94a3b8; font-weight:700;">MAINTENANCE PROJECT</span></div>
                        <span class="badge">${escapeHTML(p.status)}</span>
                    </div>
                `).join('') : '<p style="text-align:center; color:#94a3b8; font-size:11px; padding:10px; font-style:italic;">No project records found.</p>';
            });

            document.getElementById('lguModal').style.display = 'flex';
            lucide.createIcons();
        }

        window.closeModal = () => document.getElementById('lguModal').style.display = 'none';
        document.getElementById('refreshBtn').onclick = () => renderTable(masterLguList);
        window.onclick = (e) => { if (e.target == document.getElementById('lguModal')) closeModal(); }
    </script>
</body>
</html>