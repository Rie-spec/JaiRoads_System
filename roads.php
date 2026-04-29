<?php
    $baseUrl = '../../'; 
    $activePage = 'roads';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAIROADS - Road Inventory</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/layout.css">
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Table Structure */
        .road-grid { 
            display: grid; 
            grid-template-columns: 3fr 1.5fr 1fr 1fr; 
            gap: 20px; 
            align-items: center; 
        }
        .records-head.road-grid { padding: 20px 30px; border-bottom: 1px solid #e2e8f0; }
        .road-row { padding: 14px 30px; border-bottom: 1px solid #f1f5f9; transition: all 0.2s ease; }
        .road-row:hover { background-color: #f8fafc; }

        /* Condition Badges */
        .cond-badge { font-size: 10px; font-weight: 900; padding: 5px 12px; border-radius: 20px; text-transform: uppercase; text-align: center; width: fit-content; }
        .cond-good { background: #dcfce7; color: #16a34a; }
        .cond-fair { background: #fef3c7; color: #d97706; }
        .cond-poor { background: #fee2e2; color: #dc2626; }

        /* Fixed View Button (Aligned Right, Not Stretched) */
        .view-btn {
            height: 38px; width: 110px;
            border: 1px solid #e2e8f0; border-radius: 12px;
            background: white; color: #5d5fef;
            font-weight: 700; font-size: 11px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            cursor: pointer; justify-self: end; transition: all 0.2s ease;
        }
        .view-btn:hover { background: #5d5fef; color: white; border-color: #5d5fef; box-shadow: 0 4px 12px rgba(93, 95, 239, 0.2); }

        /* Filter Styling */
        .filter-select {
            padding: 0 15px; height: 45px; border-radius: 14px; border: 1px solid #e2e8f0;
            font-family: 'Inter'; font-weight: 700; color: #64748b; background: white; outline: none; cursor: pointer;
        }

        /* Modal Styles */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(30, 41, 59, 0.3); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 9999; }
        .modal-card { background: white; width: 100%; max-width: 720px; border-radius: 32px; padding: 35px; box-shadow: 0 25px 50px rgba(0,0,0,0.1); }
        .sect { margin-bottom: 25px; }
        .secthead { display: flex; align-items: center; gap: 8px; color: #94a3b8; font-size: 11px; font-weight: 900; text-transform: uppercase; margin-bottom: 12px; }
        .lbl { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; display: block; }
        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .data-box { padding: 12px 16px; background: #f8fafc; border-radius: 14px; border: 1px solid #f1f5f9; font-weight: 700; color: #1e293b; font-size: 13px; }
    </style>
</head>
<body>

    <div class="page-layout">
        <?php include $baseUrl . 'components/sidebar.php'; ?>

        <main class="main-content">
            <header class="page-heading-row">
                <div class="page-heading-text">
                    <h2 class="page-main-title">Road Inventory</h2>
                    <p class="page-subtitle">Master Infrastructure Registry</p>
                </div>
            </header>

            <section class="filter-shell">
                <div class="filter-search">
                    <i class="filter-search-icon" data-lucide="search"></i>
                    <input type="text" id="roadSearchInput" placeholder="Search by road name or ID...">
                </div>
                <div class="filter-actions">
                    <select id="muniFilter" class="filter-select">
                        <option value="all">All Municipalities</option>
                    </select>
                    <button class="refresh-btn" id="refreshBtn" type="button"><i data-lucide="rotate-cw"></i></button>
                </div>
            </section>

            <section class="records-shell">
                <div class="records-head road-grid">
                    <div>Road Description</div>
                    <div>Municipality</div>
                    <div>Condition</div>
                    <div style="text-align: right; padding-right: 20px;">Action</div>
                </div>

                <div class="records-body" id="roadTableBody">
                    </div>

                <div class="records-footer">
                    <p class="records-note">Total Inventory Items: <span id="roadCount">0</span></p>
                </div>
            </section>
        </main>
    </div>

    <div class="modal-overlay" id="roadModal">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 52px; height: 52px; background: #fff8eb; color: #d39a00; border-radius: 18px; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="map-pin"></i>
                    </div>
                    <div>
                        <h1 style="font-weight: 800; font-size: 24px; color: #1e293b;" id="detRoadName">---</h1>
                        <p style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;" id="detRoadId">ROAD ID: ---</p>
                    </div>
                </div>
                <button onclick="closeModal()" style="background:none; border:none; cursor:pointer; color:#94a3b8;"><i data-lucide="x"></i></button>
            </div>

            <div class="sect">
                <div class="secthead"><i data-lucide="settings"></i><span>Engineering Specs</span></div>
                <div class="grid2" style="margin-bottom: 15px;">
                    <div><label class="lbl">Classification</label><div class="data-box" id="detClass">---</div></div>
                    <div><label class="lbl">Pavement Type</label><div class="data-box" id="detPave">---</div></div>
                </div>
                <div class="grid2">
                    <div><label class="lbl">Total Length (km)</label><div class="data-box" id="detLength">---</div></div>
                    <div><label class="lbl">Location (Municipality)</label><div class="data-box" id="detMuni">---</div></div>
                </div>
            </div>

            <div class="sect">
                <div class="secthead"><i data-lucide="history"></i><span>Maintenance History</span></div>
                <div id="roadProjects" style="display: flex; flex-direction: column; gap: 10px; max-height: 180px; overflow-y: auto;">
                    </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                <button onclick="closeModal()" class="primary-btn" style="width: auto; padding: 12px 35px;">CLOSE INVENTORY</button>
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
        let masterRoadList = [];

        // 1. Populating the Dropdown Filter from LGUs
        onSnapshot(collection(db, "lgus"), (snap) => {
            const select = document.getElementById('muniFilter');
            select.innerHTML = '<option value="all">All Municipalities</option>';
            snap.forEach(doc => {
                const name = doc.data().municipality;
                select.innerHTML += `<option value="${name}">${name}</option>`;
            });
        });

        // 2. Real-time Road List
        onSnapshot(collection(db, "roads"), (snapshot) => {
            masterRoadList = snapshot.docs.map(doc => ({ id: doc.id, ...doc.data() }));
            renderTable(masterRoadList);
        });

        // 3. Render Table (WITH YELLOW BAR FIX)
        window.renderTable = function(data) {
            const body = document.getElementById('roadTableBody');
            const countDisplay = document.getElementById('roadCount');

            if (data.length === 0) {
                body.innerHTML = `
                    <div style="padding: 80px 20px; text-align: center; background: #fff; border-radius: 0 0 24px 24px;">
                        <i data-lucide="search-x" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 16px; display: block; margin-left: auto; margin-right: auto;"></i>
                        <h3 style="color: #1e293b; font-weight: 800; font-size: 18px; margin-bottom: 4px;">No Road Found</h3>
                        <p style="color: #94a3b8; font-size: 14px;">Try searching for a different road name or ID.</p>
                    </div>
                `;
                countDisplay.innerText = "0";
                lucide.createIcons();
                return;
            }

            body.innerHTML = data.map(road => {
                const condClass = `cond-${(road.condition || 'good').toLowerCase()}`;
                return `
                <div class="road-row road-grid">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 40px; height: 40px; background: #fff8eb; color: #d39a00; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i data-lucide="map-pin" style="width: 18px; height: 18px;"></i>
                        </div>
                        <div>
                            <span style="font-weight: 800; color: #1e293b; font-size: 14px; display: block; margin-bottom: 2px;">${road.name}</span>
                            <span style="font-size: 10px; color: #94a3b8; font-weight: 800; text-transform: uppercase;">ID: ${road.roadId}</span>
                        </div>
                    </div>
                    <div style="font-weight: 600; color: #64748b; font-size: 13px;">${road.municipality}</div>
                    <div><span class="cond-badge ${condClass}">${road.condition || 'Good'}</span></div>
                    <button class="view-btn" onclick="openRoadDetail('${road.id}')"><i data-lucide="eye"></i> VIEW</button>
                </div>
            `}).join('');
            countDisplay.innerText = data.length;
            lucide.createIcons();
        }

        // 4. Modal Detail + Bridge to Projects
        window.openRoadDetail = function(id) {
            const road = masterRoadList.find(r => r.id === id);
            document.getElementById('detRoadName').innerText = road.name;
            document.getElementById('detRoadId').innerText = `ROAD ID: ${road.roadId}`;
            document.getElementById('detClass').innerText = road.classification;
            document.getElementById('detPave').innerText = road.pavementType;
            document.getElementById('detLength').innerText = `${road.length} km`;
            document.getElementById('detMuni').innerText = road.municipality;

            // Fetch History Bridge
            const q = query(collection(db, "projects"), where("municipality", "==", road.municipality));
            onSnapshot(q, (snap) => {
                const container = document.getElementById('roadProjects');
                const projs = snap.docs.map(doc => doc.data());
                container.innerHTML = projs.length > 0 ? projs.map(p => `
                    <div style="background:#f8fafc; padding:14px; border-radius:14px; border:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                        <div><span style="display:block; font-weight:800; color:#1e293b; font-size:13px;">${p.title}</span><span style="font-size:9px; color:#94a3b8; font-weight:800; text-transform:uppercase;">Infrastructure Maintenance</span></div>
                        <span style="font-size:9px; font-weight:900; color:#5d5fef; text-transform:uppercase; background:#eeefff; padding:4px 10px; border-radius:10px;">${p.status}</span>
                    </div>
                `).join('') : '<p style="text-align:center; font-size:12px; color:#94a3b8; font-style:italic; padding:20px;">No historical records found for this area.</p>';
            });

            document.getElementById('roadModal').style.display = 'flex';
            lucide.createIcons();
        }

        window.closeModal = () => document.getElementById('roadModal').style.display = 'none';

        // Filters
        const performFilter = () => {
            const search = document.getElementById('roadSearchInput').value.toLowerCase().trim();
            const muni = document.getElementById('muniFilter').value;
            const filtered = masterRoadList.filter(r => {
                const matchesSearch = r.name.toLowerCase().includes(search) || r.roadId.toLowerCase().includes(search);
                const matchesMuni = muni === 'all' || r.municipality === muni;
                return matchesSearch && matchesMuni;
            });
            renderTable(filtered);
        };

        document.getElementById('roadSearchInput').oninput = performFilter;
        document.getElementById('muniFilter').onchange = performFilter;
        document.getElementById('refreshBtn').onclick = () => {
            document.getElementById('roadSearchInput').value = '';
            document.getElementById('muniFilter').value = 'all';
            renderTable(masterRoadList);
        };
    </script>
</body>
</html>