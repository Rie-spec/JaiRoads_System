<?php
    $baseUrl = '../../';
    $activePage = 'maintenance-projects';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAIROADS - Maintenance Projects</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/layout.css">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* ============================= */
        /* MAINTENANCE TABLE */
        /* ============================= */
        :root {
            --maintenance-columns: 2fr 1.5fr 1fr 1.5fr 2fr 1fr;
        }

        .records-head,
        .record-row {
            grid-template-columns: var(--maintenance-columns);
        }

        .records-head > div,
        .record-row > div {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .records-head > div:first-child,
        .record-row > div:first-child {
            justify-content: flex-start;
        }

        .record-row {
            display: grid;
            gap: 16px;
            align-items: center;
            padding: 18px 20px;
            background: #ffffff;
            border: 1px solid #f3f4f6;
            border-radius: 14px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            font-size: 13px;
            color: #4b5563;
            cursor: pointer;
            transition: 0.18s ease;
        }

        .record-row:hover {
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
            border-color: #e5e7eb;
            transform: translateY(-1px);
        }

        .project-title-cell {
            display: block !important;
            text-align: left;
        }

        .proj-name-main {
            display: block;
            font-weight: 800;
            color: #111827;
            margin-bottom: 4px;
        }

        .proj-id-sub {
            display: block;
            font-size: 11px;
            color: #9ca3af;
            text-transform: uppercase;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            display: inline-block;
        }

        .status-ongoing { background: #e0e7ff; color: #4f46e5; }
        .status-completed { background: #dcfce7; color: #16a34a; }
        .status-terminated { background: #fee2e2; color: #dc2626; }

        .action-btn {
            background: #eef2ff;
            color: #5d5fef;
            border: none;
            padding: 8px;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn:hover {
            background: #5d5fef;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <script>
        window.JAIROADS_BASE_URL = '<?php echo $baseUrl; ?>';
    </script>

    <div class="page-layout">

        <!-- SIDEBAR START -->
        <?php include '../../components/sidebar.php'; ?>
        <!-- SIDEBAR END -->

        <!-- MAIN CONTENT START -->
        <main class="main-content">
            <section class="page-heading-row">
                <div class="page-heading-text">
                    <h2 class="page-main-title">Maintenance Projects</h2>
                    <p class="page-subtitle">Davao De Oro Region</p>
                </div>

                <button id="openModalBtn" class="primary-btn" type="button">
                    <i data-lucide="plus"></i>
                    <span>Add Maintenance Project</span>
                </button>
            </section>

            <section class="filter-shell">
                <div class="filter-search">
                    <i class="filter-search-icon" data-lucide="search"></i>
                    <input type="text" id="tableSearch" placeholder="Search by project title...">
                </div>

                <div class="filter-actions">
                    <select id="filterMunicipality" class="filter-btn">
                        <option value="All">All Municipalities</option>
                        <option value="Compostela">Compostela</option>
                        <option value="Laak">Laak</option>
                        <option value="Mabini">Mabini</option>
                        <option value="Maco">Maco</option>
                        <option value="Maragusan">Maragusan</option>
                        <option value="Mawab">Mawab</option>
                        <option value="Monkayo">Monkayo</option>
                        <option value="Montevista">Montevista</option>
                        <option value="Nabunturan">Nabunturan</option>
                        <option value="New Bataan">New Bataan</option>
                        <option value="Pantukan">Pantukan</option>
                    </select>

                    <select id="filterStatus" class="filter-btn">
                        <option value="All">All Statuses</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Completed">Completed</option>
                        <option value="Terminated">Terminated</option>
                    </select>

                    <select id="filterEngineer" class="filter-btn">
                        <option value="All">All Assigned Engineers</option>
                    </select>

                    <button id="refreshBtn" class="refresh-btn" type="button" aria-label="Refresh table">
                        <i data-lucide="rotate-cw"></i>
                    </button>
                </div>
            </section>

            <section class="records-shell">
                <div class="records-head" style="--records-columns: var(--maintenance-columns);">
                    <div class="head-col">Project Title</div>
                    <div class="head-col">Municipality</div>
                    <div class="head-col">Status</div>
                    <div class="head-col">Engineer</div>
                    <div class="head-col">Timeline</div>
                    <div class="head-col actions-col">Actions</div>
                </div>

                <div id="recordsBody" class="records-body">
                    <div class="records-placeholder">Place your records here. No projects found.</div>
                </div>

                <div class="records-footer">
                    <p id="recordsNote" class="records-note">Showing 0 of 0 projects</p>
                    <div id="paginationControls" class="pagination"></div>
                </div>
            </section>
        </main>
        <!-- MAIN CONTENT END -->
    </div>

    <!-- REUSABLE COMPONENTS START -->
    <?php include '../../components/forms/maintenance-project-form.php'; ?>
    <?php include '../../components/modals/maintenance-project-details.php'; ?>
    <!-- REUSABLE COMPONENTS END -->

    <script src="<?php echo $baseUrl; ?>assets/js/sidebar-global-search.js"></script>
    <script>
        lucide.createIcons();

        const STORAGE_KEY = JAIROADS_PROJECT_STORAGE;
        const itemsPerPage = 5;

        let allProjects = getStoredProjects();
        let filteredProjects = [];
        let currentPage = 1;
        let selectedRoads = [];
        let editingProjectId = null;

        const tableBody = document.getElementById('recordsBody');
        const recordsNote = document.getElementById('recordsNote');
        const paginationControls = document.getElementById('paginationControls');
        const filterStatus = document.getElementById('filterStatus');
        const filterMunicipality = document.getElementById('filterMunicipality');
        const filterEngineer = document.getElementById('filterEngineer');
        const tableSearch = document.getElementById('tableSearch');

        function fillEngineerDropdown() {
            sampleEngineers.forEach(name => {
                const option = document.createElement('option');
                option.value = name;
                option.textContent = name;
                filterEngineer.appendChild(option);
            });
        }

        function saveProjects() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(allProjects));
        }

        function formatDateRange(start, end) {
            if (!start || !end) return 'TBD';
            const d1 = new Date(start);
            const d2 = new Date(end);
            return `${d1.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${d2.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
        }

        function getStatusBadge(status) {
            const st = (status || '').toLowerCase();
            if (st === 'completed') return 'status-completed';
            if (st === 'terminated') return 'status-terminated';
            return 'status-ongoing';
        }

        function applyFilters() {
            const statusVal = filterStatus.value;
            const muniVal = filterMunicipality.value;
            const engineerVal = filterEngineer.value;
            const searchVal = tableSearch.value.toLowerCase().trim();

            filteredProjects = allProjects.filter(project => {
                return (statusVal === 'All' || project.status === statusVal) &&
                       (muniVal === 'All' || project.municipality === muniVal) &&
                       (engineerVal === 'All' || project.engineer === engineerVal) &&
                       ((project.title || '').toLowerCase().includes(searchVal));
            });

            currentPage = 1;
            renderTable();
        }

        function renderTable() {
            tableBody.innerHTML = '';

            if (filteredProjects.length === 0) {
                tableBody.innerHTML = '<div class="records-placeholder">Place your records here. No projects found.</div>';
                recordsNote.textContent = 'SHOWING 0 OF 0 PROJECTS';
                paginationControls.innerHTML = '';
                return;
            }

            const startIndex = (currentPage - 1) * itemsPerPage;
            const paginatedItems = filteredProjects.slice(startIndex, startIndex + itemsPerPage);

            paginatedItems.forEach(project => {
                const row = document.createElement('div');
                row.className = 'record-row';
                row.innerHTML = `
                    <div class="project-title-cell">
                        <span class="proj-name-main">${project.title}</span>
                        <span class="proj-id-sub">ID: ${project.id}</span>
                    </div>
                    <div class="col">${project.municipality}</div>
                    <div class="col"><span class="status-badge ${getStatusBadge(project.status)}">${project.status}</span></div>
                    <div class="col">${project.engineer || 'Unassigned'}</div>
                    <div class="col">${formatDateRange(project.startDate, project.endDate)}</div>
                    <div class="col actions-col">
                        <button class="action-btn" type="button" title="View Details"><i data-lucide="eye" style="width:18px;height:18px;"></i></button>
                    </div>
                `;

                row.querySelector('.action-btn').addEventListener('click', event => {
                    event.stopPropagation();
                    openDetails(project.id);
                });

                row.addEventListener('click', () => openDetails(project.id));
                tableBody.appendChild(row);
            });

            const actualEnd = Math.min(startIndex + itemsPerPage, filteredProjects.length);
            recordsNote.textContent = `SHOWING ${startIndex + 1} - ${actualEnd} OF ${filteredProjects.length} PROJECTS`;
            renderPagination();
            lucide.createIcons();
        }

        function renderPagination() {
            paginationControls.innerHTML = '';
            const totalPages = Math.ceil(filteredProjects.length / itemsPerPage);

            if (totalPages <= 1) {
                paginationControls.innerHTML = '<button class="page-btn active" type="button">1</button>';
                return;
            }

            const prevBtn = document.createElement('button');
            prevBtn.className = 'page-btn';
            prevBtn.type = 'button';
            prevBtn.innerHTML = '<i data-lucide="chevron-left"></i>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => { currentPage--; renderTable(); };
            paginationControls.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
                btn.type = 'button';
                btn.textContent = i;
                btn.onclick = () => { currentPage = i; renderTable(); };
                paginationControls.appendChild(btn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.className = 'page-btn';
            nextBtn.type = 'button';
            nextBtn.innerHTML = '<i data-lucide="chevron-right"></i>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => { currentPage++; renderTable(); };
            paginationControls.appendChild(nextBtn);
            lucide.createIcons();
        }

        function openForm() {
            editingProjectId = null;
            resetForm();
            updateFormMode(false);
            document.getElementById('projectModalOverlay').classList.add('active');
        }

        function updateFormMode(isEdit) {
            const modalTitle = document.querySelector('#projectModalOverlay .modal-title');
            const saveBtn = document.getElementById('saveProjectBtn');
            if (modalTitle) modalTitle.textContent = isEdit ? 'Edit Maintenance Project' : 'Add Maintenance Project';
            if (saveBtn) saveBtn.innerHTML = isEdit ? '<i data-lucide="save"></i><span>Update Project Record</span>' : '<i data-lucide="circle-check"></i><span>Save Project Entry</span>';
            lucide.createIcons();
        }

        function openEditProject(projectId) {
            const project = allProjects.find(item => item.id === projectId);
            if (!project) return;

            editingProjectId = project.id;
            selectedRoads = [...(project.roads || [])];

            document.getElementById('formTitle').value = project.title || '';
            document.getElementById('formMunicipality').value = project.municipality || '';
            document.getElementById('formStart').value = project.startDate || '';
            document.getElementById('formEnd').value = project.endDate || '';
            document.getElementById('formStatus').value = project.status || 'Ongoing';
            document.getElementById('formEngineer').value = project.engineer || '';
            renderSelectedRoads();
            updateFormMode(true);
            closeDetails();
            document.getElementById('projectModalOverlay').classList.add('active');
        }

        function closeForm() {
            document.getElementById('projectModalOverlay').classList.remove('active');
            editingProjectId = null;
            resetForm();
            updateFormMode(false);
        }

        function resetForm() {
            ['formTitle', 'formMunicipality', 'formStart', 'formEnd', 'formEngineer', 'roadSearchInput'].forEach(id => {
                const element = document.getElementById(id);
                if (element) element.value = '';
            });

            document.getElementById('formStatus').value = 'Ongoing';
            selectedRoads = [];
            renderSelectedRoads();
        }

        function saveProjectFromForm() {
            const title = document.getElementById('formTitle').value.trim();
            const municipality = document.getElementById('formMunicipality').value;

            if (!title || !municipality) {
                alert('Please fill in Title and Municipality.');
                return;
            }

            const projectPayload = {
                title,
                municipality,
                category: 'Maintenance Projects',
                startDate: document.getElementById('formStart').value,
                endDate: document.getElementById('formEnd').value,
                status: document.getElementById('formStatus').value,
                engineer: document.getElementById('formEngineer').value.trim(),
                roads: selectedRoads
            };

            if (editingProjectId) {
                allProjects = allProjects.map(project => project.id === editingProjectId
                    ? { ...project, ...projectPayload, updatedAt: Date.now() }
                    : project
                );
            } else {
                allProjects.unshift({
                    id: `PRJ-${String(Date.now()).slice(-6)}`,
                    ...projectPayload,
                    createdBy: 'Andrei Jacob',
                    createdAt: Date.now()
                });
            }

            saveProjects();
            closeForm();
            applyFilters();
        }

        function bindRoadSelectionEvents() {
            const roadSearchInput = document.getElementById('roadSearchInput');
            const roadResults = document.getElementById('roadResults');

            roadSearchInput.addEventListener('input', () => {
                const keyword = roadSearchInput.value.toLowerCase().trim();

                if (!keyword) {
                    roadResults.style.display = 'none';
                    roadResults.innerHTML = '';
                    return;
                }

                const results = sampleRoads.filter(road =>
                    road.name.toLowerCase().includes(keyword) || road.municipality.toLowerCase().includes(keyword)
                );

                roadResults.innerHTML = results.slice(0, 5).map(road => `
                    <button type="button" class="road-result" data-id="${road.id}">
                        <strong>${road.name}</strong>
                        <span>${road.municipality} · ${road.section}</span>
                    </button>
                `).join('') || '<div class="road-result"><strong>No roads found</strong><span>Admin must add road records first.</span></div>';

                roadResults.style.display = 'block';
                roadResults.querySelectorAll('[data-id]').forEach(btn => btn.addEventListener('click', () => selectRoad(btn.dataset.id)));
            });
        }

        function selectRoad(roadId) {
            const road = sampleRoads.find(item => item.id === roadId);
            if (!road || selectedRoads.some(item => item.id === roadId)) return;

            selectedRoads.push(road);
            document.getElementById('roadSearchInput').value = '';
            document.getElementById('roadResults').style.display = 'none';
            renderSelectedRoads();
        }

        function removeRoad(roadId) {
            selectedRoads = selectedRoads.filter(road => road.id !== roadId);
            renderSelectedRoads();
        }

        function renderSelectedRoads() {
            const selectedAssets = document.getElementById('selectedAssets');
            const selectedTitle = document.getElementById('selectedAssetsTitle');
            if (!selectedAssets || !selectedTitle) return;

            selectedTitle.textContent = `Selected Assets (${selectedRoads.length})`;
            selectedAssets.innerHTML = selectedRoads.map(road => `
                <div class="item">
                    <div class="iteminfo">
                        <div class="itembadge"><i data-lucide="route"></i></div>
                        <div><div class="itemname">${road.name}</div><p class="itemdesc">Section: ${road.section}</p></div>
                    </div>
                    <button class="del" type="button" data-remove-road="${road.id}"><i data-lucide="trash-2"></i></button>
                </div>
            `).join('') || '<p class="itemdesc">No roads selected yet.</p>';

            selectedAssets.querySelectorAll('[data-remove-road]').forEach(btn => {
                btn.addEventListener('click', () => removeRoad(btn.dataset.removeRoad));
            });

            lucide.createIcons();
        }

        function readableDate(value) {
            if (!value) return 'TBD';
            return new Date(value).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
        }

        function openDetails(projectId) {
            const project = allProjects.find(item => item.id === projectId);
            if (!project) return;

            const monthlyUpdates = project.monthlyUpdates || [
                { month: 'May 2026', title: 'Asphalt Overlay Started', description: 'Initial overlay work has begun on the cleared road section.', progress: '56%' },
                { month: 'April 2026', title: 'Base Preparation Completed', description: 'Clearing and grubbing of the selected stretch is fully finished.', progress: '38%' }
            ];
            const documents = project.documents || [
                { name: 'Approved_Budget.pdf', meta: '2.4 MB · May 5, 2026' },
                { name: 'Environmental_Clearance.docx', meta: '1.1 MB · May 8, 2026' }
            ];

            document.getElementById('detailsTitle').textContent = project.title;
            document.getElementById('detailsStatus').textContent = project.status || 'Ongoing';
            document.getElementById('detailsCreatedBy').textContent = project.createdBy || 'Andrei Jacob';
            document.getElementById('detailsDate').textContent = readableDate(project.createdAt || project.startDate);
            document.getElementById('detailsMunicipality').textContent = project.municipality || 'TBD';
            document.getElementById('detailsEngineer').textContent = project.engineer || 'Unassigned';
            document.getElementById('detailsStart').textContent = readableDate(project.startDate);
            document.getElementById('detailsEnd').textContent = readableDate(project.endDate);

            document.getElementById('detailsRoads').innerHTML = (project.roads || []).map(road => `
                <button type="button" class="linked-item" onclick="alert('Road record: ${road.name.replace(/'/g, "\'")}')">
                    <div class="linked-title">${road.name}</div>
                    <p class="linked-desc">Section: ${road.section || 'Full length'}</p>
                </button>
            `).join('') || '<div class="linked-item"><div class="linked-title">No affected roads selected</div><p class="linked-desc">Road records will appear here.</p></div>';

            document.getElementById('detailsMonthlyUpdates').innerHTML = monthlyUpdates.map(update => `
                <div class="timeline-item" onclick="alert('Monthly update: ${update.title.replace(/'/g, "\'")}')">
                    <div class="timeline-dot"></div>
                    <div>
                        <div class="timeline-date">${update.month} · Progress: ${update.progress || 'TBD'}</div>
                        <div class="timeline-title">${update.title}</div>
                        <p class="timeline-desc">${update.description}</p>
                    </div>
                </div>
            `).join('');

            document.getElementById('detailsDocuments').innerHTML = documents.map(doc => `
                <div class="doc-item" onclick="alert('Document selected: ${doc.name.replace(/'/g, "\'")}')">
                    <div class="doc-icon"><i data-lucide="file-text"></i></div>
                    <div><div class="doc-name">${doc.name}</div><div class="doc-meta">${doc.meta}</div></div>
                </div>
            `).join('');

            const editBtn = document.getElementById('editProjectRecordBtn');
            editBtn.onclick = () => openEditProject(project.id);

            document.getElementById('projectDetailsOverlay').classList.add('active');
            lucide.createIcons();
        }

        function closeDetails() {
            document.getElementById('projectDetailsOverlay').classList.remove('active');
        }

        function bindEvents() {
            document.getElementById('openModalBtn').addEventListener('click', openForm);
            document.getElementById('closeModalBtn').addEventListener('click', closeForm);
            document.getElementById('cancelModalBtn').addEventListener('click', closeForm);
            document.getElementById('saveProjectBtn').addEventListener('click', saveProjectFromForm);
            document.getElementById('closeDetailsBtn').addEventListener('click', closeDetails);
            document.getElementById('detailsCloseFooterBtn').addEventListener('click', closeDetails);
            document.getElementById('detailsMapPreview').addEventListener('click', () => alert('Map preview will open here once Leaflet/map data integration is connected.'));

            document.getElementById('refreshBtn').addEventListener('click', () => {
                tableSearch.value = '';
                filterStatus.value = 'All';
                filterMunicipality.value = 'All';
                filterEngineer.value = 'All';
                applyFilters();
            });

            tableSearch.addEventListener('input', applyFilters);
            filterStatus.addEventListener('change', applyFilters);
            filterMunicipality.addEventListener('change', applyFilters);
            filterEngineer.addEventListener('change', applyFilters);
        }

        fillEngineerDropdown();
        bindEvents();
        bindRoadSelectionEvents();
        saveProjects();
        applyFilters();
    </script>
</body>
</html>
