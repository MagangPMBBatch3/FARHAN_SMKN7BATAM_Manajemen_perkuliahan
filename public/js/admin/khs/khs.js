const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;

async function loadKhsData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";

    // --- Query Data Aktif ---
    const queryAktif = `
    query($first: Int, $page: Int, $search: String) {
        allKhsPaginate(first: $first, page: $page, search: $search) {
            data { 
                id 
                mahasiswa {
                    id 
                    nim
                    nama_lengkap
                } 
                semester {
                    id 
                    nama_semester
                } 
                sks_semester 
                sks_kumulatif 
                ip_semester 
                ipk 
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
    renderKhsTable(dataAktif?.data?.allKhsPaginate?.data || [], 'dataKhs', true);

    // --- Query Data Arsip ---
    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        allKhsArsip(first: $first, page: $page, search: $search) {
            data { 
                id 
                mahasiswa {
                    id
                    nim
                    nama_lengkap
                } 
                semester {
                    id 
                    nama_semester
                } 
                sks_semester 
                sks_kumulatif 
                ip_semester 
                ipk 
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
    renderKhsTable(dataArsip?.data?.allKhsArsip?.data || [], 'dataKhsArsip', false);

    // --- Update pagination info ---
    const pageInfoAktif = dataAktif?.data?.allKhsPaginate?.paginatorInfo;
    if (pageInfoAktif) {
        document.getElementById("pageInfoAktif").innerText =
            `Halaman ${pageInfoAktif.currentPage} dari ${pageInfoAktif.lastPage} (Total: ${pageInfoAktif.total})`;
        document.getElementById("prevBtnAktif").disabled = pageInfoAktif.currentPage <= 1;
        document.getElementById("nextBtnAktif").disabled = !pageInfoAktif.hasMorePages;
    }

    const pageInfoArsip = dataArsip?.data?.allKhsArsip?.paginatorInfo;
    if (pageInfoArsip) {
        document.getElementById("pageInfoArsip").innerText =
            `Halaman ${pageInfoArsip.currentPage} dari ${pageInfoArsip.lastPage} (Total: ${pageInfoArsip.total})`;
        document.getElementById("prevBtnArsip").disabled = pageInfoArsip.currentPage <= 1;
        document.getElementById("nextBtnArsip").disabled = !pageInfoArsip.hasMorePages;
    }
}

function renderKhsTable(khs, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';
    
    if (!khs.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-gray-500 p-3">Tidak ada data</td>
            </tr>
        `;
        return;
    }

    khs.forEach(item => {
        const ipk = parseFloat(item.ipk);
        const ipkColor = getIPKColor(ipk);
        
        let actions = '';
        if (isActive) {
            actions = `
                <div class="flex items-center justify-end gap-2">
                    <button onclick="openDetailModal(${item.id})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                            title="Detail KHS">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    <button onclick="openEditModal(${item.id}, ${item.mahasiswa.id}, ${item.semester.id}, ${item.sks_semester}, ${item.sks_kumulatif}, ${item.ip_semester}, ${item.ipk})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors"
                            title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="hapusKhs(${item.id})" 
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
                    <button onclick="restoreKhs(${item.id})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                            title="Restore">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    <button onclick="forceDeleteKhs(${item.id})" 
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
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${item.mahasiswa.nama_lengkap}</div>
                    <div class="text-xs text-gray-500">${item.mahasiswa.nim}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.semester.nama_semester}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold text-gray-900">${item.sks_semester || "-"}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold text-blue-600">${item.sks_kumulatif}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    <span class="px-3 py-1 rounded-full text-sm font-bold ${getIPColor(parseFloat(item.ip_semester))}">
                        ${parseFloat(item.ip_semester).toFixed(2)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    <span class="px-3 py-1 rounded-full text-sm font-bold ${ipkColor}">
                        ${ipk.toFixed(2)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${actions}</td>
            </tr>
        `;
    });
}

function getIPColor(ip) {
    if (ip >= 3.50) return 'bg-green-100 text-green-800';
    if (ip >= 3.00) return 'bg-blue-100 text-blue-800';
    if (ip >= 2.75) return 'bg-yellow-100 text-yellow-800';
    if (ip >= 2.00) return 'bg-orange-100 text-orange-800';
    return 'bg-red-100 text-red-800';
}

function getIPKColor(ipk) {
    if (ipk >= 3.75) return 'bg-green-100 text-green-800 border-2 border-green-400';
    if (ipk >= 3.50) return 'bg-blue-100 text-blue-800 border-2 border-blue-400';
    if (ipk >= 3.00) return 'bg-cyan-100 text-cyan-800 border-2 border-cyan-400';
    if (ipk >= 2.75) return 'bg-yellow-100 text-yellow-800 border-2 border-yellow-400';
    if (ipk >= 2.00) return 'bg-orange-100 text-orange-800 border-2 border-orange-400';
    return 'bg-red-100 text-red-800 border-2 border-red-400';
}

// --- Mutations ---
async function hapusKhs(id) {
    if (!confirm('Pindahkan ke arsip?')) return;
    const mutation = `mutation { deleteKhs(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadKhsData(currentPageAktif, currentPageArsip);
}

async function restoreKhs(id) {
    if (!confirm('Kembalikan dari arsip?')) return;
    const mutation = `mutation { restoreKhs(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadKhsData(currentPageAktif, currentPageArsip);
}

async function forceDeleteKhs(id) {
    if (!confirm('Hapus permanen? Data tidak bisa dikembalikan')) return;
    const mutation = `mutation { forceDeleteKhs(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadKhsData(currentPageAktif, currentPageArsip);
}

// --- Search ---
async function searchKhs() {
    loadKhsData(1, 1);
}

// --- Pagination ---
function prevPageAktif() {
    if (currentPageAktif > 1) loadKhsData(currentPageAktif - 1, currentPageArsip);
}

function nextPageAktif() {
    loadKhsData(currentPageAktif + 1, currentPageArsip);
}

function prevPageArsip() {
    if (currentPageArsip > 1) loadKhsData(currentPageAktif, currentPageArsip - 1);
}

function nextPageArsip() {
    loadKhsData(currentPageAktif, currentPageArsip + 1);
}

document.addEventListener("DOMContentLoaded", () => loadKhsData());