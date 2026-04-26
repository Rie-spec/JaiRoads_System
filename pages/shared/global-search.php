<?php
    $baseUrl = '../../';
    $activePage = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAIROADS - Global Search</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>assets/css/layout.css">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* ============================= */
        /* GLOBAL SEARCH PAGE */
        /* ============================= */
        .global-page-shell {
            padding: 8px 16px 28px;
        }

        .global-filter-shell {
            margin: 0 0 22px;
            padding: 18px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 32px;
            box-shadow: 0 1px 3px rgba(15,23,42,.08);
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .global-main-search {
            position: relative;
            flex: 1;
            min-width: 260px;
        }

        .global-main-search svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: #cbd5e1;
        }

        .global-main-search input {
            width: 100%;
            height: 42px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #f8fafc;
            padding: 0 14px 0 42px;
            color: #334155;
            outline: none;
            font-weight: 600;
        }

        .global-dynamic-filters {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .month-input-wrap {
            position: relative;
        }

        .month-display {
            height: 40px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #ffffff;
            color: #475569;
            padding: 0 14px;
            cursor: pointer;
            font-weight: 700;
            min-width: 145px;
        }

        .month-picker-panel {
            position: fixed;
            z-index: 6000;
            width: 290px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 22px;
            box-shadow: 0 24px 60px rgba(15,23,42,.2);
            padding: 16px;
            display: none;
        }

        .month-picker-panel.active {
            display: block;
        }

        .month-picker-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .month-year-btn {
            border: none;
            background: #f8fafc;
            border-radius: 12px;
            width: 34px;
            height: 34px;
            cursor: pointer;
            font-weight: 900;
            color: #64748b;
        }

        .month-year-label {
            font-weight: 900;
            color: #1e293b;
        }

        .month-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .month-cell {
            height: 38px;
            border: none;
            border-radius: 12px;
            background: #f8fafc;
            color: #64748b;
            font-weight: 800;
            cursor: pointer;
        }

        .month-cell:hover,
        .month-cell.active {
            background: #5d5fef;
            color: #ffffff;
        }

        .applied-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0 0 20px;
        }

        .filter-chip {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .result-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 32px;
            box-shadow: 0 1px 3px rgba(15,23,42,.08);
            margin-bottom: 22px;
            overflow: hidden;
        }

        .section-head {
            padding: 22px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 15px;
            background: #eef2ff;
            color: #5d5fef;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .section-title {
            font-size: 18px;
            font-weight: 900;
            color: #1e293b;
        }

        .section-count {
            font-size: 11px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        .section-body {
            padding: 20px 24px 24px;
        }

        .result-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .result-card {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 22px;
            padding: 16px;
            cursor: pointer;
            transition: .18s ease;
        }

        .result-card:hover {
            background: #ffffff;
            border-color: #c7d2fe;
            box-shadow: 0 12px 24px rgba(93,95,239,.08);
            transform: translateY(-1px);
        }

        .result-title {
            font-size: 15px;
            font-weight: 900;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .result-meta {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            line-height: 1.6;
        }

        .result-action {
            margin-top: 12px;
            color: #5d5fef;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .empty-section {
            padding: 20px;
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            border-radius: 20px;
            text-align: center;
            color: #64748b;
            font-weight: 700;
        }

        @media (max-width: 850px) {
            .result-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <script>
        window.JAIROADS_BASE_URL = '<?php echo $baseUrl; ?>';
    </script>

    <div class="page-layout">
        <?php include '../../components/sidebar.php'; ?>

        <main class="main-content">
            <section class="page-heading-row">
                <div class="page-heading-text">
                    <h2 class="page-main-title">Global Search</h2>
                    <p id="globalSearchSubtitle" class="page-subtitle">System-wide record lookup</p>
                </div>
            </section>

            <div class="global-page-shell">
                <section class="global-filter-shell">
                    <div class="global-main-search">
                        <i data-lucide="search"></i>
                        <input id="mainGlobalSearchInput" type="text" placeholder="Search all records...">
                    </div>

                    <select id="recordTypeFilter" class="filter-btn">
                        <option value="All">All Categories</option>
                        <option value="Maintenance Projects">Maintenance Projects</option>
                        <option value="Monthly Updates">Monthly Updates</option>
                        <option value="Project Documents">Project Documents</option>
                        <option value="Roads">Roads</option>
                        <option value="Engineers">Engineers</option>
                        <option value="LGUs">LGUs</option>
                    </select>

                    <div id="dynamicFilters" class="global-dynamic-filters"></div>

                    <select id="sortFilter" class="filter-btn">
                        <option value="relevance">Relevance</option>
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="az">A-Z</option>
                        <option value="za">Z-A</option>
                    </select>

                    <button id="refreshGlobalBtn" class="refresh-btn" type="button"><i data-lucide="rotate-cw"></i></button>
                </section>

                <div id="appliedChips" class="applied-chips"></div>
                <div id="globalResults"></div>
            </div>
        </main>
    </div>

    <div id="monthPickerPanel" class="month-picker-panel">
        <div class="month-picker-head">
            <button id="monthPrevYear" class="month-year-btn" type="button">‹</button>
            <span id="monthYearLabel" class="month-year-label">2026</span>
            <button id="monthNextYear" class="month-year-btn" type="button">›</button>
        </div>
        <div id="monthGrid" class="month-grid"></div>
    </div>

    <script src="<?php echo $baseUrl; ?>assets/js/sidebar-global-search.js"></script>
    <script>
        lucide.createIcons();

        let searchParams = new URLSearchParams(window.location.search);
        let activeMonthTarget = null;
        let activeYear = 2026;
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const mainInput = document.getElementById('mainGlobalSearchInput');
        const recordTypeFilter = document.getElementById('recordTypeFilter');
        const dynamicFilters = document.getElementById('dynamicFilters');
        const sortFilter = document.getElementById('sortFilter');
        const globalResults = document.getElementById('globalResults');
        const appliedChips = document.getElementById('appliedChips');
        const monthPickerPanel = document.getElementById('monthPickerPanel');

        function initializeFromQuery() {
            mainInput.value = searchParams.get('q') || '';
            recordTypeFilter.value = searchParams.get('category') || 'All';
            sortFilter.value = searchParams.get('sort') || 'relevance';
            renderDynamicFilters();

            const municipalityFromSidebar = (searchParams.get('municipalities') || '').split(',').filter(Boolean)[0] || 'All';
            const statusFromSidebar = searchParams.get('status') || 'All';
            const fileTypeFromSidebar = searchParams.get('fileType') || 'All';
            const municipalityFilter = document.getElementById('municipalityFilter');
            const statusFilter = document.getElementById('statusFilter');
            const fileTypeFilter = document.getElementById('fileTypeFilter');

            if (municipalityFilter) municipalityFilter.value = municipalityFromSidebar;
            if (statusFilter) statusFilter.value = statusFromSidebar;
            if (fileTypeFilter) fileTypeFilter.value = fileTypeFromSidebar;

            renderResults();
        }

        function renderDynamicFilters() {
            const type = recordTypeFilter.value;
            let html = '';

            if (['All', 'Maintenance Projects', 'Project Documents', 'Roads', 'Monthly Updates', 'LGUs'].includes(type)) {
                html += `
                    <select id="municipalityFilter" class="filter-btn">
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
                `;
            }

            if (['All', 'Maintenance Projects'].includes(type)) {
                html += `
                    <select id="statusFilter" class="filter-btn">
                        <option value="All">All Statuses</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Completed">Completed</option>
                        <option value="Terminated">Terminated</option>
                    </select>
                `;
            }

            if (type === 'Project Documents') {
                html += `
                    <select id="fileTypeFilter" class="filter-btn">
                        <option value="All">All File Types</option>
                        <option value="PDF">PDF Documents</option>
                        <option value="Image">Images</option>
                        <option value="Excel">Excel Spreadsheets</option>
                        <option value="Word">Word Files</option>
                        <option value="ZIP">ZIP Files</option>
                    </select>
                `;
            }

            if (type === 'Roads') {
                html += `
                    <select id="roadClassFilter" class="filter-btn">
                        <option value="All">All Road Classes</option>
                        <option value="National Road">National Road</option>
                        <option value="Provincial Road">Provincial Road</option>
                        <option value="Municipal Road">Municipal Road</option>
                    </select>
                `;
            }

            if (type === 'Monthly Updates') {
                html += `
                    <div class="month-input-wrap">
                        <button id="monthFromBtn" class="month-display" type="button" data-value="">From Month</button>
                    </div>
                    <div class="month-input-wrap">
                        <button id="monthToBtn" class="month-display" type="button" data-value="">To Month</button>
                    </div>
                `;
            }

            dynamicFilters.innerHTML = html;
            dynamicFilters.querySelectorAll('select').forEach(select => select.addEventListener('change', renderResults));
            document.getElementById('monthFromBtn')?.addEventListener('click', event => openMonthPicker(event.currentTarget));
            document.getElementById('monthToBtn')?.addEventListener('click', event => openMonthPicker(event.currentTarget));
            lucide.createIcons();
        }

        function openMonthPicker(target) {
            activeMonthTarget = target;
            const rect = target.getBoundingClientRect();
            monthPickerPanel.style.left = `${rect.left}px`;
            monthPickerPanel.style.top = `${rect.bottom + 10}px`;
            monthPickerPanel.classList.add('active');
            renderMonthPicker();
        }

        function renderMonthPicker() {
            document.getElementById('monthYearLabel').textContent = activeYear;
            document.getElementById('monthGrid').innerHTML = months.map((m, index) => {
                const value = `${activeYear}-${String(index + 1).padStart(2, '0')}`;
                return `<button class="month-cell" type="button" data-month="${value}">${m}</button>`;
            }).join('');

            document.querySelectorAll('.month-cell').forEach(cell => {
                cell.addEventListener('click', () => {
                    activeMonthTarget.dataset.value = cell.dataset.month;
                    activeMonthTarget.textContent = formatMonth(cell.dataset.month);
                    monthPickerPanel.classList.remove('active');
                    renderResults();
                });
            });
        }

        function formatMonth(value) {
            if (!value) return '';
            const [year, month] = value.split('-');
            return `${months[Number(month) - 1]} ${year}`;
        }

        document.getElementById('monthPrevYear').addEventListener('click', () => { activeYear--; renderMonthPicker(); });
        document.getElementById('monthNextYear').addEventListener('click', () => { activeYear++; renderMonthPicker(); });

        function getCurrentFilters() {
            return {
                q: mainInput.value.toLowerCase().trim(),
                type: recordTypeFilter.value,
                sort: sortFilter.value,
                municipality: document.getElementById('municipalityFilter')?.value || 'All',
                status: document.getElementById('statusFilter')?.value || 'All',
                fileType: document.getElementById('fileTypeFilter')?.value || 'All',
                roadClass: document.getElementById('roadClassFilter')?.value || 'All',
                monthFrom: document.getElementById('monthFromBtn')?.dataset.value || '',
                monthTo: document.getElementById('monthToBtn')?.dataset.value || ''
            };
        }

        function filterRecords() {
            const f = getCurrentFilters();
            let records = buildGlobalRecords();

            records = records.filter(item => {
                const matchSearch = !f.q || item.title.toLowerCase().includes(f.q) || (item.label || '').toLowerCase().includes(f.q) || (item.municipality || '').toLowerCase().includes(f.q);
                const matchType = f.type === 'All' || item.category === f.type;
                const matchMuni = f.municipality === 'All' || item.municipality === f.municipality;
                const matchStatus = f.status === 'All' || item.status === f.status;
                const matchFile = f.fileType === 'All' || item.fileType === f.fileType;
                const matchRoad = f.roadClass === 'All' || item.classification === f.roadClass;
                const matchMonthFrom = !f.monthFrom || (item.month && item.month >= f.monthFrom);
                const matchMonthTo = !f.monthTo || (item.month && item.month <= f.monthTo);

                return matchSearch && matchType && matchMuni && matchStatus && matchFile && matchRoad && matchMonthFrom && matchMonthTo;
            });

            if (f.sort === 'az') records.sort((a, b) => a.title.localeCompare(b.title));
            if (f.sort === 'za') records.sort((a, b) => b.title.localeCompare(a.title));
            if (f.sort === 'newest') records.sort((a, b) => (b.createdAt || 0) - (a.createdAt || 0));
            if (f.sort === 'oldest') records.sort((a, b) => (a.createdAt || 0) - (b.createdAt || 0));

            return records;
        }

        function renderChips(records) {
            const f = getCurrentFilters();
            const chips = [`${records.length} Results`];
            if (f.q) chips.push(`Search: ${f.q}`);
            if (f.type !== 'All') chips.push(f.type);
            if (f.municipality !== 'All') chips.push(f.municipality);
            if (f.status !== 'All') chips.push(f.status);
            if (f.fileType !== 'All') chips.push(f.fileType);
            if (f.roadClass !== 'All') chips.push(f.roadClass);
            if (f.monthFrom) chips.push(`From ${formatMonth(f.monthFrom)}`);
            if (f.monthTo) chips.push(`To ${formatMonth(f.monthTo)}`);

            appliedChips.innerHTML = chips.map(chip => `<span class="filter-chip">${chip}</span>`).join('');
        }

        function renderResults() {
            const records = filterRecords();
            renderChips(records);

            const categories = ['Maintenance Projects', 'Monthly Updates', 'Project Documents', 'Roads', 'Engineers', 'LGUs'];
            const visibleCategories = recordTypeFilter.value === 'All' ? categories : [recordTypeFilter.value];

            globalResults.innerHTML = visibleCategories.map(category => {
                const items = records.filter(record => record.category === category);
                const shown = items.slice(0, 5);

                return `
                    <section class="result-section">
                        <div class="section-head">
                            <div class="section-title-group">
                                <div class="section-icon"><i data-lucide="${getCategoryIcon(category)}"></i></div>
                                <div>
                                    <h3 class="section-title">${category}</h3>
                                    <p class="section-count">${items.length} matching records</p>
                                </div>
                            </div>
                        </div>
                        <div class="section-body">
                            ${shown.length ? `<div class="result-grid">${shown.map(renderResultCard).join('')}</div>` : `<div class="empty-section">No matching records found.</div>`}
                        </div>
                    </section>
                `;
            }).join('');

            lucide.createIcons();
        }

        function getCategoryIcon(category) {
            return {
                'Maintenance Projects': 'wrench',
                'Monthly Updates': 'calendar-days',
                'Project Documents': 'file-text',
                'Roads': 'map',
                'Engineers': 'users',
                'LGUs': 'building-2'
            }[category] || 'search';
        }

        function renderResultCard(item) {
            return `
                <div class="result-card">
                    <h4 class="result-title">${item.title}</h4>
                    <p class="result-meta">${item.category}</p>
                    <p class="result-meta">${item.label || item.municipality || 'System record'}</p>
                    <p class="result-action">View Details →</p>
                </div>
            `;
        }

        recordTypeFilter.addEventListener('change', () => { renderDynamicFilters(); renderResults(); });
        sortFilter.addEventListener('change', renderResults);
        mainInput.addEventListener('input', renderResults);
        document.getElementById('refreshGlobalBtn').addEventListener('click', () => { mainInput.value = ''; recordTypeFilter.value = 'All'; sortFilter.value = 'relevance'; renderDynamicFilters(); renderResults(); });

        document.addEventListener('click', event => {
            if (!event.target.closest('.month-input-wrap') && !event.target.closest('.month-picker-panel')) {
                monthPickerPanel.classList.remove('active');
            }
        });

        initializeFromQuery();
    </script>
</body>
</html>
