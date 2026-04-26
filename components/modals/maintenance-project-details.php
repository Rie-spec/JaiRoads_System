<style>
    .details-overlay {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: none;
        justify-content: center;
        align-items: flex-start;
        padding: 18px;
        background: rgba(15, 23, 42, 0.28);
        backdrop-filter: blur(8px);
        overflow-y: auto;
    }

    .details-overlay.active { display: flex; }

    .details-panel {
        width: min(920px, 100%);
        max-height: 94vh;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 26px;
        box-shadow: 0 28px 70px rgba(15, 23, 42, 0.22);
        padding: 34px 38px 28px;
    }

    .details-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 30px;
    }

    .details-title-wrap { display: flex; align-items: center; gap: 14px; }

    .details-badge {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #eef2ff;
        color: #5d5fef;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .details-title {
        margin: 0 0 4px;
        font-size: 20px;
        font-weight: 900;
        color: #111827;
        line-height: 1.1;
    }

    .details-subtitle {
        margin: 0;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .18em;
        color: #94a3b8;
        text-transform: uppercase;
    }

    .details-close {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 10px;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .details-close:hover { background: #f8fafc; color: #111827; }

    .details-hero {
        border: 1px solid #f1f5f9;
        border-radius: 4px;
        background: #ffffff;
        padding: 24px;
        margin-bottom: 34px;
        box-shadow: 0 10px 26px rgba(148, 163, 184, 0.08);
    }

    .details-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .details-status-pill::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 999px;
        background: currentColor;
    }

    .details-project-name {
        font-size: 24px;
        font-weight: 900;
        color: #111827;
        margin: 0 0 10px;
        line-height: 1.15;
    }

    .details-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        font-size: 12px;
        color: #334155;
    }

    .details-meta span { display: inline-flex; align-items: center; gap: 8px; }
    .details-meta svg { width: 14px; height: 14px; color: #64748b; }

    .details-grid {
        display: grid;
        grid-template-columns: 0.85fr 1.15fr;
        gap: 34px;
        margin-bottom: 34px;
    }

    .details-card {
        border: 1px solid #f1f5f9;
        background: #ffffff;
        border-radius: 4px;
        padding: 20px;
        min-height: 260px;
    }

    .details-section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 18px;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: #9ca3af;
    }

    .details-section-title svg { width: 15px; height: 15px; color: #7c83ff; }

    .details-map {
        height: 230px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background-color: #f8fafc;
        background-image: linear-gradient(to right, #e2e8f0 1px, transparent 1px), linear-gradient(to bottom, #e2e8f0 1px, transparent 1px);
        background-size: 28px 28px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
    }

    .details-map .pin { color: #a5b4fc; }
    .details-map .pin svg { width: 38px; height: 38px; }

    .details-map-strip {
        position: absolute;
        left: 14px;
        right: 14px;
        bottom: 14px;
        border-radius: 8px;
        background: rgba(255,255,255,.9);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 14px;
        font-size: 10px;
        font-weight: 900;
        color: #94a3b8;
        text-transform: uppercase;
    }

    .details-map-strip b { color: #5d5fef; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 15px 17px;
    }

    .info-label {
        font-size: 9px;
        font-weight: 900;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .09em;
        margin-bottom: 8px;
    }

    .info-value { font-size: 14px; font-weight: 800; color: #111827; }

    .linked-list { display: flex; flex-direction: column; gap: 9px; }

    .linked-item {
        border: 1px solid #f1f5f9;
        background: #ffffff;
        border-radius: 4px;
        padding: 12px 14px;
        cursor: pointer;
        transition: .18s ease;
    }

    .linked-item:hover { border-color: #c7d2fe; background: #f8fafc; transform: translateY(-1px); }
    .linked-title { font-size: 13px; font-weight: 900; color: #111827; margin-bottom: 3px; }
    .linked-desc { font-size: 11px; color: #94a3b8; margin: 0; }

    .details-bottom-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 34px;
        padding-top: 28px;
        border-top: 1px solid #f1f5f9;
    }

    .timeline-list, .doc-list { display: flex; flex-direction: column; gap: 16px; }

    .timeline-item {
        display: grid;
        grid-template-columns: 18px 1fr;
        gap: 10px;
        cursor: pointer;
    }

    .timeline-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #818cf8;
        margin-top: 5px;
        box-shadow: 0 0 0 4px #eef2ff;
    }

    .timeline-date { font-size: 11px; color: #6d73d9; font-weight: 900; margin-bottom: 3px; }
    .timeline-title { font-size: 14px; color: #111827; font-weight: 900; margin-bottom: 4px; }
    .timeline-desc { font-size: 12px; color: #475569; line-height: 1.45; margin: 0; }

    .doc-item {
        display: flex;
        align-items: center;
        gap: 14px;
        border: 1px solid #f1f5f9;
        border-radius: 4px;
        padding: 16px;
        cursor: pointer;
        transition: .18s ease;
    }

    .doc-item:hover { border-color: #c7d2fe; background: #f8fafc; }
    .doc-icon { color: #64748b; }
    .doc-icon svg { width: 20px; height: 20px; }
    .doc-name { font-size: 13px; font-weight: 900; color: #111827; margin-bottom: 4px; }
    .doc-meta { font-size: 11px; color: #64748b; }

    .details-footer {
        margin-top: 34px;
        padding-top: 22px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .details-note { font-size: 11px; color: #9ca3af; }

    .details-footer-actions { display: flex; align-items: center; gap: 12px; }

    .details-btn {
        height: 44px;
        padding: 0 22px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .04em;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .details-btn.close { background: transparent; color: #64748b; }
    .details-btn.edit { background: #5d5fef; color: #ffffff; box-shadow: 0 14px 28px rgba(93,95,239,.22); }
    .details-btn.edit svg { width: 16px; height: 16px; }

    @media (max-width: 850px) {
        .details-panel { padding: 24px; }
        .details-grid, .details-bottom-grid { grid-template-columns: 1fr; }
        .info-grid { grid-template-columns: 1fr; }
        .details-footer { flex-direction: column; align-items: stretch; }
        .details-footer-actions { justify-content: flex-end; }
    }
</style>

<div id="projectDetailsOverlay" class="details-overlay">
    <div class="details-panel">
        <div class="details-head">
            <div class="details-title-wrap">
                <div class="details-badge"><i data-lucide="settings"></i></div>
                <div>
                    <h2 class="details-title">Maintenance Project Details</h2>
                    <p class="details-subtitle">Record Overview Panel</p>
                </div>
            </div>
            <button id="closeDetailsBtn" class="details-close" type="button"><i data-lucide="x"></i></button>
        </div>

        <section class="details-hero">
            <span id="detailsStatus" class="details-status-pill">Ongoing</span>
            <h1 id="detailsTitle" class="details-project-name">Project Title</h1>
            <div class="details-meta">
                <span><i data-lucide="user-round"></i>Created by: <b id="detailsCreatedBy">Andrei Jacob</b></span>
                <span><i data-lucide="calendar-days"></i>Date: <b id="detailsDate">TBD</b></span>
            </div>
        </section>

        <section class="details-grid">
            <div class="details-card">
                <div class="details-section-title"><i data-lucide="map"></i><span>Map Preview</span></div>
                <div id="detailsMapPreview" class="details-map" title="Open map preview">
                    <div class="pin"><i data-lucide="map-pin"></i></div>
                    <div class="details-map-strip"><span>Leaflet Preview</span><b>Click to Expand</b></div>
                </div>
            </div>

            <div class="details-card">
                <div class="details-section-title"><i data-lucide="info"></i><span>Project Information</span></div>
                <div class="info-grid">
                    <div class="info-box"><div class="info-label">Municipality</div><div id="detailsMunicipality" class="info-value">TBD</div></div>
                    <div class="info-box"><div class="info-label">Assigned Engineer</div><div id="detailsEngineer" class="info-value">TBD</div></div>
                    <div class="info-box"><div class="info-label">Start Date</div><div id="detailsStart" class="info-value">TBD</div></div>
                    <div class="info-box"><div class="info-label">Estimated End</div><div id="detailsEnd" class="info-value">TBD</div></div>
                </div>

                <div class="details-section-title"><i data-lucide="route"></i><span>Affected Roads</span></div>
                <div id="detailsRoads" class="linked-list"></div>
            </div>
        </section>

        <section class="details-bottom-grid">
            <div class="details-card">
                <div class="details-section-title"><i data-lucide="activity"></i><span>Monthly Updates</span></div>
                <div id="detailsMonthlyUpdates" class="timeline-list"></div>
            </div>

            <div class="details-card">
                <div class="details-section-title"><i data-lucide="folder-open"></i><span>Documents</span></div>
                <div id="detailsDocuments" class="doc-list"></div>
            </div>
        </section>

        <div class="details-footer">
            <p class="details-note">This panel shows the selected maintenance project record and its linked updates and documents.</p>
            <div class="details-footer-actions">
                <button id="detailsCloseFooterBtn" class="details-btn close" type="button">Close</button>
                <button id="editProjectRecordBtn" class="details-btn edit" type="button"><i data-lucide="square-pen"></i>Edit Project Record</button>
            </div>
        </div>
    </div>
</div>
