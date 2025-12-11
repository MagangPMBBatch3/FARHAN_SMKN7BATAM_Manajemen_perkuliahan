const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;
let statsData = {
    total: 0,
    aktif: 0,
    cuti: 0,
    arsip: 0
};

async function loadDosenData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";

    // --- Query Data Aktif ---
    const queryAktif = `
    query($first: Int, $page: Int, $search: String) {
        allDosenPaginate(first: $first, page: $page, search: $search) {
            data { 
                id 
                nidn 
                nip
                nama_lengkap
                gelar_depan
                gelar_belakang
                jurusan {
                    id
                    nama_jurusan
                }
                jenis_kelamin
                status_kepegawaian
                jabatan
                status
                no_hp
            }
            paginatorInfo { currentPage lastPage total hasMorePages perPage }
        }
    }`;
    const variablesAktif = { first: perPageAktif, page: pageAktif, search: searchValue };

    const resAktif = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query: queryAktif, variables: variablesAktif })
    });
    const dataAktif = await resAktif.json();
    const dosenAktifList = dataAktif?.data?.allDosenPaginate?.data || [];
    renderDosenTable(dosenAktifList, 'dataDosen', true);

    // --- Query Data Arsip ---
    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        allDosenArsip(first: $first, page: $page, search: $search) {
            data { 
                id 
                nidn 
                nip
                nama_lengkap
                gelar_depan
                gelar_belakang
                jurusan {
                    id
                    nama_jurusan
                }
                jenis_kelamin
                status_kepegawaian
                jabatan
                status
                no_hp
            }
            paginatorInfo { currentPage lastPage total hasMorePages perPage }
        }
    }`;
    const variablesArsip = { first: perPageArsip, page: pageArsip, search: searchValue };

    const resArsip = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query: queryArsip, variables: variablesArsip })
    });
    const dataArsip = await resArsip.json();
    const dosenArsipList = dataArsip?.data?.allDosenArsip?.data || [];
    renderDosenTable(dosenArsipList, 'dosenArsip', false);

    // --- Update Stats Cards ---
    updateStatsCards(dosenAktifList, dataAktif?.data?.allDosenPaginate?.paginatorInfo?.total || 0, dataArsip?.data?.allDosenArsip?.paginatorInfo?.total || 0);

    // --- Update info pagination untuk Data Aktif ---
    const pageInfoAktif = dataAktif?.data?.allDosenPaginate?.paginatorInfo;
    if (pageInfoAktif) {
        const start = ((pageInfoAktif.currentPage - 1) * pageInfoAktif.perPage) + 1;
        const end = Math.min(pageInfoAktif.currentPage * pageInfoAktif.perPage, pageInfoAktif.total);
        
        document.getElementById("pageInfoAktif").innerHTML =
            `Menampilkan <span class="font-medium">${start}</span> sampai <span class="font-medium">${end}</span> dari <span class="font-medium">${pageInfoAktif.total}</span> hasil`;
        
        const prevBtns = ['prevBtnAktif', 'prevBtnAktifMobile'];
        const nextBtns = ['nextBtnAktif', 'nextBtnAktifMobile'];
        
        prevBtns.forEach(id => {
            const btn = document.getElementById(id);
            if (btn) btn.disabled = pageInfoAktif.currentPage <= 1;
        });
        
        nextBtns.forEach(id => {
            const btn = document.getElementById(id);
            if (btn) btn.disabled = !pageInfoAktif.hasMorePages;
        });
    }

    // --- Update info pagination untuk Data Arsip ---
    const pageInfoArsip = dataArsip?.data?.allDosenArsip?.paginatorInfo;
    if (pageInfoArsip) {
        const start = ((pageInfoArsip.currentPage - 1) * pageInfoArsip.perPage) + 1;
        const end = Math.min(pageInfoArsip.currentPage * pageInfoArsip.perPage, pageInfoArsip.total);
        
        document.getElementById("pageInfoArsip").innerHTML =
            `Menampilkan <span class="font-medium">${start}</span> sampai <span class="font-medium">${end}</span> dari <span class="font-medium">${pageInfoArsip.total}</span> hasil`;
        
        const prevBtns = ['prevBtnArsip', 'prevBtnArsipMobile'];
        const nextBtns = ['nextBtnArsip', 'nextBtnArsipMobile'];
        
        prevBtns.forEach(id => {
            const btn = document.getElementById(id);
            if (btn) btn.disabled = pageInfoArsip.currentPage <= 1;
        });
        
        nextBtns.forEach(id => {
            const btn = document.getElementById(id);
            if (btn) btn.disabled = !pageInfoArsip.hasMorePages;
        });
    }
}

function updateStatsCards(dosenList, totalAktif, totalArsip) {
    // Hitung statistik dari data aktif
    const dosenAktifStatus = dosenList.filter(d => d.status?.toUpperCase() === 'AKTIF').length;
    const dosenCutiStatus = dosenList.filter(d => d.status?.toUpperCase() === 'CUTI').length;
    
    statsData.total = totalAktif;
    statsData.aktif = dosenAktifStatus;
    statsData.cuti = dosenCutiStatus;
    statsData.arsip = totalArsip;
    
    // Update tampilan stats cards dengan animasi
    animateValue('totalDosen', 0, totalAktif, 500);
    animateValue('dosenAktif', 0, dosenAktifStatus, 500);
    animateValue('dosenCuti', 0, dosenCutiStatus, 500);
}

function animateValue(id, start, end, duration) {
    const element = document.getElementById(id);
    if (!element) return;
    
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            current = end;
            clearInterval(timer);
        }
        element.textContent = Math.round(current);
    }, 16);
}

function renderDosenTable(dosen, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    if (!tbody) return;
    
    tbody.innerHTML = '';

    if (!dosen.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">Tidak ada data dosen</p>
                        <p class="text-gray-400 text-sm mt-1">Belum ada data yang ditambahkan</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    dosen.forEach(item => {
        // Format nama dengan gelar
        let namaLengkap = item.nama_lengkap;
        if (item.gelar_depan || item.gelar_belakang) {
            namaLengkap = `${item.gelar_depan || ''} ${item.nama_lengkap} ${item.gelar_belakang || ''}`.trim();
        }

        // Badge untuk status
        let statusBadge = '';
        switch(item.status?.toUpperCase()) {
            case 'AKTIF':
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>Aktif</span>';
                break;
            case 'CUTI':
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><span class="w-2 h-2 bg-yellow-400 rounded-full mr-1.5"></span>Cuti</span>';
                break;
            case 'PENSIUN':
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><span class="w-2 h-2 bg-blue-400 rounded-full mr-1.5"></span>Pensiun</span>';
                break;
            case 'NONAKTIF':
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"><span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>Nonaktif</span>';
                break;
            default:
                statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${item.status || '-'}</span>`;
        }

        // Badge jenis kelamin
        const jkBadge = item.jenis_kelamin === 'L' 
            ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">L</span>'
            : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-pink-100 text-pink-800">P</span>';

        let actions = '';
        if (isActive) {
            actions = `
                <div class="flex items-center justify-end space-x-2">
                    <a href="/admin/dosen_detail/${item.id}" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Detail
                    </a>
                    <button onclick="openEditModal(${item.id})" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </button>
                    <button onclick="hapusDosen(${item.id})" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Arsip
                    </button>
                </div>
            `;
        } else {
            actions = `
                <div class="flex items-center justify-end space-x-2">
                    <a href="/admin/dosen_detail/${item.id}" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Detail
                    </a>
                    <button onclick="restoreDosen(${item.id})" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Restore
                    </button>
                    <button onclick="forceDeleteDosen(${item.id})" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Hapus
                    </button>
                </div>
            `;
        }

        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50 transition-colors duration-150';
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.nidn || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.nip || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold">
                            ${item.nama_lengkap.charAt(0).toUpperCase()}
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${namaLengkap}</div>
                        ${item.gelar_depan || item.gelar_belakang ? `<div class="text-xs text-gray-500">${item.gelar_depan || ''} ${item.gelar_belakang || ''}</div>` : ''}
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.jurusan?.nama_jurusan || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">${jkBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.status_kepegawaian || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.jabatan || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-center">${statusBadge}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${item.no_hp ? `<a href="tel:${item.no_hp}" class="text-blue-600 hover:text-blue-800">${item.no_hp}</a>` : '-'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">${actions}</td>
        `;
        
        tbody.appendChild(row);
    });
}

// --- Mutations ---
async function hapusDosen(id) {
    if (!confirm('Pindahkan dosen ini ke arsip?')) return;
    
    const mutation = `mutation { deleteDosen(id: ${id}) { id } }`;
    
    try {
        await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });
        
        showNotification('Dosen berhasil diarsipkan', 'success');
        loadDosenData(currentPageAktif, currentPageArsip);
    } catch (error) {
        showNotification('Gagal mengarsipkan dosen', 'error');
    }
}

async function restoreDosen(id) {
    if (!confirm('Kembalikan dosen ini dari arsip?')) return;
    
    const mutation = `mutation { restoreDosen(id: ${id}) { id } }`;
    
    try {
        await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });
        
        showNotification('Dosen berhasil dikembalikan', 'success');
        loadDosenData(currentPageAktif, currentPageArsip);
    } catch (error) {
        showNotification('Gagal mengembalikan dosen', 'error');
    }
}

async function forceDeleteDosen(id) {
    if (!confirm('PERINGATAN: Hapus permanen? Data tidak bisa dikembalikan!')) return;
    
    const mutation = `mutation { forceDeleteDosen(id: ${id}) { id } }`;
    
    try {
        await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });
        
        showNotification('Dosen berhasil dihapus permanen', 'success');
        loadDosenData(currentPageAktif, currentPageArsip);
    } catch (error) {
        showNotification('Gagal menghapus dosen', 'error');
    }
}

// Simple notification helper
function showNotification(message, type = 'success') {
    // You can implement a toast notification here
    // For now, using alert
    if (type === 'success') {
        // Show success message
        console.log('Success:', message);
    } else {
        console.error('Error:', message);
    }
}

// --- Search ---
let searchTimeout;
async function searchDosen() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadDosenData(1, 1);
    }, 300); // Debounce 300ms
}

// --- Pagination untuk Data Aktif ---
function prevPageAktif() {
    if (currentPageAktif > 1) loadDosenData(currentPageAktif - 1, currentPageArsip);
}

function nextPageAktif() {
    loadDosenData(currentPageAktif + 1, currentPageArsip);
}

// --- Pagination untuk Data Arsip ---
function prevPageArsip() {
    if (currentPageArsip > 1) loadDosenData(currentPageAktif, currentPageArsip - 1);
}

function nextPageArsip() {
    loadDosenData(currentPageAktif, currentPageArsip + 1);
}

document.addEventListener("DOMContentLoaded", () => {
    loadDosenData();
});