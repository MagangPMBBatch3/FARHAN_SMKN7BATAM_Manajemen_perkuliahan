const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;

/**
 * Load data SKS Limit dengan pagination
 */
async function loadSksLimitData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";
    showTableLoading('dataSksLimit', 5, 5);
    showTableLoading('dataSksLimitArsip', 5, 5);

    // Query Data Aktif
    const queryAktif = `
    query($first: Int, $page: Int, $search: String) {
        allSksLimitPaginate(first: $first, page: $page, search: $search) {
            data { 
                id 
                min_ipk 
                max_ipk 
                max_sks 
                keterangan 
            }
            paginatorInfo { 
                currentPage 
                lastPage 
                total 
                hasMorePages 
                perPage 
            }
        }
    }`;

    const variablesAktif = { first: perPageAktif, page: pageAktif, search: searchValue };

    const resAktif = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query: queryAktif, variables: variablesAktif })
    });

    const dataAktif = await resAktif.json();
    renderSksLimitTable(dataAktif?.data?.allSksLimitPaginate?.data || [], 'dataSksLimit', true);

    // Query Data Arsip
    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        allSksLimitArsip(first: $first, page: $page, search: $search) {
            data { 
                id 
                min_ipk 
                max_ipk 
                max_sks 
                keterangan 
            }
            paginatorInfo { 
                currentPage 
                lastPage 
                total 
                hasMorePages 
                perPage 
            }
        }
    }`;

    const variablesArsip = { first: perPageArsip, page: pageArsip, search: searchValue };

    const resArsip = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query: queryArsip, variables: variablesArsip })
    });

    const dataArsip = await resArsip.json();
    renderSksLimitTable(dataArsip?.data?.allSksLimitArsip?.data || [], 'dataSksLimitArsip', false);

    // Update pagination info
    const pageInfoAktif = dataAktif?.data?.allSksLimitPaginate?.paginatorInfo;
    if (pageInfoAktif) {
        document.getElementById("pageInfoAktif").innerText =
            `Halaman ${pageInfoAktif.currentPage} dari ${pageInfoAktif.lastPage} (Total: ${pageInfoAktif.total})`;
        document.getElementById("prevBtnAktif").disabled = pageInfoAktif.currentPage <= 1;
        document.getElementById("nextBtnAktif").disabled = !pageInfoAktif.hasMorePages;
    }

    const pageInfoArsip = dataArsip?.data?.allSksLimitArsip?.paginatorInfo;
    if (pageInfoArsip) {
        document.getElementById("pageInfoArsip").innerText =
            `Halaman ${pageInfoArsip.currentPage} dari ${pageInfoArsip.lastPage} (Total: ${pageInfoArsip.total})`;
        document.getElementById("prevBtnArsip").disabled = pageInfoArsip.currentPage <= 1;
        document.getElementById("nextBtnArsip").disabled = !pageInfoArsip.hasMorePages;
    }
}

/**
 * Render tabel SKS Limit
 */
function renderSksLimitTable(data, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';

    if (!data.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-gray-500 p-3">Tidak ada data</td>
            </tr>
        `;
        return;
    }

    data.forEach(item => {
        let actions = '';
        if (isActive) {
            actions = `
                <div class="flex items-center justify-end gap-2">
                    <button onclick="openEditModal(${item.id}, ${item.min_ipk}, ${item.max_ipk}, ${item.max_sks}, '${item.keterangan || ''}')" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors"
                            title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="hapusSksLimit(${item.id})" 
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
                    <button onclick="restoreSksLimit(${item.id})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                            title="Restore">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    <button onclick="forceDeleteSksLimit(${item.id})" 
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.min_ipk || '0.00'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.max_ipk || '4.00'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.max_sks}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.keterangan || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${actions}</td>
            </tr>
        `;
    });
}

// CRUD Operations
async function hapusSksLimit(id) {
    if (!confirm('Pindahkan ke arsip?')) return;
    const mutation = `mutation { deleteSksLimit(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadSksLimitData(currentPageAktif, currentPageArsip);
}

async function restoreSksLimit(id) {
    if (!confirm('Kembalikan dari arsip?')) return;
    const mutation = `mutation { restoreSksLimit(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadSksLimitData(currentPageAktif, currentPageArsip);
}

async function forceDeleteSksLimit(id) {
    if (!confirm('Hapus permanen? Data tidak bisa dikembalikan')) return;
    const mutation = `mutation { forceDeleteSksLimit(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadSksLimitData(currentPageAktif, currentPageArsip);
}

// Search
async function searchSksLimit() {
    loadSksLimitData(1, 1);
}

// Pagination
function prevPageAktif() {
    if (currentPageAktif > 1) loadSksLimitData(currentPageAktif - 1, currentPageArsip);
}

function nextPageAktif() {
    loadSksLimitData(currentPageAktif + 1, currentPageArsip);
}

function prevPageArsip() {
    if (currentPageArsip > 1) loadSksLimitData(currentPageAktif, currentPageArsip - 1);
}

function nextPageArsip() {
    loadSksLimitData(currentPageAktif, currentPageArsip + 1);
}

document.addEventListener("DOMContentLoaded", () => loadSksLimitData());