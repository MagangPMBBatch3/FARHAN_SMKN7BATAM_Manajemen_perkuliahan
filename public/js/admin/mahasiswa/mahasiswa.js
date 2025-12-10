const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;

async function loadMahasiswaData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";

    // Query Data Aktif
    const queryAktif = `
    query($first: Int, $page: Int, $search: String) {
        allMahasiswaPaginate(first: $first, page: $page, search: $search) {
            data { 
                id 
                nim 
                nama_lengkap 
                jurusan {
                    id
                    nama_jurusan
                }
                angkatan 
                jenis_kelamin 
                status 
                semester_saat_ini 
                ipk 
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
    renderMahasiswaTable(dataAktif?.data?.allMahasiswaPaginate?.data || [], 'dataMahasiswa', true);

    // Query Data Arsip
    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        allMahasiswaArsip(first: $first, page: $page, search: $search) {
            data { 
                id 
                nim 
                nama_lengkap 
                jurusan {
                    id
                    nama_jurusan
                }
                angkatan 
                jenis_kelamin 
                status 
                semester_saat_ini 
                ipk 
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
    renderMahasiswaTable(dataArsip?.data?.allMahasiswaArsip?.data || [], 'dataMahasiswaArsip', false);

    // Update Pagination Info Aktif
    const pageInfoAktif = dataAktif?.data?.allMahasiswaPaginate?.paginatorInfo;
    if (pageInfoAktif) {
        const start = pageInfoAktif.perPage * (pageInfoAktif.currentPage - 1) + 1;
        const end = Math.min(pageInfoAktif.perPage * pageInfoAktif.currentPage, pageInfoAktif.total);
        
        document.getElementById("pageInfoAktif").innerHTML = `
            Menampilkan <span class="font-medium">${start}</span> sampai <span class="font-medium">${end}</span> dari <span class="font-medium">${pageInfoAktif.total}</span> hasil
        `;
        
        // Update all pagination buttons
        document.getElementById("prevBtnAktif").disabled = pageInfoAktif.currentPage <= 1;
        document.getElementById("nextBtnAktif").disabled = !pageInfoAktif.hasMorePages;
        
        if (document.getElementById("prevBtnAktifMobile")) {
            document.getElementById("prevBtnAktifMobile").disabled = pageInfoAktif.currentPage <= 1;
        }
        if (document.getElementById("nextBtnAktifMobile")) {
            document.getElementById("nextBtnAktifMobile").disabled = !pageInfoAktif.hasMorePages;
        }
    }

    // Update Pagination Info Arsip
    const pageInfoArsip = dataArsip?.data?.allMahasiswaArsip?.paginatorInfo;
    if (pageInfoArsip) {
        const start = pageInfoArsip.perPage * (pageInfoArsip.currentPage - 1) + 1;
        const end = Math.min(pageInfoArsip.perPage * pageInfoArsip.currentPage, pageInfoArsip.total);
        
        document.getElementById("pageInfoArsip").innerHTML = `
            Menampilkan <span class="font-medium">${start}</span> sampai <span class="font-medium">${end}</span> dari <span class="font-medium">${pageInfoArsip.total}</span> hasil
        `;
        
        // Update all pagination buttons
        document.getElementById("prevBtnArsip").disabled = pageInfoArsip.currentPage <= 1;
        document.getElementById("nextBtnArsip").disabled = !pageInfoArsip.hasMorePages;
        
        if (document.getElementById("prevBtnArsipMobile")) {
            document.getElementById("prevBtnArsipMobile").disabled = pageInfoArsip.currentPage <= 1;
        }
        if (document.getElementById("nextBtnArsipMobile")) {
            document.getElementById("nextBtnArsipMobile").disabled = !pageInfoArsip.hasMorePages;
        }
    }
}

function renderMahasiswaTable(mahasiswa, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';

    if (!mahasiswa.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-500 font-medium text-lg">Tidak ada data mahasiswa</p>
                        <p class="text-gray-400 text-sm mt-1">Data yang Anda cari tidak ditemukan</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    mahasiswa.forEach(item => {
        // Status Badge
        let statusBadge = '';
        switch(item.status?.toUpperCase()) {
            case 'AKTIF':
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>';
                break;
            case 'CUTI':
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Cuti</span>';
                break;
            case 'LULUS':
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Lulus</span>';
                break;
            case 'DO':
                statusBadge = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">DO</span>';
                break;
            default:
                statusBadge = `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${item.status || '-'}</span>`;
        }

        // Action Buttons
        let actions = '';
        if (isActive) {
            actions = `
                <div class="flex items-center justify-end gap-2">
                    <a href="/admin/mahasiswa_detail/${item.id}" 
                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                       title="Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    <button onclick="openEditModal(${item.id})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors"
                            title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="hapusMahasiswa(${item.id})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                            title="Arsipkan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </button>
                </div>
            `;
        } else {
            actions = `
                <div class="flex items-center justify-end gap-2">
                    <a href="/mahasiswa_detail/${item.id}" 
                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                       title="Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    <button onclick="restoreMahasiswa(${item.id})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                            title="Restore">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    <button onclick="forceDeleteMahasiswa(${item.id})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-700 transition-colors"
                            title="Hapus Permanen">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;
        }

        tbody.innerHTML += `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.nim}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.nama_lengkap}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${item.jurusan?.nama_jurusan || "-"}</td>     
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">${item.angkatan}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">${item.jenis_kelamin}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">${statusBadge}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">${item.semester_saat_ini}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-center">${item.ipk ? item.ipk.toFixed(2) : '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${item.no_hp || "-"}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${actions}</td>
            </tr>
        `;
    });
}

async function hapusMahasiswa(id) {
    if (!confirm('Pindahkan mahasiswa ini ke arsip?')) return;
    
    try {
        const mutation = `mutation { deleteMahasiswa(id: ${id}) { id } }`;
        await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });
        
        loadMahasiswaData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal mengarsipkan mahasiswa');
    }
}

async function restoreMahasiswa(id) {
    if (!confirm('Kembalikan mahasiswa ini dari arsip?')) return;
    
    try {
        const mutation = `mutation { restoreMahasiswa(id: ${id}) { id } }`;
        await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });
        
        loadMahasiswaData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal mengembalikan mahasiswa');
    }
}

async function forceDeleteMahasiswa(id) {
    if (!confirm('PERINGATAN: Hapus permanen? Data tidak bisa dikembalikan!')) return;
    
    try {
        const mutation = `mutation { forceDeleteMahasiswa(id: ${id}) { id } }`;
        await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });
        
        loadMahasiswaData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menghapus mahasiswa');
    }
}

// Search
async function searchMahasiswa() {
    loadMahasiswaData(1, 1);
}

// Pagination Aktif
function prevPageAktif() {
    if (currentPageAktif > 1) loadMahasiswaData(currentPageAktif - 1, currentPageArsip);
}

function nextPageAktif() {
    loadMahasiswaData(currentPageAktif + 1, currentPageArsip);
}       

// Pagination Arsip
function prevPageArsip() {
    if (currentPageArsip > 1) loadMahasiswaData(currentPageAktif, currentPageArsip - 1);
}

function nextPageArsip() {
    loadMahasiswaData(currentPageAktif, currentPageArsip + 1);
}

document.addEventListener("DOMContentLoaded", () => loadMahasiswaData());