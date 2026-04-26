<style>
    /* ============================= */
    /* MAINTENANCE PROJECT FORM MODAL */
    /* ============================= */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(71, 85, 105, 0.3);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
        --card: #ffffff;
        --text1: #111827;
        --text2: #9ca3af;
        --text3: #6b7280;
        --accent1: #6366f1;
        --accent2: #e0e7ff;
        --border: #f3f4f6;
        --input: #f8fafc;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .modal-card {
        background-color: var(--card);
        width: 100%;
        max-width: 900px;
        border-radius: 30px;
        padding: 40px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transform: scale(0.95);
        transition: transform 0.3s ease;
        max-height: 95vh;
        overflow-y: auto;
        text-align: left;
    }

    .modal-overlay.active .modal-card {
        transform: scale(1);
    }

    .m-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        width: 100%;
        margin-bottom: 35px;
    }

    .m-titlebox {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .m-badge {
        background-color: var(--accent2);
        color: var(--accent1);
        padding: 12px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        box-sizing: border-box;
    }

    .m-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text1);
        margin: 0 0 4px 0;
        line-height: 1.2;
    }

    .m-sub {
        font-size: 11px;
        font-weight: 700;
        color: var(--text2);
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 0;
    }

    .m-close {
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        cursor: pointer;
        color: var(--text2);
        padding: 8px;
        border-radius: 8px;
    }

    .m-close:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .sect {
        margin-bottom: 35px;
    }

    .secthead {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        font-size: 11px;
        font-weight: 800;
        color: var(--text2);
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .secthead svg {
        color: #818cf8;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .grid2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        width: 100%;
    }

    .grid2inner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        width: 100%;
    }

    .lbl {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: var(--text3);
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .m-inp {
        width: 100%;
        height: 46px;
        line-height: 46px;
        box-sizing: border-box;
        background-color: var(--input);
        border: none;
        border-radius: 12px;
        padding: 0 16px;
        font-size: 14px;
        font-weight: 500;
        color: var(--text1);
        outline: none;
        display: block;
    }

    .m-inp::placeholder {
        color: var(--text2);
        font-weight: 400;
    }

    .m-inp:focus {
        box-shadow: 0 0 0 2px var(--accent2);
    }

    select.m-inp {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2216%22%20height%3D%2216%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
        padding-right: 42px;
        cursor: pointer;
    }

    .m-wrap {
        position: relative;
        display: block;
        width: 100%;
    }

    .m-wrap.left .m-inp {
        padding-left: 42px;
    }

    .m-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text2);
        width: 16px;
        height: 16px;
    }

    .m-icon.l {
        left: 16px;
    }

    .roadhead {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .addbtn {
        color: var(--accent1);
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 6px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }

    .grid5 {
        display: grid;
        grid-template-columns: 3fr 2fr;
        gap: 24px;
        width: 100%;
    }

    .roads {
        display: flex;
        flex-direction: column;
        gap: 16px;
        position: relative;
    }

    .m-inp.srch {
        background-color: var(--card);
        border: 1px solid var(--border);
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .road-results {
        position: absolute;
        top: 50px;
        left: 0;
        right: 0;
        z-index: 20;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 18px 36px rgba(15,23,42,.12);
        padding: 8px;
        display: none;
        max-height: 190px;
        overflow-y: auto;
    }

    .road-result {
        width: 100%;
        border: none;
        background: transparent;
        text-align: left;
        border-radius: 10px;
        padding: 10px;
        cursor: pointer;
    }

    .road-result:hover {
        background: #f8fafc;
    }

    .road-result strong {
        display: block;
        font-size: 12px;
        color: #111827;
    }

    .road-result span {
        display: block;
        font-size: 10px;
        color: #94a3b8;
        margin-top: 3px;
    }

    .list {
        background-color: #f8fafc99;
        padding: 20px;
        border-radius: 16px;
    }

    .listtitle {
        font-size: 11px;
        font-weight: 800;
        color: var(--text2);
        margin-bottom: 15px;
        text-transform: uppercase;
    }

    .item {
        background-color: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .iteminfo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .itembadge {
        background-color: var(--accent2);
        color: var(--accent1);
        padding: 8px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .itemname {
        font-size: 13px;
        font-weight: 700;
        color: var(--text1);
        margin-bottom: 2px;
    }

    .itemdesc {
        font-size: 11px;
        color: var(--text2);
        margin: 0;
    }

    .del {
        background: none;
        border: none;
        color: #e5e7eb;
        cursor: pointer;
        padding: 5px;
    }

    .del:hover {
        color: #ef4444;
    }

    .map {
        background-color: var(--input);
        background-image: linear-gradient(to right, #e2e8f0 1px, transparent 1px), linear-gradient(to bottom, #e2e8f0 1px, transparent 1px);
        background-size: 25px 25px;
        border: 1px solid var(--border);
        border-radius: 16px;
        position: relative;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .expand {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--card);
        padding: 8px;
        border-radius: 8px;
        color: var(--text2);
        cursor: pointer;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: flex;
    }

    .pin {
        color: #a5b4fc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .overlay {
        position: absolute;
        bottom: 15px;
        left: 15px;
        right: 15px;
        background: rgba(255,255,255,0.9);
        padding: 12px 16px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        font-weight: 800;
        color: var(--text2);
    }

    .link {
        color: var(--accent1);
        cursor: pointer;
    }

    .foot {
        border-top: 1px solid var(--border);
        padding-top: 25px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 15px;
        margin-top: 20px;
    }

    .btn {
        padding: 0 28px;
        height: 46px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        border: none;
        letter-spacing: 0.5px;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cancel {
        background: transparent;
        color: var(--text3);
    }

    .save {
        background-color: var(--accent1);
        color: var(--card);
        gap: 8px;
    }

    @media (max-width: 850px) {
        .grid2,
        .grid2inner,
        .grid5 {
            grid-template-columns: 1fr;
        }
    }
</style>

<div id="projectModalOverlay" class="modal-overlay">
    <div class="modal-card">
        <!-- FORM HEADER START -->
        <div class="m-head">
            <div class="m-titlebox">
                <div class="m-badge"><i data-lucide="wrench"></i></div>
                <div>
                    <h1 class="m-title">New Maintenance Project</h1>
                    <p class="m-sub">Record Entry Form</p>
                </div>
            </div>

            <button id="closeModalBtn" class="m-close" type="button">
                <i data-lucide="x"></i>
            </button>
        </div>
        <!-- FORM HEADER END -->

        <!-- PROJECT INFORMATION START -->
        <div class="sect">
            <div class="secthead"><i data-lucide="info"></i><span>Project Information</span></div>

            <div class="grid2">
                <div>
                    <label class="lbl">Project Title</label>
                    <input id="formTitle" type="text" placeholder="e.g. Brgy. Tagbaros Road Repaving" class="m-inp">
                </div>

                <div>
                    <label class="lbl">Municipality</label>
                    <select id="formMunicipality" class="m-inp">
                        <option value="">Select Municipality</option>
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
            </div>
        </div>
        <!-- PROJECT INFORMATION END -->

        <!-- SCHEDULE AND PERSONNEL START -->
        <div class="sect">
            <div class="grid2">
                <div>
                    <div class="secthead"><i data-lucide="calendar"></i><span>Schedule</span></div>
                    <div class="grid2inner">
                        <div>
                            <label class="lbl">Start Date</label>
                            <input id="formStart" type="date" class="m-inp">
                        </div>
                        <div>
                            <label class="lbl">Estimated End</label>
                            <input id="formEnd" type="date" class="m-inp">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="secthead"><i data-lucide="users"></i><span>Personnel & Status</span></div>
                    <div class="grid2inner">
                        <div>
                            <label class="lbl">Assigned Engineer</label>
                            <div class="m-wrap left">
                                <i class="m-icon l" data-lucide="user-cog"></i>
                                <input id="formEngineer" type="text" placeholder="Search Engineer" class="m-inp">
                            </div>
                        </div>
                        <div>
                            <label class="lbl">Status</label>
                            <select id="formStatus" class="m-inp">
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                                <option value="Terminated">Terminated</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SCHEDULE AND PERSONNEL END -->

        <!-- AFFECTED ROADS START -->
        <div class="sect">
            <div class="roadhead">
                <div class="secthead" style="margin-bottom:0;"><i data-lucide="map"></i><span>Affected Roads Selection</span></div>
                <button class="addbtn" type="button"><i data-lucide="plus-circle"></i>Add New Road to System</button>
            </div>

            <div class="grid5">
                <div class="roads">
                    <div class="m-wrap left">
                        <i class="m-icon l" data-lucide="search"></i>
                        <input id="roadSearchInput" type="text" placeholder="Search roads by name to add..." class="m-inp srch">
                    </div>

                    <div id="roadResults" class="road-results"></div>

                    <div class="list">
                        <div id="selectedAssetsTitle" class="listtitle">Selected Assets (0)</div>
                        <div id="selectedAssets">
                            <p class="itemdesc">No roads selected yet.</p>
                        </div>
                    </div>
                </div>

                <div class="map">
                    <div class="expand"><i data-lucide="maximize"></i></div>
                    <div class="pin"><i data-lucide="map-pin" style="width:40px;height:40px;"></i></div>
                    <div class="overlay"><span>Preview Mode</span><span class="link">Click to Expand</span></div>
                </div>
            </div>
        </div>
        <!-- AFFECTED ROADS END -->

        <div class="foot">
            <button id="cancelModalBtn" class="btn cancel" type="button">Cancel</button>
            <button id="saveProjectBtn" class="btn save" type="button"><i data-lucide="check-circle"></i>Save Project Entry</button>
        </div>
    </div>
</div>
