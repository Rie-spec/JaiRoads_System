<?php
    $baseUrl = $baseUrl ?? '../../';
    $activePage = $activePage ?? '';

    function navActive($current, $activePage) {
        return $current === $activePage ? 'active' : '';
    }
?>

<style>
    /* ============================= */
    /* SIDEBAR GLOBAL SEARCH COMPONENT */
    /* ============================= */
    .global-suggestions {
        position: absolute;
        left: 0;
        right: 0;
        top: calc(100% + 10px);
        z-index: 1000;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        box-shadow: 0 22px 50px rgba(15, 23, 42, 0.16);
        overflow: hidden;
        display: none;
    }

    .global-suggestions.active {
        display: block;
    }

    .suggestion-head {
        padding: 14px 16px 8px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .14em;
        color: #94a3b8;
    }

    .suggestion-list {
        max-height: 250px;
        overflow-y: auto;
        padding: 4px 8px 10px;
    }

    .suggestion-item {
        width: 100%;
        border: none;
        background: transparent;
        text-align: left;
        padding: 11px 10px;
        border-radius: 14px;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background: #f8fafc;
    }

    .suggestion-item strong {
        display: block;
        font-size: 12px;
        color: #1e293b;
        margin-bottom: 3px;
    }

    .suggestion-item span {
        display: block;
        font-size: 10px;
        color: #94a3b8;
        font-weight: 700;
    }

    .view-more-search {
        width: 100%;
        border: none;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
        padding: 13px 16px;
        color: #5d5fef;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .08em;
        cursor: pointer;
    }

    /* ============================= */
    /* REDESIGNED FILTER PANEL */
    /* Simple first, advanced second */
    /* ============================= */
    .global-filter-popover {
        position: fixed;
        width: 410px;
        max-width: calc(100vw - 32px);
        max-height: calc(100vh - 120px);
        z-index: 5000;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 28px;
        box-shadow: 0 30px 80px rgba(15, 23, 42, 0.22);
        overflow: hidden;
        display: none;
    }

    .global-filter-popover.active {
        display: block;
    }

    .simple-filter-head {
        padding: 18px 20px 14px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .simple-filter-title {
        font-size: 16px;
        font-weight: 900;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .simple-filter-subtitle {
        font-size: 10px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .12em;
    }

    .filter-close-btn {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 12px;
        background: #f8fafc;
        color: #94a3b8;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .filter-close-btn:hover {
        background: #eef2ff;
        color: #5d5fef;
    }

    .simple-filter-body {
        padding: 18px 20px;
        max-height: calc(100vh - 280px);
        overflow-y: auto;
        overflow-x: hidden;
    }

    .filter-help-card {
        background: #eef2ff;
        border: 1px solid #e0e7ff;
        border-radius: 18px;
        padding: 12px 14px;
        color: #4f46e5;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 16px;
    }

    .quick-filter-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 13px;
    }

    .quick-filter-field {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .quick-filter-label {
        font-size: 10px;
        font-weight: 900;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .14em;
        padding-left: 2px;
    }

    .quick-filter-control {
        width: 100%;
        height: 42px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        border-radius: 14px;
        padding: 0 13px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        outline: none;
    }

    .quick-filter-control:focus {
        border-color: #5d5fef;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(93, 95, 239, .12);
    }

    .advanced-filter-toggle {
        width: 100%;
        margin-top: 16px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        border-radius: 16px;
        padding: 12px 14px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .advanced-filter-toggle:hover {
        background: #f8fafc;
    }

    .advanced-filter-content {
        display: none;
        margin-top: 12px;
        padding: 14px;
        border: 1px solid #f1f5f9;
        background: #fbfdff;
        border-radius: 18px;
    }

    .advanced-filter-content.active {
        display: block;
    }

    .date-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .simple-filter-footer {
        padding: 16px 20px;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .reset-filter-btn,
    .apply-filter-btn {
        border: none;
        cursor: pointer;
        font-weight: 900;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .reset-filter-btn {
        background: transparent;
        color: #94a3b8;
    }

    .apply-filter-btn {
        background: #5d5fef;
        color: #fff;
        border-radius: 15px;
        padding: 13px 18px;
        box-shadow: 0 14px 24px rgba(93,95,239,.18);
    }

    @media (max-width: 720px) {
        .global-filter-popover {
            width: calc(100vw - 28px);
        }

        .date-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<aside class="sidebar">
    <div class="sidebar-top">
        <div class="brand-row">
            <div class="brand-icon-box"><i data-lucide="navigation"></i></div>
            <h1 class="brand-name">JAIROADS</h1>
        </div>

        <!-- GLOBAL SEARCH START -->
        <div class="search-box">
            <div class="custom-searchbar">
                <button id="globalSearchBtn" class="search-action-btn" type="button" aria-label="Search">
                    <svg class="search-action-icon" viewBox="0 0 24 24">
                        <path d="M10 2a8 8 0 105.3 14.3l4.2 4.2 1.4-1.4-4.2-4.2A8 8 0 0010 2zm0 2a6 6 0 110 12 6 6 0 010-12z"/>
                    </svg>
                </button>

                <input id="globalSearch" class="custom-search-input" type="text" placeholder="Search everything...">

                <button id="globalFilterBtn" class="search-action-btn" type="button" aria-label="Global filters">
                    <svg class="search-action-icon" viewBox="0 0 24 24">
                        <path d="M3 4h18l-7 8v6l-4 2v-8L3 4z"/>
                    </svg>
                </button>
            </div>

            <div id="globalSuggestions" class="global-suggestions">
                <div class="suggestion-head">Top Suggestions</div>
                <div id="suggestionList" class="suggestion-list"></div>
                <button id="viewMoreResultsBtn" class="view-more-search" type="button">View more results</button>
            </div>
        </div>
        <!-- GLOBAL SEARCH END -->

        <p class="sidebar-label">Infrastructure Hub</p>

        <nav class="sidebar-nav">
            <a href="<?php echo $baseUrl; ?>pages/engineer/dashboard.php" class="nav-item <?php echo navActive('dashboard', $activePage); ?>">
                <i data-lucide="layout-dashboard"></i><span>Dashboard</span>
            </a>

            <a href="<?php echo $baseUrl; ?>pages/engineer/maintenance-projects.php" class="nav-item <?php echo navActive('maintenance-projects', $activePage); ?>">
                <i data-lucide="wrench"></i><span>Maintenance Projects</span>
            </a>

            <a href="<?php echo $baseUrl; ?>pages/engineer/monthly-updates.php" class="nav-item <?php echo navActive('monthly-updates', $activePage); ?>">
                <i data-lucide="calendar"></i><span>Monthly Updates</span>
            </a>

            <a href="<?php echo $baseUrl; ?>pages/engineer/project-documents.php" class="nav-item <?php echo navActive('project-documents', $activePage); ?>">
                <i data-lucide="file-text"></i><span>Project Documents</span>
            </a>

            <a href="<?php echo $baseUrl; ?>pages/engineer/engineers.php" class="nav-item <?php echo navActive('engineers', $activePage); ?>">
                <i data-lucide="users"></i><span>Engineers</span>
            </a>

            <a href="<?php echo $baseUrl; ?>pages/engineer/lgus.php" class="nav-item <?php echo navActive('lgus', $activePage); ?>">
                <i data-lucide="building-2"></i><span>LGUs</span>
            </a>

            <a href="<?php echo $baseUrl; ?>pages/engineer/account.php" class="nav-item <?php echo navActive('account', $activePage); ?>">
                <i data-lucide="user-circle"></i><span>Account</span>
            </a>
        </nav>
    </div>

    <div class="sidebar-footer">
        <div class="profile-card">
            <div class="profile-avatar">JD</div>
            <div class="profile-text">
                <p class="profile-name">Jairus John Claude...</p>
                <p class="profile-role">Engineer</p>
            </div>
        </div>

        <button class="logout-btn" type="button">
            <i data-lucide="log-out"></i><span>Logout</span>
        </button>
    </div>
</aside>

<!-- FLOATING GLOBAL FILTER PANEL START -->
<div id="globalFilterPopover" class="global-filter-popover">
    <div class="simple-filter-head">
        <div>
            <h3 class="simple-filter-title">Quick Filters</h3>
            <p class="simple-filter-subtitle">Keep search simple for field users</p>
        </div>
        <button id="closeGlobalFilters" class="filter-close-btn" type="button" aria-label="Close filters">
            <i data-lucide="x"></i>
        </button>
    </div>

    <div class="simple-filter-body">
        <div class="filter-help-card">
            Choose the record type first, then narrow results using status, municipality, or assigned engineer only when needed.
        </div>

        <div class="quick-filter-grid">
            <div class="quick-filter-field">
                <label class="quick-filter-label" for="gCategorySelect">Record Type</label>
                <select id="gCategorySelect" class="quick-filter-control">
                    <option value="All">All Records</option>
                    <option value="Maintenance Projects">Maintenance Projects</option>
                    <option value="Monthly Updates">Monthly Updates</option>
                    <option value="Project Documents">Project Documents</option>
                    <option value="Roads">Roads</option>
                    <option value="Engineers">Engineers</option>
                    <option value="LGUs">LGUs</option>
                </select>
            </div>

            <div class="quick-filter-field">
                <label class="quick-filter-label" for="gStatusSelect">Project Status</label>
                <select id="gStatusSelect" class="quick-filter-control">
                    <option value="All">All Statuses</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                    <option value="Terminated">Terminated</option>
                </select>
            </div>

            <div class="quick-filter-field">
                <label class="quick-filter-label" for="gMunicipalitySelect">Municipality</label>
                <select id="gMunicipalitySelect" class="quick-filter-control">
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
            </div>

            <div class="quick-filter-field">
                <label class="quick-filter-label" for="gEngineerSelect">Assigned Engineer</label>
                <select id="gEngineerSelect" class="quick-filter-control">
                    <option value="All">All Engineers</option>
                </select>
            </div>

            <div class="quick-filter-field">
                <label class="quick-filter-label" for="gSortSelect">Sort By</label>
                <select id="gSortSelect" class="quick-filter-control">
                    <option value="relevance">Relevance</option>
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="az">A-Z</option>
                    <option value="za">Z-A</option>
                </select>
            </div>
        </div>

        <button id="advancedFilterToggle" class="advanced-filter-toggle" type="button">
            <span>Advanced options</span>
            <i data-lucide="chevron-down"></i>
        </button>

        <div id="advancedFilterContent" class="advanced-filter-content">
            <div class="quick-filter-grid">
                <div class="quick-filter-field">
                    <label class="quick-filter-label" for="gFileTypeSelect">Document Format</label>
                    <select id="gFileTypeSelect" class="quick-filter-control">
                        <option value="All">All File Types</option>
                        <option value="PDF">PDF Documents</option>
                        <option value="Image">Images</option>
                        <option value="Excel">Excel Spreadsheets</option>
                        <option value="Word">Word Files</option>
                        <option value="ZIP">ZIP Files</option>
                    </select>
                </div>

                <div class="quick-filter-field">
                    <label class="quick-filter-label">Date Range</label>
                    <div class="date-row">
                        <input id="gStartDate" class="quick-filter-control" type="date" aria-label="Start date">
                        <input id="gEndDate" class="quick-filter-control" type="date" aria-label="End date">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="simple-filter-footer">
        <button id="resetGlobalFilters" class="reset-filter-btn" type="button">Reset</button>
        <button id="applyGlobalFilters" class="apply-filter-btn" type="button">Search Records</button>
    </div>
</div>
<!-- FLOATING GLOBAL FILTER PANEL END -->
