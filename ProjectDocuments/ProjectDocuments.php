<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$upload_dir = __DIR__ . '/secure_uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'message' => 'CSRF validation failed.']);
        exit;
    }

    $title = htmlspecialchars(trim($_POST['title'] ?? ''), ENT_QUOTES, 'UTF-8');
    $municipality = htmlspecialchars(trim($_POST['municipality'] ?? ''), ENT_QUOTES, 'UTF-8');
    $status = htmlspecialchars(trim($_POST['status'] ?? ''), ENT_QUOTES, 'UTF-8');
    $engineer = htmlspecialchars(trim($_POST['engineer'] ?? ''), ENT_QUOTES, 'UTF-8');
    $docType = htmlspecialchars(trim($_POST['docType'] ?? ''), ENT_QUOTES, 'UTF-8');

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'File upload error.']);
        exit;
    }

    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileInfo->file($_FILES['file']['tmp_name']);

    $allowedMimes = [
        'application/pdf' => 'pdf',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/msword' => 'doc',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/svg+xml' => 'svg'
    ];

    if (!array_key_exists($mimeType, $allowedMimes)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
        exit;
    }

    if ($_FILES['file']['size'] > 25000000) {
        echo json_encode(['success' => false, 'message' => 'File exceeds 25MB limit.']);
        exit;
    }

    $extension = $allowedMimes[$mimeType];
    $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $title) . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    $destination = $upload_dir . $safeFilename;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
        echo json_encode([
            'success' => true, 
            'data' => [
                'id' => uniqid('doc_'),
                'title' => $title,
                'municipality' => $municipality,
                'status' => $status,
                'engineer' => $engineer,
                'type' => $docType,
                'date' => date('Y-m-d'),
                'filename' => $safeFilename
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
    }
    exit;
}

$current_page = 'project_documents';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JaiRoads - Project Documents</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link rel="stylesheet" href="Projstyle.css">
</head>
<body>
    <div class="page-layout">
        
        <?php include 'sidebar.php'; ?>

        <main class="main-content">
            <div class="page-heading-row">
                <div>
                    <h1 class="page-main-title">Project Documents</h1>
                    <div class="page-subtitle">Davao De Oro Region</div>
                </div>
                <button class="primary-btn" onclick="openModal('uploadModal')">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Upload Project Document
                </button>
            </div>

            <div class="filter-shell">
                <div class="filter-search">
                    <svg class="filter-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="searchInput" placeholder="Search by project title or road name...">
                </div>
                <div class="filter-actions">
                    <select class="filter-btn" id="statusFilter">
                        <option value="All">All Maintenance Projects</option>
                        <option value="Completed">Completed</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Terminated">Terminated</option>
                    </select>
                    <select class="filter-btn" id="typeFilter">
                        <option value="All">All File Types</option>
                        <option value="PDF">PDF</option>
                        <option value="Excel">Excel</option>
                        <option value="JPEG">JPEG</option>
                        <option value="SVG">SVG</option>
                        <option value="DOCX">DOCX</option>
                        <option value="DWG">DWG</option>
                    </select>
                    <button class="refresh-btn" onclick="renderTable()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                    </button>
                </div>
            </div>

            <div class="records-shell">
                <div class="records-head">
                    <div class="head-col">Project Title</div>
                    <div class="head-col">Municipality</div>
                    <div class="head-col">Status</div>
                    <div class="head-col">Engineer</div>
                    <div class="head-col">File Type</div>
                    <div class="head-col">Upload Date</div>
                    <div class="head-col actions-col">Actions</div>
                </div>

                <div class="records-body">
                    <div id="recordsContainer"></div>
                    <div class="records-placeholder" id="emptyState">
                        Place your records here.
                    </div>
                </div>

                <div class="records-footer">
                    <div class="records-note" id="showingText">SHOWING 0-0 OF 0 PROJECTS</div>
                    <div class="pagination" id="paginationControls"></div>
                </div>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="uploadModal">
        <div class="modal-card">
            <div class="m-head">
                <div class="m-titlebox">
                    <div class="m-badge"><i data-lucide="file-up"></i></div>
                    <div>
                        <h1 class="m-title">Upload Project Document</h1>
                        <p class="m-sub">DOCUMENT REPOSITORY FORM</p>
                    </div>
                </div>
                <button class="m-close" onclick="closeModal('uploadModal')" type="button"><i data-lucide="x"></i></button>
            </div>

            <form id="uploadForm">
                <input type="hidden" id="csrfToken" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                
                <div class="sect">
                    <div class="secthead"><i data-lucide="info"></i><span>PROJECT INFORMATION</span></div>
                    <div class="grid2">
                        <div>
                            <label class="lbl">PROJECT TITLE</label>
                            <input type="text" id="docTitle" class="m-inp" required>
                        </div>
                        <div>
                            <label class="lbl">MUNICIPALITY</label>
                            <select id="docMunicipality" class="m-inp" required>
                                <option value="" disabled selected>Select Municipality...</option>
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

                <div class="sect">
                    <div class="secthead"><i data-lucide="users"></i><span>PERSONNEL & STATUS</span></div>
                    <div class="grid2">
                        <div>
                            <label class="lbl">ASSIGNED ENGINEER</label>
                            <select id="docEngineer" class="m-inp" required>
                                <option value="" disabled selected>Select Engineer...</option>
                                <option value="Prince Adrian Badoy">Prince Adrian Badoy</option>
                                <option value="Andrei Jacob Malaluan">Andrei Jacob Malaluan</option>
                                <option value="Gavriel Fritz Polbo">Gavriel Fritz Polbo</option>
                                <option value="Eric Yuson">Eric Yuson</option>
                            </select>
                        </div>
                        <div>
                            <label class="lbl">STATUS</label>
                            <select id="docStatus" class="m-inp" required>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Completed">Completed</option>
                                <option value="Terminated">Terminated</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="sect" style="margin-bottom: 15px;">
                    <div class="secthead"><i data-lucide="paperclip"></i><span>ATTACHMENT DETAILS</span></div>
                    <div class="grid2">
                        <div>
                            <label class="lbl">UPLOAD FILE</label>
                            <div class="upload-zone" id="dropZone">
                                <div class="upload-icon"><i data-lucide="cloud-upload"></i></div>
                                <p class="upload-text-main" id="fileNameDisplay"><span>Click to browse</span> or drag file here</p>
                                <p class="upload-text-sub">PDF, DOCX, XLSX, JPG or PNG (Max 25MB)</p>
                                <input type="file" id="fileInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.svg,.dwg" required>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            <div>
                                <label class="lbl">FILE TYPE</label>
                                <select id="docType" class="m-inp" required>
                                    <option value="" disabled selected>Select File Type...</option>
                                    <option value="PDF">PDF</option>
                                    <option value="Excel">Excel</option>
                                    <option value="JPEG">JPEG</option>
                                    <option value="SVG">SVG</option>
                                    <option value="DOCX">DOCX</option>
                                    <option value="DWG">DWG</option>
                                </select>
                            </div>
                            <div>
                                <label class="lbl">DESCRIPTION (OPTIONAL)</label>
                                <textarea id="docDescription" class="m-inp"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="foot">
                    <button type="button" class="btn cancel" onclick="closeModal('uploadModal')">CANCEL</button>
                    <button type="submit" class="btn save" id="submitBtn">
                        <i data-lucide="upload"></i><span>SAVE PROJECT ENTRY</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="viewModal">
        <div class="modal">
            <h2>Document Details</h2>
            <div class="details-grid">
                <div class="detail-item full-width"><label>Project Title</label><div id="viewTitle"></div></div>
                <div class="detail-item"><label>Municipality</label><div id="viewMunicipality"></div></div>
                <div class="detail-item"><label>Status</label><div id="viewStatus"></div></div>
                <div class="detail-item full-width"><label>Assigned Engineer</label><div id="viewEngineer"></div></div>
                <div class="detail-item"><label>File Type</label><div id="viewType"></div></div>
                <div class="detail-item"><label>Upload Date</label><div id="viewDate"></div></div>
            </div>
            <div class="modal-actions">
                <button type="button" class="primary-btn" style="height: 44px; width: 100%;" onclick="closeModal('viewModal')">Close</button>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>