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
        /* ========================================= */
        /* DIRECTORY LAYOUT                          */
        /* ========================================= */
        .lgu-grid {
            display: grid;
            grid-template-columns: 2.5fr 2.5fr 1fr; 
            gap: 16px;
            align-items: center;
        }

        .records-head.lgu-grid {
            padding: 20px 30px;
            border-bottom: 1px solid #e2e8f0;
        }

        .lgu-row {
            padding: 14px 30px;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .lgu-row:hover { background-color: #f8fafc; }

        .lgu-info-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .lgu-icon-wrapper {
            width: 38px; height: 38px;
            background: #eeefff; color: #5d5fef;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }

        .lgu-main-text { font-weight: 700; color: #1e293b; font-size: 14px; }
        .lgu-sub-text { font-weight: 500; color: #64748b; font-size: 14px; }

        /* FIXED VIEW BUTTON (Right Aligned & Not Stretched) */
        .view-btn {
            height: 38px; 
            width: 110px;
            border: 1px solid #e2e8f0; border-radius: 12px;
            background: white; color: #5d5fef;
            font-weight: 700; font-size: 11px;
            display: flex; align-items: center; justify-content: center; 
            gap: 8px;
            cursor: pointer; 
            justify-self: end; /* Pushes button to right of grid cell */
            transition: all 0.2s ease;
        }

        .view-btn:hover {
            background: #5d5fef; color: white;
            box-shadow: 0 8px 20px rgba(93, 95, 239, 0.2);
            border-color: #5d5fef;
        }

        /* ========================================= */
        /* MODAL & DETAIL PANE                       */
        /* ========================================= */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(30, 41, 59, 0.3); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center; z-index: 9999;
        }

        .modal-card {
            background: white; width: 100%; max-width: 680px;
            border-radius: 32px; padding: 35px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2);
        }

        .sect { margin-bottom: 25px; }
        .secthead {
            display: flex; align-items: center; gap: 8px;
            color: #94a3b8; font-size: 11px; font-weight: 900;
            text-transform: uppercase; letter-spacing: 0.1em;
            margin-bottom: 12px;
        }

        .lbl { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; display: block; }
        
        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }

        .data-box {
            padding: 12px 16px; background: #f8fafc; border-radius: 14px;
            border: 1px solid #f1f5f9; font-weight: 700; color: #1e293b; font-size: 14px;
        }

        .project-card {
            background: #f8fafc; padding: 14px 18px; border-radius: 16px;
            border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;
        }
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
                    <input type="text" id="lguSearchInput" placeholder="Search by municipality or head of LGU...">
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

                <div class="records-body" id="lguTableBody">
                    </div>

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
                        <h1 style="font-weight: 800; font-size: 24px; color: #1e293b; letter-spacing: -0.02em;" id="detMuni">---</h1>
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
                <div class="secthead"><i data-lucide="wrench"></i><span>Maintenance Projects Involved</span></div>
                <div id="associatedProjects" style="display: flex; flex-direction: column; gap: 10px; max-height: 180px; overflow-y: auto;">
                    </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                <button type="button" onclick="closeModal()" class="primary-btn" style="width: auto; padding: 12px 35px;">
                    CLOSE PROFILE
                </button>
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

        // 1. Fetch LGUs
        onSnapshot(collection(db, "lgus"), (snapshot) => {
            masterLguList = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
            renderTable(masterLguList);
        });

        // 2. Render Table with "No Record Found" Logic
        window.renderTable = function(data) {
            const body = document.getElementById('lguTableBody');
            const countDisplay = document.getElementById('lguCount');

            if (data.length === 0) {
                body.innerHTML = `
                    <div style="padding: 80px 20px; text-align: center; background: #fff; border-radius: 0 0 24px 24px;">
                        <i data-lucide="search-x" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 16px; display: block; margin-left: auto; margin-right: auto;"></i>
                        <h3 style="color: #1e293b; font-weight: 800; font-size: 18px; margin-bottom: 4px;">No Record Found</h3>
                        <p style="color: #94a3b8; font-size: 14px;">We couldn't find any results matching your search.</p>
                    </div>
                `;
                countDisplay.innerText = "0";
                lucide.createIcons();
                return;
            }

            body.innerHTML = data.map(lgu => `
                <div class="lgu-row lgu-grid">
                    <div class="lgu-info-cell">
                        <div class="lgu-icon-wrapper"><i data-lucide="building-2"></i></div>
                        <span class="lgu-main-text">${lgu.municipality || '---'}</span>
                    </div>
                    <div class="lgu-info-cell">
                        <span class="lgu-sub-text">${lgu.headName || '---'}</span>
                    </div>
                    <button class="view-btn" onclick="openDetail('${lgu.id}')">
                        <i data-lucide="eye"></i> VIEW
                    </button>
                </div>
            `).join('');

            countDisplay.innerText = data.length;
            lucide.createIcons();
        }

        // 3. Search Logic
        document.getElementById('lguSearchInput').oninput = (e) => {
            const term = e.target.value.toLowerCase().trim();
            const filtered = masterLguList.filter(l => 
                (l.municipality && l.municipality.toLowerCase().includes(term)) || 
                (l.headName && l.headName.toLowerCase().includes(term))
            );
            renderTable(filtered);
        };

        // 4. Detail Pane & Project Bridge
        window.openDetail = function(id) {
            const lgu = masterLguList.find(l => l.id === id);
            
            document.getElementById('detMuni').innerText = lgu.municipality;
            document.getElementById('detMayor').innerText = lgu.headName;
            document.getElementById('detContact').innerText = lgu.contact;
            document.getElementById('detProvince').innerText = lgu.province;
            document.getElementById('detRegion').innerText = lgu.region;

            // Fetch linked projects automatically
            const q = query(collection(db, "projects"), where("municipality", "==", lgu.municipality));
            onSnapshot(q, (snapshot) => {
                const container = document.getElementById('associatedProjects');
                const projects = snapshot.docs.map(doc => doc.data());

                if (projects.length > 0) {
                    container.innerHTML = projects.map(p => `
                        <div class="project-card">
                            <div>
                                <span style="display:block; font-weight:800; color:#1e293b;">${p.title}</span>
                                <span style="font-size:10px; color:#94a3b8; font-weight:700; text-transform:uppercase;">Infrastructure Maintenance</span>
                            </div>
                            <span style="font-size:9px; font-weight:900; background:#eeefff; color:#5d5fef; padding:5px 10px; border-radius:10px; text-transform:uppercase;">${p.status}</span>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = `<p style="text-align:center; color:#94a3b8; font-size:12px; font-style:italic; padding:20px; background:#f8fafc; border-radius:14px;">No projects recorded for this area.</p>`;
                }
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
