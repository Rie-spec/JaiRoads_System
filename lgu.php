<?php
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
        /* Table Layout */
        .lgu-grid { display: grid; grid-template-columns: 2.5fr 2.5fr 1fr; gap: 16px; align-items: center; }
        .records-head.lgu-grid { padding: 20px 30px; border-bottom: 1px solid #e2e8f0; }
        .lgu-row { padding: 14px 30px; border-bottom: 1px solid #f1f5f9; transition: all 0.2s; }
        .lgu-row:hover { background-color: #f8fafc; }
        .lgu-info-cell { display: flex; align-items: center; gap: 12px; }
        .lgu-icon-wrapper { width: 38px; height: 38px; background: #eeefff; color: #5d5fef; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .lgu-main-text { font-weight: 700; color: #1e293b; font-size: 14px; }
        .lgu-sub-text { font-weight: 500; color: #64748b; font-size: 14px; }

        .view-btn {
            height: 38px; padding: 0 18px;
            border: 1px solid #e2e8f0; border-radius: 12px;
            background: white; color: #5d5fef;
            font-weight: 700; font-size: 11px;
            display: flex; align-items: center; gap: 8px;
            cursor: pointer; justify-self: end; transition: all 0.2s;
        }

        /* Improved Modal Overlay (Lightened to keep background buttons visible) */
        .modal-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(30, 41, 59, 0.3); /* Lighter navy */
            backdrop-filter: blur(3px); /* Slightly less blur */
            display: none; align-items: center; justify-content: center; z-index: 9999; 
        }

        .modal-card { 
            background: white; width: 100%; max-width: 680px; border-radius: 32px; 
            padding: 35px; box-shadow: 0 25px 50px rgba(15, 23, 42, 0.2); 
        }
        
        /* Repository Form Styles */
        .sect { margin-bottom: 25px; }
        .secthead { display: flex; align-items: center; gap: 8px; color: #94a3b8; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 12px; }
        .lbl { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; display: block; letter-spacing: 0.05em;}
        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        
        /* Data Display Boxes */
        .data-box { 
            padding: 12px 16px; background: #f8fafc; border-radius: 14px; border: 1px solid #f1f5f9; 
            font-weight: 700; color: #1e293b; font-size: 14px;
        }

        .project-item { 
            background: #f8fafc; padding: 14px 18px; border-radius: 16px; border: 1px solid #f1f5f9; 
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; 
        }

        .status-pill { 
            font-size: 9px; font-weight: 900; padding: 5px 12px; border-radius: 10px; 
            text-transform: uppercase; background: #eeefff; color: #5d5fef; 
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
                    <p class="page-subtitle">Regional Infrastructure Hub</p>
                </div>
            </header>

            <section class="filter-shell">
                <div class="filter-search">
                    <i class="filter-search-icon" data-lucide="search"></i>
                    <input type="text" id="lguSearchInput" placeholder="Search municipality or mayor...">
                </div>
                <div class="filter-actions">
                    <button class="refresh-btn" id="refreshBtn" type="button"><i data-lucide="rotate-cw"></i></button>
                </div>
            </section>

            <section class="records-shell">
                <div class="records-head lgu-grid">
                    <div class="head-col">Municipality</div>
                    <div class="head-col">Head of LGU</div>
                    <div class="head-col" style="text-align: right;">Action</div>
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
                        <h1 style="font-weight: 800; font-size: 24px; color: #1e293b; letter-spacing: -0.02em;" id="detMuni">---</h1>
                        <p style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">LGU Directory Profile</p>
                    </div>
                </div>
                <button onclick="closeModal()" style="background:none; border:none; cursor:pointer; color:#94a3b8;"><i data-lucide="x"></i></button>
            </div>

            <div class="sect">
                <div class="secthead"><i data-lucide="info"></i><span>GOVERNMENT DIRECTORY</span></div>
                <div class="grid2" style="margin-bottom: 15px;">
                    <div>
                        <label class="lbl">Incumbent Mayor</label>
                        <div class="data-box" id="detMayor">---</div>
                    </div>
                    <div>
                        <label class="lbl">Municipality Class</label>
                        <div class="data-box" id="detClass" style="color: #5d5fef;">---</div>
                    </div>
                </div>
                <div class="grid2">
                    <div>
                        <label class="lbl">Office Contact No.</label>
                        <div class="data-box" id="detContact">---</div>
                    </div>
                    <div>
                        <label class="lbl">Administrative Region</label>
                        <div class="data-box">Davao Region (XI)</div>
                    </div>
                </div>
            </div>

            <div class="sect">
                <div class="secthead"><i data-lucide="wrench"></i><span>ROAD MAINTENANCE PROJECTS</span></div>
                <div id="associatedProjects" style="max-height: 220px; overflow-y: auto;">
                    </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 30px; border-top: 1px solid #f1f5f9; padding-top: 25px;">
                <button type="button" onclick="closeModal()" class="primary-btn" style="padding: 14px 28px; border-radius: 16px;">
                    CLOSE DIRECTORY PROFILE
                </button>
            </div>
        </div>
    </div>

    <script>
        const lguList = [
            { name: "MONKAYO", mayor: "Hon. Manuel Zamora", class: "1st Class", contact: "084-372-0262" },
            { name: "NABUNTURAN", mayor: "Hon. Myra Colina-Uy", class: "1st Class", contact: "084-376-1004" },
            { name: "COMPOSTELA", mayor: "Hon. Levi Ebdao", class: "1st Class", contact: "084-372-0051" },
            { name: "MAWAB", mayor: "Hon. Ruperto Gonzaga III", class: "3rd Class", contact: "084-373-0125" }
        ];

        const projectData = [
            { title: "Brgy. Casoon Road Repaving", lgu: "MONKAYO", status: "Ongoing" },
            { title: "Public Market Drainage Clearing", lgu: "MONKAYO", status: "Completed" },
            { title: "Main Highway Streetlight Installation", lgu: "NABUNTURAN", status: "Ongoing" },
            { title: "Compostela Bridge Maintenance", lgu: "COMPOSTELA", status: "Terminated" },
            { title: "Mawab Central School Path", lgu: "MAWAB", status: "Completed" }
        ];

        function renderTable(data = lguList) {
            const body = document.getElementById('lguTableBody');
            if (data.length === 0) {
                body.innerHTML = `<div style="padding: 80px; text-align: center; color: #94a3b8; font-weight: 700;">No Records Found</div>`;
                document.getElementById('lguCount').innerText = "0";
                return;
            }
            body.innerHTML = data.map(lgu => `
                <div class="lgu-row lgu-grid">
                    <div class="lgu-info-cell">
                        <div class="lgu-icon-wrapper"><i data-lucide="building-2"></i></div>
                        <span class="lgu-main-text">${lgu.name}</span>
                    </div>
                    <div class="lgu-info-cell">
                        <div class="lgu-icon-wrapper" style="background:transparent;"><i data-lucide="user"></i></div>
                        <span class="lgu-sub-text">${lgu.mayor}</span>
                    </div>
                    <button class="view-btn" onclick="openDetail('${lgu.name}')">
                        <i data-lucide="eye"></i> VIEW
                    </button>
                </div>
            `).join('');
            document.getElementById('lguCount').innerText = data.length;
            lucide.createIcons();
        }

        function openDetail(muniName) {
            const lgu = lguList.find(l => l.name === muniName);
            
            document.getElementById('detMuni').innerText = lgu.name;
            document.getElementById('detMayor').innerText = lgu.mayor;
            document.getElementById('detClass').innerText = lgu.class;
            document.getElementById('detContact').innerText = lgu.contact;

            const filteredProjects = projectData.filter(p => p.lgu === muniName);
            const container = document.getElementById('associatedProjects');

            if (filteredProjects.length > 0) {
                container.innerHTML = filteredProjects.map(p => `
                    <div class="project-item">
                        <div>
                            <span style="display: block; font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 2px;">${p.title}</span>
                            <span style="font-size: 10px; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Infrastructure Maintenance</span>
                        </div>
                        <span class="status-pill">${p.status}</span>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `<p style="text-align: center; color: #94a3b8; font-size: 12px; font-style: italic; padding: 30px;">No projects currently recorded for this area.</p>`;
            }

            document.getElementById('lguModal').style.display = 'flex';
            lucide.createIcons();
        }

        function closeModal() { document.getElementById('lguModal').style.display = 'none'; }

        document.getElementById('lguSearchInput').addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            const filtered = lguList.filter(l => l.name.toLowerCase().includes(term) || l.mayor.toLowerCase().includes(term));
            renderTable(filtered);
        });

        document.getElementById('refreshBtn').onclick = () => {
            document.getElementById('lguSearchInput').value = '';
            renderTable();
        };

        window.onclick = (e) => { if (e.target == document.getElementById('lguModal')) closeModal(); }

        renderTable();
    </script>
</body>
</html>