/* ============================= */
/* SHARED SAMPLE DATA + SIDEBAR GLOBAL SEARCH */
/* ============================= */
const JAIROADS_PROJECT_STORAGE = 'jairoads-maintenance-projects';

const sampleRoads = [
    { id: 'RD-001', title: 'Nabunturan-Monkayo Arterial', name: 'Nabunturan-Monkayo Arterial', category: 'Roads', municipality: 'Nabunturan', section: 'Km 24 - Km 28', classification: 'Provincial Road' },
    { id: 'RD-002', title: 'Poblacion Loop West', name: 'Poblacion Loop West', category: 'Roads', municipality: 'Monkayo', section: 'Full length', classification: 'Municipal Road' },
    { id: 'RD-003', title: 'Compostela-New Bataan Bypass', name: 'Compostela-New Bataan Bypass', category: 'Roads', municipality: 'Compostela', section: 'Km 5 - Km 12', classification: 'Provincial Road' },
    { id: 'RD-004', title: 'Maco-Mawab Connector Road', name: 'Maco-Mawab Connector Road', category: 'Roads', municipality: 'Maco', section: 'Km 3 - Km 9', classification: 'National Road' }
];

const sampleEngineers = ['Juan Dela Cruz', 'Marco Reyes', 'Engr. Santos', 'Engr. Ramos', 'Engr. Cruz', 'Jairus Manliguez', 'Alberto Manliguez'];

const defaultProjects = [
    { id: 'PRJ-610715', title: 'Brgy. Tagbaros Road Repaving', category: 'Maintenance Projects', municipality: 'Nabunturan', status: 'Ongoing', engineer: 'Juan Dela Cruz', startDate: '2026-05-10', endDate: '2026-08-15', roads: [sampleRoads[0], sampleRoads[1]], createdAt: 176500001 },
    { id: 'PRJ-482120', title: 'Capuyan Bridge Repair', category: 'Maintenance Projects', municipality: 'Monkayo', status: 'Completed', engineer: 'Marco Reyes', startDate: '2026-03-15', endDate: '2026-04-20', roads: [sampleRoads[1]], createdAt: 176500002 },
    { id: 'PRJ-225510', title: 'Maco Road Drainage Clearing', category: 'Maintenance Projects', municipality: 'Maco', status: 'Terminated', engineer: 'Engr. Santos', startDate: '2026-01-12', endDate: '2026-02-28', roads: [sampleRoads[3]], createdAt: 176500003 }
];

const sampleMonthlyUpdates = [
    { id: 'UPD-001', title: 'May 2026 Progress Report', category: 'Monthly Updates', municipality: 'Nabunturan', status: 'Ongoing', month: '2026-05', progress: 56, label: 'Brgy. Tagbaros Road Repaving · 56%' },
    { id: 'UPD-002', title: 'April 2026 Completion Report', category: 'Monthly Updates', municipality: 'Monkayo', status: 'Completed', month: '2026-04', progress: 100, label: 'Capuyan Bridge Repair · 100%' },
    { id: 'UPD-003', title: 'March 2026 Site Report', category: 'Monthly Updates', municipality: 'Compostela', status: 'Ongoing', month: '2026-03', progress: 42, label: 'Compostela-New Bataan Bypass · 42%' }
];

const sampleDocuments = [
    { id: 'DOC-001', title: 'Approved_Budget.pdf', category: 'Project Documents', municipality: 'Nabunturan', fileType: 'PDF', label: 'PDF · Brgy. Tagbaros Road Repaving', uploadedAt: '2026-05-05' },
    { id: 'DOC-002', title: 'Environmental_Clearance.docx', category: 'Project Documents', municipality: 'Monkayo', fileType: 'Word', label: 'Word File · Capuyan Bridge Repair', uploadedAt: '2026-04-12' },
    { id: 'DOC-003', title: 'Road_Site_Photos.zip', category: 'Project Documents', municipality: 'Maco', fileType: 'ZIP', label: 'ZIP · Maco Road Drainage Clearing', uploadedAt: '2026-02-10' }
];

const sampleLGUs = ['Compostela', 'Monkayo', 'Nabunturan', 'Maco', 'Mawab'].map(name => ({
    id: `LGU-${name.toUpperCase().replaceAll(' ', '-')}`,
    title: name,
    category: 'LGUs',
    municipality: name,
    label: 'LGU Record · Davao de Oro'
}));

function getStoredProjects() {
    const stored = JSON.parse(localStorage.getItem(JAIROADS_PROJECT_STORAGE));
    if (!stored || stored.length === 0) {
        localStorage.setItem(JAIROADS_PROJECT_STORAGE, JSON.stringify(defaultProjects));
        return defaultProjects;
    }
    return stored;
}

function buildGlobalRecords() {
    const projects = getStoredProjects().map(item => ({
        ...item,
        category: 'Maintenance Projects',
        label: `${item.municipality} · ${item.status}`
    }));

    const roads = sampleRoads.map(item => ({
        ...item,
        label: `${item.municipality} · ${item.section}`
    }));

    const engineers = sampleEngineers.map(name => ({
        id: `ENG-${name.replaceAll(' ', '-').toUpperCase()}`,
        title: name,
        category: 'Engineers',
        label: 'Engineer Record'
    }));

    return [...projects, ...sampleMonthlyUpdates, ...sampleDocuments, ...roads, ...engineers, ...sampleLGUs];
}

function getGlobalFilterParams() {
    const category = document.getElementById('gCategorySelect')?.value || 'All';
    const status = document.getElementById('gStatusSelect')?.value || 'All';
    const sort = document.getElementById('gSortSelect')?.value || 'relevance';
    const municipality = document.getElementById('gMunicipalitySelect')?.value || 'All';
    const engineer = document.getElementById('gEngineerSelect')?.value || 'All';
    const fileType = document.getElementById('gFileTypeSelect')?.value || 'All';

    return {
        category,
        status,
        sort,
        municipalities: municipality === 'All' ? [] : [municipality],
        engineersSelected: engineer === 'All' ? [] : [engineer],
        fileType,
        start: document.getElementById('gStartDate')?.value || '',
        end: document.getElementById('gEndDate')?.value || ''
    };
}

function buildGlobalSearchUrl() {
    const input = document.getElementById('globalSearch');
    const q = encodeURIComponent(input ? input.value.trim() : '');
    const params = getGlobalFilterParams();

    const query = new URLSearchParams({
        q,
        category: params.category,
        status: params.status,
        sort: params.sort,
        municipalities: params.municipalities.join(','),
        engineers: params.engineersSelected.join(','),
        fileType: params.fileType,
        start: params.start,
        end: params.end
    });

    return `${window.JAIROADS_BASE_URL || '../../'}pages/shared/global-search.php?${query.toString()}`;
}

function positionGlobalFilterPanel() {
    const searchBox = document.querySelector('.search-box');
    const panel = document.getElementById('globalFilterPopover');
    if (!searchBox || !panel) return;

    const boxRect = searchBox.getBoundingClientRect();
    const panelWidth = Math.min(410, window.innerWidth - 32);
    const preferredLeft = boxRect.right + 16;
    const fallbackLeft = boxRect.left + 8;
    const left = preferredLeft + panelWidth > window.innerWidth - 16 ? fallbackLeft : preferredLeft;

    panel.style.left = `${Math.max(16, left)}px`;
    panel.style.top = `${Math.min(boxRect.top, window.innerHeight - 120)}px`;
}

function initSidebarGlobalSearch() {
    const globalSearch = document.getElementById('globalSearch');
    const globalSuggestions = document.getElementById('globalSuggestions');
    const suggestionList = document.getElementById('suggestionList');
    const globalFilterBtn = document.getElementById('globalFilterBtn');
    const globalFilterPopover = document.getElementById('globalFilterPopover');
    const globalSearchBtn = document.getElementById('globalSearchBtn');
    const viewMoreResultsBtn = document.getElementById('viewMoreResultsBtn');
    const engineerSelect = document.getElementById('gEngineerSelect');

    if (!globalSearch || !globalSuggestions || !suggestionList || !globalFilterBtn || !globalFilterPopover) return;

    if (engineerSelect) {
        engineerSelect.innerHTML = '<option value="All">All Engineers</option>' + sampleEngineers.map(name => `<option value="${name}">${name}</option>`).join('');
    }

    function renderSuggestions() {
        const keyword = globalSearch.value.toLowerCase().trim();
        if (!keyword) {
            globalSuggestions.classList.remove('active');
            return;
        }

        const matches = buildGlobalRecords().filter(item =>
            item.title.toLowerCase().includes(keyword) ||
            (item.municipality || '').toLowerCase().includes(keyword) ||
            (item.label || '').toLowerCase().includes(keyword)
        ).slice(0, 3);

        suggestionList.innerHTML = matches.map(item => `
            <button type="button" class="suggestion-item" data-suggestion-title="${item.title}">
                <strong>${item.title}</strong>
                <span>${item.category} · ${item.label || ''}</span>
            </button>
        `).join('') || '<button type="button" class="suggestion-item"><strong>No quick matches</strong><span>Try viewing more results.</span></button>';

        globalSuggestions.classList.add('active');
    }

    function goToGlobalSearch() {
        window.location.href = buildGlobalSearchUrl();
    }

    globalSearch.addEventListener('input', renderSuggestions);
    globalSearch.addEventListener('keydown', event => {
        if (event.key === 'Enter') goToGlobalSearch();
    });

    if (globalSearchBtn) globalSearchBtn.addEventListener('click', goToGlobalSearch);
    if (viewMoreResultsBtn) viewMoreResultsBtn.addEventListener('click', goToGlobalSearch);

    globalFilterBtn.addEventListener('click', event => {
        event.stopPropagation();
        globalSuggestions.classList.remove('active');
        positionGlobalFilterPanel();
        globalFilterPopover.classList.toggle('active');
    });

    document.getElementById('closeGlobalFilters')?.addEventListener('click', () => {
        globalFilterPopover.classList.remove('active');
    });

    document.getElementById('advancedFilterToggle')?.addEventListener('click', () => {
        const content = document.getElementById('advancedFilterContent');
        content?.classList.toggle('active');
    });

    document.getElementById('applyGlobalFilters')?.addEventListener('click', goToGlobalSearch);

    document.getElementById('resetGlobalFilters')?.addEventListener('click', () => {
        document.getElementById('gCategorySelect').value = 'All';
        document.getElementById('gStatusSelect').value = 'All';
        document.getElementById('gMunicipalitySelect').value = 'All';
        document.getElementById('gEngineerSelect').value = 'All';
        document.getElementById('gSortSelect').value = 'relevance';
        document.getElementById('gFileTypeSelect').value = 'All';
        document.getElementById('gStartDate').value = '';
        document.getElementById('gEndDate').value = '';
    });

    document.addEventListener('click', event => {
        if (!event.target.closest('.search-box') && !globalFilterPopover.contains(event.target)) {
            globalSuggestions.classList.remove('active');
            globalFilterPopover.classList.remove('active');
        }
    });

    window.addEventListener('resize', () => {
        if (globalFilterPopover.classList.contains('active')) positionGlobalFilterPanel();
    });
}

document.addEventListener('DOMContentLoaded', initSidebarGlobalSearch);
