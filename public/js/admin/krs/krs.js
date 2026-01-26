// ==================== OPTIMIZED KRS MAIN SCRIPT ====================
// File: krs-main.js

const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;

// Cache untuk menyimpan data fakultas, semester, dosen
const dataCache = {
    fakultas: null,
    semester: null,
    dosen: null,
    lastFetch: {}
};

// Debounce function untuk search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ==================== OPTIMIZED DATA LOADING ====================

/**
 * Load KRS data dengan loading state
 */
async function loadKrsData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";

    // Show loading skeleton
    showTableLoading('dataKrs', 5, 9);
    showTableLoading('dataKrsArsip', 5, 9);

    try {
        // Paralel fetch untuk performa lebih baik
        const [dataAktif, dataArsip] = await Promise.all([
            fetchKrsAktif(pageAktif, perPageAktif, searchValue),
            fetchKrsArsip(pageArsip, perPageArsip, searchValue)
        ]);

        // Render data aktif
        if (dataAktif.errors) {
            console.error('GraphQL Errors (Aktif):', dataAktif.errors);
            document.getElementById('dataKrs').innerHTML = getErrorState('Gagal memuat data aktif', 9);
        } else {
            renderKrsTable(dataAktif?.data?.allKrsPaginate?.data || [], 'dataKrs', true);
            updatePaginationInfo(dataAktif?.data?.allKrsPaginate?.paginatorInfo, 'Aktif');
        }

        // Render data arsip
        if (dataArsip.errors) {
            console.error('GraphQL Errors (Arsip):', dataArsip.errors);
            document.getElementById('dataKrsArsip').innerHTML = getErrorState('Gagal memuat data arsip', 9);
        } else {
            renderKrsTable(dataArsip?.data?.allKrsArsip?.data || [], 'dataKrsArsip', false);
            updatePaginationInfo(dataArsip?.data?.allKrsArsip?.paginatorInfo, 'Arsip');
        }

    } catch (error) {
        console.error('Error loading KRS data:', error);
        document.getElementById('dataKrs').innerHTML = getErrorState('Terjadi kesalahan saat memuat data', 9);
        document.getElementById('dataKrsArsip').innerHTML = getErrorState('Terjadi kesalahan saat memuat data', 9);
    }
}

/**
 * Fetch data KRS aktif
 */
async function fetchKrsAktif(page, perPage, search) {
    const query = `
    query($first: Int, $page: Int, $search: String) {
        allKrsPaginate(first: $first, page: $page, search: $search) {
            data { 
                id 
                mahasiswa { id nama_lengkap nim }
                semester { id nama_semester kode_semester }
                tanggal_pengisian 
                tanggal_persetujuan 
                status 
                total_sks 
                catatan 
                dosenPa { id nama_lengkap nidn }
            }
            paginatorInfo { currentPage lastPage total hasMorePages perPage }
        }
    }`;

    const response = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            query, 
            variables: { first: perPage, page, search }
        })
    });

    return await response.json();
}

/**
 * Fetch data KRS arsip
 */
async function fetchKrsArsip(page, perPage, search) {
    const query = `
    query($first: Int, $page: Int, $search: String) {
        allKrsArsip(first: $first, page: $page, search: $search) {
            data { 
                id 
                mahasiswa { id nama_lengkap nim }
                semester { id nama_semester kode_semester }
                tanggal_pengisian 
                tanggal_persetujuan 
                status 
                total_sks 
                catatan 
                dosenPa { id nama_lengkap nidn }
            }
            paginatorInfo { currentPage lastPage total hasMorePages perPage }
        }
    }`;

    const response = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            query, 
            variables: { first: perPage, page, search }
        })
    });

    return await response.json();
}

/**
 * Update pagination info
 */
function updatePaginationInfo(pageInfo, type) {
    if (!pageInfo) return;

    const infoElement = document.getElementById(`pageInfo${type}`);
    const prevBtn = document.getElementById(`prevBtn${type}`);
    const nextBtn = document.getElementById(`nextBtn${type}`);

    if (infoElement) {
        infoElement.innerText = `Halaman ${pageInfo.currentPage} dari ${pageInfo.lastPage} (Total: ${pageInfo.total})`;
    }

    if (prevBtn) prevBtn.disabled = pageInfo.currentPage <= 1;
    if (nextBtn) nextBtn.disabled = !pageInfo.hasMorePages;
}

/**
 * Render tabel KRS dengan optimasi
 */
function renderKrsTable(krs, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    
    if (!krs || krs.length === 0) {
        tbody.innerHTML = getEmptyState('Tidak ada data KRS', 9);
        return;
    }

    // Build HTML string sekali jalan (lebih cepat dari innerHTML +=)
    const rows = krs.map(item => {
        const actions = isActive ? getActiveActions(item.id) : getArchiveActions(item.id);
        const tanggalPengisian = formatDate(item.tanggal_pengisian);
        const tanggalPersetujuan = item.tanggal_persetujuan ? formatDate(item.tanggal_persetujuan) : 'Belum Disetujui';
        const statusBadge = getStatusBadge(item.status);

        return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${item.mahasiswa?.nama_lengkap || '-'}
                    <div class="text-xs text-gray-500">${item.mahasiswa?.nim || '-'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${item.semester?.nama_semester || '-'}
                    <div class="text-xs text-gray-500">${item.semester?.kode_semester || '-'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tanggalPengisian}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tanggalPersetujuan}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${statusBadge}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.total_sks || 0}</td>
                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="${item.catatan || ''}">${item.catatan || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${item.dosenPa?.nama_lengkap || '-'}
                    ${item.dosenPa?.nidn ? `<div class="text-xs text-gray-500">NIDN: ${item.dosenPa.nidn}</div>` : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${actions}</td>
            </tr>
        `;
    }).join('');

    tbody.innerHTML = rows;
}

/**
 * Get action buttons untuk data aktif
 */
function getActiveActions(id) {
    return `
        <div class="flex items-center justify-end space-x-2">
            <a href="/admin/krs-detail/${id}" 
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                title="Detail">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>
            <button onclick="hapusKrs(${id})" 
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                title="Arsipkan">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </button>
        </div>
    `;
}

/**
 * Get action buttons untuk data arsip
 */
function getArchiveActions(id) {
    return `
        <div class="flex items-center justify-end gap-2">
            <button onclick="restoreKrs(${id})" 
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                title="Restore">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
            <button onclick="forceDeleteKrs(${id})" 
                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-700 transition-colors"
                title="Hapus Permanen">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `;
}

/**
 * Get status badge
 */
function getStatusBadge(status) {
    const badges = {
        'Draft': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>',
        'Diajukan': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Diajukan</span>',
        'Disetujui': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>',
        'Ditolak': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>'
    };
    return badges[status] || `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${status}</span>`;
}

/**
 * Format tanggal
 */
function formatDate(dateString) {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// ==================== MUTATIONS ====================

async function hapusKrs(id) {
    if (!confirm('Pindahkan KRS ini ke arsip?')) return;
    
    try {
        const mutation = `mutation { deleteKrs(id: ${id}) { id } }`;
        const response = await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });
        
        const result = await response.json();
        
        if (result.errors) {
            alert('Gagal mengarsipkan: ' + result.errors[0].message);
            return;
        }
        
        await loadKrsData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengarsipkan KRS');
    }
}

async function restoreKrs(id) {
    if (!confirm('Kembalikan KRS ini dari arsip?')) return;
    
    try {
        const mutation = `mutation { restoreKrs(id: ${id}) { id } }`;
        const response = await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });
        
        const result = await response.json();
        
        if (result.errors) {
            alert('Gagal restore: ' + result.errors[0].message);
            return;
        }
        
        await loadKrsData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat restore KRS');
    }
}

async function forceDeleteKrs(id) {
    if (!confirm('Hapus permanen KRS ini? Data tidak bisa dikembalikan!')) return;
    
    try {
        const mutation = `mutation { forceDeleteKrs(id: ${id}) { id } }`;
        const response = await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });
        
        const result = await response.json();
        
        if (result.errors) {
            alert('Gagal menghapus: ' + result.errors[0].message);
            return;
        }
        
        await loadKrsData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus KRS');
    }
}

// ==================== SEARCH & PAGINATION ====================

const searchKrs = debounce(() => {
    loadKrsData(1, 1);
}, 500);

function prevPageAktif() {
    if (currentPageAktif > 1) loadKrsData(currentPageAktif - 1, currentPageArsip);
}

function nextPageAktif() {
    loadKrsData(currentPageAktif + 1, currentPageArsip);
}

function prevPageArsip() {
    if (currentPageArsip > 1) loadKrsData(currentPageAktif, currentPageArsip - 1);
}

function nextPageArsip() {
    loadKrsData(currentPageAktif, currentPageArsip + 1);
}

// ==================== INITIALIZATION ====================

document.addEventListener("DOMContentLoaded", () => {
    loadKrsData();
    
    // Setup search event listener
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', searchKrs);
    }
});