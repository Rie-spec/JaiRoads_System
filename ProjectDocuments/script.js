lucide.createIcons();

let documents = [
    { id: 'doc_1', title: "Nabunturan Core Road", municipality: "Nabunturan", status: "Ongoing", engineer: "Prince Adrian Badoy", type: "PDF", date: "2026-04-20" },
    { id: 'doc_2', title: "Mawab Highway Extension", municipality: "Mawab", status: "Completed", engineer: "Andrei Jacob Malaluan", type: "Excel", date: "2026-04-22" },
    { id: 'doc_3', title: "Maco Coastal Phase 1", municipality: "Maco", status: "Terminated", engineer: "Gavriel Fritz Polbo", type: "DWG", date: "2026-04-25" }
];

let currentPage = 1;
const itemsPerPage = 5;

const recordsContainer = document.getElementById('recordsContainer');
const emptyState = document.getElementById('emptyState');
const searchInput = document.getElementById('searchInput');
const typeFilter = document.getElementById('typeFilter');
const statusFilter = document.getElementById('statusFilter');
const uploadForm = document.getElementById('uploadForm');
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const fileNameDisplay = document.getElementById('fileNameDisplay');
const uploadIcon = document.querySelector('.upload-icon i');
const docTypeSelect = document.getElementById('docType');

if (searchInput) searchInput.addEventListener('input', () => { currentPage = 1; renderTable(); });
if (typeFilter) typeFilter.addEventListener('change', () => { currentPage = 1; renderTable(); });
if (statusFilter) statusFilter.addEventListener('change', () => { currentPage = 1; renderTable(); });

if (dropZone) {
    dropZone.addEventListener('dragover', () => dropZone.classList.add('dragover'));
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', () => dropZone.classList.remove('dragover'));
}

if (fileInput) {
    fileInput.addEventListener('change', (e) => {
        const files = e.target.files;
        if (files.length > 0) {
            const fileName = files[0].name;
            fileNameDisplay.innerHTML = `<span style="color: #111827;">Selected:</span> <span style="color: #4f46e5; text-decoration: underline;">${fileName}</span>`;
            uploadIcon.setAttribute('data-lucide', 'check-circle-2');
            document.querySelector('.upload-icon').style.color = '#16a34a'; 
            document.querySelector('.upload-icon').style.backgroundColor = '#dcfce7'; 
            lucide.createIcons();

            const ext = fileName.split('.').pop().toLowerCase();
            if (ext === 'pdf') docTypeSelect.value = 'PDF';
            else if (['xls', 'xlsx'].includes(ext)) docTypeSelect.value = 'Excel';
            else if (['jpg', 'jpeg', 'png'].includes(ext)) docTypeSelect.value = 'JPEG';
            else if (ext === 'svg') docTypeSelect.value = 'SVG';
            else if (['doc', 'docx'].includes(ext)) docTypeSelect.value = 'DOCX';
            else if (ext === 'dwg') docTypeSelect.value = 'DWG';
        } else {
            resetFileUI();
        }
    });
}

function resetFileUI() {
    if (!fileNameDisplay) return;
    fileNameDisplay.innerHTML = `<span>Click to browse</span> or drag file here`;
    uploadIcon.setAttribute('data-lucide', 'cloud-upload');
    document.querySelector('.upload-icon').style.color = 'var(--accent1)';
    document.querySelector('.upload-icon').style.backgroundColor = '#ffffff';
    lucide.createIcons();
}

function openModal(modalId) { 
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'flex'; 
}

function closeModal(modalId) { 
    const modal = document.getElementById(modalId);
    if (modal) modal.style.display = 'none'; 
    if (modalId === 'uploadModal' && uploadForm) {
        uploadForm.reset(); 
        resetFileUI();
    }
}

function viewDocument(docId) {
    const doc = documents.find(d => d.id === docId);
    if (doc) {
        document.getElementById('viewTitle').innerText = doc.title;
        document.getElementById('viewMunicipality').innerText = doc.municipality;
        document.getElementById('viewStatus').innerText = doc.status;
        document.getElementById('viewEngineer').innerText = doc.engineer;
        document.getElementById('viewType').innerText = doc.type;
        document.getElementById('viewDate').innerText = doc.date;
        openModal('viewModal');
    }
}

if (uploadForm) {
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span>UPLOADING...</span>';
        submitBtn.disabled = true;

        const formData = new FormData();
        formData.append('csrf_token', document.getElementById('csrfToken').value);
        formData.append('title', document.getElementById('docTitle').value);
        formData.append('municipality', document.getElementById('docMunicipality').value);
        formData.append('status', document.getElementById('docStatus').value);
        formData.append('engineer', document.getElementById('docEngineer').value);
        formData.append('docType', document.getElementById('docType').value);
        formData.append('file', document.getElementById('fileInput').files[0]);

        try {
            const response = await fetch('ProjectDocuments.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                documents.unshift(result.data);
                closeModal('uploadModal');
                renderTable();
            } else {
                alert(result.message || 'Error uploading document.');
            }
        } catch (error) {
            alert('A network error occurred while uploading.');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });
}

function renderTable() {
    if (!recordsContainer) return;
    
    const query = searchInput.value.toLowerCase();
    const selectedType = typeFilter.value;
    const selectedStatus = statusFilter.value;

    const filteredDocs = documents.filter(doc => {
        const matchesSearch = doc.title.toLowerCase().includes(query) || doc.municipality.toLowerCase().includes(query);
        const matchesType = selectedType === 'All' || doc.type === selectedType;
        const matchesStatus = selectedStatus === 'All' || doc.status === selectedStatus;
        return matchesSearch && matchesType && matchesStatus;
    });

    const totalPages = Math.ceil(filteredDocs.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const paginatedDocs = filteredDocs.slice(startIndex, startIndex + itemsPerPage);

    recordsContainer.innerHTML = '';
    
    if (paginatedDocs.length === 0) {
        emptyState.style.display = 'flex';
        recordsContainer.style.display = 'none';
    } else {
        emptyState.style.display = 'none';
        recordsContainer.style.display = 'block';

        paginatedDocs.forEach(doc => {
            const statusClass = doc.status === 'Completed' ? 'status-completed' : 
                                doc.status === 'Terminated' ? 'status-terminated' : 'status-ongoing';
            
            const row = `
            <div class="record-row">
                <div style="font-weight: 600; color: #0f172a;">${doc.title}</div>
                <div style="color: #475569;">${doc.municipality}</div>
                <div><span class="status-badge ${statusClass}">${doc.status}</span></div>
                <div style="color: #475569;">${doc.engineer}</div>
                <div style="font-weight: 600; color: #64748b;">${doc.type}</div>
                <div style="color: #475569; font-size: 13px;">${doc.date}</div>
                <div><span class="action-link" onclick="viewDocument('${doc.id}')">View</span></div>
            </div>`;
            recordsContainer.innerHTML += row;
        });
    }

    const endNum = Math.min(startIndex + itemsPerPage, filteredDocs.length);
    const startNum = filteredDocs.length === 0 ? 0 : startIndex + 1;
    document.getElementById('showingText').innerText = `SHOWING ${startNum}-${endNum} OF ${filteredDocs.length} PROJECTS`;

    renderPagination(totalPages);
}

function renderPagination(totalPages) {
    const container = document.getElementById('paginationControls');
    if (!container) return;
    
    container.innerHTML = '';
    
    const prevBtn = document.createElement('div');
    prevBtn.className = 'page-btn';
    prevBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
    prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderTable(); } };
    container.appendChild(prevBtn);

    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('div');
        btn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
        btn.innerText = i;
        btn.onclick = () => { currentPage = i; renderTable(); };
        container.appendChild(btn);
    }

    const nextBtn = document.createElement('div');
    nextBtn.className = 'page-btn';
    nextBtn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
    nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderTable(); } };
    container.appendChild(nextBtn);
}

renderTable();