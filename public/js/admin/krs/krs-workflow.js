// ==================== KRS WORKFLOW MANAGEMENT SYSTEM ====================
// File: krs-workflow.js

/**
 * Status Flow KRS:
 * Draft → Diajukan → Disetujui/Ditolak
 * 
 * Jika Ditolak:
 * - Mahasiswa bisa edit KRS
 * - Status kembali ke "Draft" atau langsung "Diajukan"
 * - Bisa diajukan ulang
 */

const KRS_STATUS = {
    DRAFT: 'Draft',
    DIAJUKAN: 'Diajukan',
    DISETUJUI: 'Disetujui',
    DITOLAK: 'Ditolak'
};

const STATUS_COLORS = {
    'Draft': 'gray',
    'Diajukan': 'blue',
    'Disetujui': 'green',
    'Ditolak': 'red'
};

/**
 * Cek apakah KRS bisa diedit oleh mahasiswa
 */
function canEditKrs(status) {
    return status === KRS_STATUS.DRAFT || status === KRS_STATUS.DITOLAK;
}

/**
 * Cek apakah KRS bisa diajukan
 */
function canSubmitKrs(status, totalSks) {
    return (status === KRS_STATUS.DRAFT || status === KRS_STATUS.DITOLAK) && totalSks > 0;
}

/**
 * Cek apakah KRS bisa dibatalkan (dari Diajukan kembali ke Draft)
 */
function canCancelSubmission(status) {
    return status === KRS_STATUS.DIAJUKAN;
}

/**
 * Render tombol aksi berdasarkan status KRS
 */
function renderKrsActionButtons(krs, userRole = 'mahasiswa') {
    const actions = [];
    
    if (userRole === 'mahasiswa') {
        // MAHASISWA ACTIONS
        
        // Detail button (selalu tampil)
        actions.push(`
            <a href="/mahasiswa/krs-detail/${krs.id}" 
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors"
                title="Lihat Detail">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span class="ml-1.5">Detail</span>
            </a>
        `);
        
        // Edit button (hanya untuk Draft dan Ditolak)
        if (canEditKrs(krs.status)) {
            actions.push(`
                <a href="/mahasiswa/krs-detail/${krs.id}" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 transition-colors"
                    title="Edit KRS">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="ml-1.5">Edit</span>
                </a>
            `);
        }
        
        // Submit button (untuk Draft dan Ditolak dengan SKS > 0)
        if (canSubmitKrs(krs.status, krs.total_sks)) {
            const buttonText = krs.status === KRS_STATUS.DITOLAK ? 'Ajukan Ulang' : 'Ajukan KRS';
            actions.push(`
                <button onclick="submitKrsForApproval(${krs.id})" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors"
                    title="${buttonText}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="ml-1.5">${buttonText}</span>
                </button>
            `);
        }
        
        // Cancel submission button (untuk status Diajukan)
        if (canCancelSubmission(krs.status)) {
            actions.push(`
                <button onclick="cancelKrsSubmission(${krs.id})" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 transition-colors"
                    title="Batalkan Pengajuan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="ml-1.5">Batalkan</span>
                </button>
            `);
        }
        
    } else if (userRole === 'admin' || userRole === 'dosen') {
        // ADMIN/DOSEN ACTIONS
        
        // Detail button
        actions.push(`
            <a href="/admin/krs-detail/${krs.id}" 
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors"
                title="Detail">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>
        `);
        
        // Approve button (hanya untuk status Diajukan)
        if (krs.status === KRS_STATUS.DIAJUKAN) {
            actions.push(`
                <button onclick="approveKrs(${krs.id})" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors"
                    title="Setujui KRS">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </button>
            `);
            
            actions.push(`
                <button onclick="rejectKrs(${krs.id})" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 transition-colors"
                    title="Tolak KRS">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `);
        }
        
        // Edit button (untuk semua status kecuali Disetujui)
        if (krs.status !== KRS_STATUS.DISETUJUI) {
            actions.push(`
                <button onclick="openEditModal(${krs.id})" 
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 transition-colors"
                    title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
            `);
        }
        
        // Archive button
        actions.push(`
            <button onclick="hapusKrs(${krs.id})" 
                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-red-500 hover:bg-red-600 transition-colors"
                title="Arsipkan">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </button>
        `);
    }
    
    return `
        <div class="flex items-center justify-end gap-2">
            ${actions.join('')}
        </div>
    `;
}

/**
 * Mahasiswa mengajukan KRS untuk persetujuan
 */
async function submitKrsForApproval(krsId) {
    const confirmed = confirm(
        'Ajukan KRS untuk persetujuan?\n\n' +
        'Setelah diajukan, KRS tidak dapat diedit sampai disetujui atau ditolak oleh Dosen PA.'
    );
    
    if (!confirmed) return;
    
    try {
        const mutation = `
        mutation {
            updateKrs(id: ${krsId}, input: {
                status: "${KRS_STATUS.DIAJUKAN}"
            }) {
                id
                status
            }
        }`;
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('KRS berhasil diajukan! Silakan tunggu persetujuan dari Dosen PA.');
        
        // Reload data
        if (typeof loadKrsData === 'function') {
            loadKrsData(currentPageAktif, currentPageArsip);
        } else if (typeof loadMahasiswaKrsData === 'function') {
            loadMahasiswaKrsData();
        }
        
    } catch (error) {
        console.error('Error submitting KRS:', error);
        alert('Gagal mengajukan KRS: ' + error.message);
    }
}

/**
 * Mahasiswa membatalkan pengajuan KRS (kembali ke Draft)
 */
async function cancelKrsSubmission(krsId) {
    const confirmed = confirm(
        'Batalkan pengajuan KRS?\n\n' +
        'KRS akan kembali ke status Draft dan dapat diedit kembali.'
    );
    
    if (!confirmed) return;
    
    try {
        const mutation = `
        mutation {
            updateKrs(id: ${krsId}, input: {
                status: "${KRS_STATUS.DRAFT}"
                tanggal_persetujuan: null
            }) {
                id
                status
            }
        }`;
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('Pengajuan KRS berhasil dibatalkan. Status kembali ke Draft.');
        
        // Reload data
        if (typeof loadKrsData === 'function') {
            loadKrsData(currentPageAktif, currentPageArsip);
        } else if (typeof loadMahasiswaKrsData === 'function') {
            loadMahasiswaKrsData();
        }
        
    } catch (error) {
        console.error('Error canceling KRS submission:', error);
        alert('Gagal membatalkan pengajuan: ' + error.message);
    }
}

/**
 * Dosen/Admin menyetujui KRS
 */
async function approveKrs(krsId) {
    // Buka modal untuk input catatan persetujuan (opsional)
    openApprovalModal(krsId, 'approve');
}

/**
 * Dosen/Admin menolak KRS
 */
async function rejectKrs(krsId) {
    // Buka modal untuk input alasan penolakan (wajib)
    openApprovalModal(krsId, 'reject');
}

/**
 * Open modal untuk approval/rejection dengan catatan
 */
function openApprovalModal(krsId, action) {
    const modal = document.getElementById('modalApproval');
    if (!modal) {
        // Fallback jika modal tidak ada
        if (action === 'approve') {
            processApproval(krsId, '');
        } else {
            const reason = prompt('Masukkan alasan penolakan:');
            if (reason) {
                processRejection(krsId, reason);
            }
        }
        return;
    }
    
    // Set modal title dan placeholder
    const title = action === 'approve' ? 'Setujui KRS' : 'Tolak KRS';
    const placeholder = action === 'approve' 
        ? 'Catatan persetujuan (opsional)...' 
        : 'Alasan penolakan (wajib)...';
    
    document.getElementById('approvalTitle').textContent = title;
    document.getElementById('approvalCatatan').placeholder = placeholder;
    document.getElementById('approvalCatatan').value = '';
    document.getElementById('approvalKrsId').value = krsId;
    document.getElementById('approvalAction').value = action;
    
    modal.classList.remove('hidden');
}

function closeApprovalModal() {
    const modal = document.getElementById('modalApproval');
    if (modal) {
        modal.classList.add('hidden');
    }
}

/**
 * Process approval/rejection
 */
async function processApprovalAction() {
    const krsId = document.getElementById('approvalKrsId').value;
    const action = document.getElementById('approvalAction').value;
    const catatan = document.getElementById('approvalCatatan').value;
    
    if (action === 'approve') {
        await processApproval(krsId, catatan);
    } else {
        if (!catatan.trim()) {
            alert('Alasan penolakan harus diisi!');
            return;
        }
        await processRejection(krsId, catatan);
    }
}

async function processApproval(krsId, catatan) {
    try {
        const today = new Date().toISOString().split('T')[0];
        
        const mutation = `
        mutation {
            updateKrs(id: ${krsId}, input: {
                status: "${KRS_STATUS.DISETUJUI}"
                tanggal_persetujuan: "${today}"
                catatan: "${catatan.replace(/"/g, '\\"')}"
            }) {
                id
                status
                tanggal_persetujuan
            }
        }`;
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('KRS berhasil disetujui!');
        closeApprovalModal();
        
        // Reload data
        if (typeof loadKrsData === 'function') {
            loadKrsData(currentPageAktif, currentPageArsip);
        }
        
        // TODO: Send notification to mahasiswa
        
    } catch (error) {
        console.error('Error approving KRS:', error);
        alert('Gagal menyetujui KRS: ' + error.message);
    }
}

async function processRejection(krsId, reason) {
    try {
        const mutation = `
        mutation {
            updateKrs(id: ${krsId}, input: {
                status: "${KRS_STATUS.DITOLAK}"
                catatan: "DITOLAK: ${reason.replace(/"/g, '\\"')}"
            }) {
                id
                status
            }
        }`;
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('KRS berhasil ditolak. Mahasiswa dapat mengedit dan mengajukan ulang.');
        closeApprovalModal();
        
        // Reload data
        if (typeof loadKrsData === 'function') {
            loadKrsData(currentPageAktif, currentPageArsip);
        }
        
        // TODO: Send notification to mahasiswa
        
    } catch (error) {
        console.error('Error rejecting KRS:', error);
        alert('Gagal menolak KRS: ' + error.message);
    }
}

/**
 * Render status badge dengan info tambahan
 */
function renderStatusBadge(krs) {
    const status = krs.status;
    const color = STATUS_COLORS[status] || 'gray';
    
    let icon = '';
    let helpText = '';
    
    switch (status) {
        case KRS_STATUS.DRAFT:
            icon = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>`;
            helpText = 'KRS masih bisa diedit. Ajukan untuk persetujuan.';
            break;
        case KRS_STATUS.DIAJUKAN:
            icon = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>`;
            helpText = 'Menunggu persetujuan Dosen PA.';
            break;
        case KRS_STATUS.DISETUJUI:
            icon = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>`;
            helpText = 'KRS sudah disetujui.';
            break;
        case KRS_STATUS.DITOLAK:
            icon = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>`;
            helpText = 'KRS ditolak. Edit dan ajukan kembali.';
            break;
    }
    
    return `
        <div class="inline-flex items-center gap-1.5 group relative">
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-${color}-100 text-${color}-800">
                ${icon}
                ${status}
            </span>
            ${krs.status === KRS_STATUS.DITOLAK && krs.catatan ? `
                <svg class="w-4 h-4 text-${color}-600 cursor-help" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="hidden group-hover:block absolute bottom-full left-0 mb-2 w-64 p-2 bg-gray-900 text-white text-xs rounded shadow-lg z-10">
                    ${krs.catatan}
                </div>
            ` : ''}
        </div>
    `;
}

/**
 * Show notification alert untuk status KRS
 */
function showKrsStatusAlert(krs) {
    if (krs.status === KRS_STATUS.DITOLAK && krs.catatan) {
        return `
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">KRS Ditolak</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>${krs.catatan}</p>
                        </div>
                        <div class="mt-4">
                            <button onclick="window.location.href='/mahasiswa/krs-detail/${krs.id}'" 
                                class="text-sm font-medium text-red-800 hover:text-red-900">
                                Edit KRS →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    if (krs.status === KRS_STATUS.DRAFT && krs.total_sks > 0) {
        return `
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">KRS Belum Diajukan</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>KRS Anda masih berstatus Draft. Ajukan untuk mendapatkan persetujuan Dosen PA.</p>
                        </div>
                        <div class="mt-4">
                            <button onclick="submitKrsForApproval(${krs.id})" 
                                class="text-sm font-medium text-yellow-800 hover:text-yellow-900">
                                Ajukan Sekarang →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    return '';
}