const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;

async function loadKrsData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";

    // --- Query Data Aktif ---
    const queryAktif = `
    query($first: Int, $page: Int, $search: String) {
        allKrsPaginate(first: $first, page: $page, search: $search) {
            data { 
                id 
                mahasiswa_id
                semester_id
                tanggal_pengisian 
                tanggal_persetujuan 
                status 
                total_sks 
                catatan 
                dosen_pa_id
                mahasiswa {
                    id 
                    nama_lengkap
                    nim
                }
                semester {
                    id 
                    nama_semester
                    kode_semester
                }
                dosenPa {
                    id 
                    nama_lengkap
                    nidn
                }
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
    
    if (dataAktif.errors) {
        console.error('GraphQL Errors:', dataAktif.errors);
        renderKrsTable([], 'dataKrs', true);
    } else {
        renderKrsTable(dataAktif?.data?.allKrsPaginate?.data || [], 'dataKrs', true);
    }

    // --- Query Data Arsip ---
    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        allKrsArsip(first: $first, page: $page, search: $search) {
            data { 
                id 
                mahasiswa_id
                semester_id
                tanggal_pengisian 
                tanggal_persetujuan 
                status 
                total_sks 
                catatan 
                dosen_pa_id
                mahasiswa {
                    id 
                    nama_lengkap
                    nim
                }
                semester {
                    id 
                    nama_semester
                    kode_semester
                }
                dosenPa {
                    id 
                    nama_lengkap
                    nidn
                }
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
    
    if (dataArsip.errors) {
        console.error('GraphQL Errors:', dataArsip.errors);
        renderKrsTable([], 'dataKrsArsip', false);
    } else {
        renderKrsTable(dataArsip?.data?.allKrsArsip?.data || [], 'dataKrsArsip', false);
    }

    // --- Update info pagination untuk Data Aktif ---
    const pageInfoAktif = dataAktif?.data?.allKrsPaginate?.paginatorInfo;
    if (pageInfoAktif) {
        document.getElementById("pageInfoAktif").innerText =
            `Halaman ${pageInfoAktif.currentPage} dari ${pageInfoAktif.lastPage} (Total: ${pageInfoAktif.total})`;
        document.getElementById("prevBtnAktif").disabled = pageInfoAktif.currentPage <= 1;
        document.getElementById("nextBtnAktif").disabled = !pageInfoAktif.hasMorePages;
    }

    // --- Update info pagination untuk Data Arsip ---
    const pageInfoArsip = dataArsip?.data?.allKrsArsip?.paginatorInfo;
    if (pageInfoArsip) {
        document.getElementById("pageInfoArsip").innerText =
            `Halaman ${pageInfoArsip.currentPage} dari ${pageInfoArsip.lastPage} (Total: ${pageInfoArsip.total})`;
        document.getElementById("prevBtnArsip").disabled = pageInfoArsip.currentPage <= 1;
        document.getElementById("nextBtnArsip").disabled = !pageInfoArsip.hasMorePages;
    }
}

function renderKrsTable(krs, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';
    
    if (!krs.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center text-gray-500 p-3">Tidak ada data</td>
            </tr>
        `;
        return;
    }

    krs.forEach(item => {
        let actions = '';
        if (isActive) {
            actions = `
                <div class="flex items-center justify-end space-x-2">
                    <a href="/admin/krs-detail/${item.id}" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                        title="Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    <button onclick="openEditModal(${item.id})" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200"
                        title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="hapusKrs(${item.id})" 
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
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
                    <button onclick="restoreKrs(${item.id})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                            title="Restore">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    <button onclick="forceDeleteKrs(${item.id})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-700 transition-colors"
                            title="Hapus Permanen">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;
        }

        // Format tanggal
        const tanggalPengisian = item.tanggal_pengisian 
            ? new Date(item.tanggal_pengisian).toLocaleDateString('id-ID')
            : '-';
        const tanggalPersetujuan = item.tanggal_persetujuan 
            ? new Date(item.tanggal_persetujuan).toLocaleDateString('id-ID')
            : 'Belum Disetujui';

        // Status badge
        const statusBadge = getStatusBadge(item.status);

        tbody.innerHTML += `
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
                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">${item.catatan || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${item.dosenPa?.nama_lengkap || '-'}
                    ${item.dosenPa?.nidn ? `<div class="text-xs text-gray-500">NIDN: ${item.dosenPa.nidn}</div>` : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${actions}</td>
            </tr>
        `;
    });
}

function getStatusBadge(status) {
    const badges = {
        'Draft': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>',
        'Diajukan': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Diajukan</span>',
        'Disetujui': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>',
        'Ditolak': '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>'
    };
    return badges[status] || `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">${status}</span>`;
}

// --- Mutations ---
async function hapusKrs(id) {
    if (!confirm('Pindahkan ke arsip?')) return;
    const mutation = `mutation { deleteKrs(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadKrsData(currentPageAktif, currentPageArsip);
}

async function restoreKrs(id) {
    if (!confirm('Kembalikan dari arsip?')) return;
    const mutation = `mutation { restoreKrs(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadKrsData(currentPageAktif, currentPageArsip);
}

async function forceDeleteKrs(id) {
    if (!confirm('Hapus permanen? Data tidak bisa dikembalikan')) return;
    const mutation = `mutation { forceDeleteKrs(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadKrsData(currentPageAktif, currentPageArsip);
}

// --- Search ---
async function searchKrs() {
    loadKrsData(1, 1);
}

// --- Pagination ---
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

document.addEventListener("DOMContentLoaded", () => loadKrsData());