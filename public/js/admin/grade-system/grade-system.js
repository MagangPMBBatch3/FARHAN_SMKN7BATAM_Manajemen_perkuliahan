const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;

async function loadGradeSystemData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";
    showTableLoading('dataGradeSystem', 5, 10);
    showTableLoading('dataGradeSystemArsip', 5, 10);

    // Query Data Aktif
    const queryAktif = `
    query($first: Int, $page: Int, $search: String) {
        allGradeSystemPaginate(first: $first, page: $page, search: $search) {
            data { 
                id grade min_score max_score grade_point 
                status_kelulusan keterangan 
            }
            paginatorInfo { 
                currentPage lastPage total hasMorePages perPage 
            }
        }
    }`;

    const resAktif = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            query: queryAktif, 
            variables: { first: perPageAktif, page: pageAktif, search: searchValue }
        })
    });

    const dataAktif = await resAktif.json();
    renderGradeSystemTable(dataAktif?.data?.allGradeSystemPaginate?.data || [], 'dataGradeSystem', true);

    // Query Data Arsip
    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        allGradeSystemArsip(first: $first, page: $page, search: $search) {
            data { 
                id grade min_score max_score grade_point 
                status_kelulusan keterangan 
            }
            paginatorInfo { 
                currentPage lastPage total hasMorePages perPage 
            }
        }
    }`;

    const resArsip = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            query: queryArsip, 
            variables: { first: perPageArsip, page: pageArsip, search: searchValue }
        })
    });

    const dataArsip = await resArsip.json();
    renderGradeSystemTable(dataArsip?.data?.allGradeSystemArsip?.data || [], 'dataGradeSystemArsip', false);

    // Update pagination
    const pageInfoAktif = dataAktif?.data?.allGradeSystemPaginate?.paginatorInfo;
    if (pageInfoAktif) {
        document.getElementById("pageInfoAktif").innerText =
            `Halaman ${pageInfoAktif.currentPage} dari ${pageInfoAktif.lastPage} (Total: ${pageInfoAktif.total})`;
        document.getElementById("prevBtnAktif").disabled = pageInfoAktif.currentPage <= 1;
        document.getElementById("nextBtnAktif").disabled = !pageInfoAktif.hasMorePages;
    }

    const pageInfoArsip = dataArsip?.data?.allGradeSystemArsip?.paginatorInfo;
    if (pageInfoArsip) {
        document.getElementById("pageInfoArsip").innerText =
            `Halaman ${pageInfoArsip.currentPage} dari ${pageInfoArsip.lastPage} (Total: ${pageInfoArsip.total})`;
        document.getElementById("prevBtnArsip").disabled = pageInfoArsip.currentPage <= 1;
        document.getElementById("nextBtnArsip").disabled = !pageInfoArsip.hasMorePages;
    }
}

function renderGradeSystemTable(data, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';

    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-500 p-3">Tidak ada data</td></tr>`;
        return;
    }

    data.forEach(item => {
        const statusBadge = item.status_kelulusan === 'Lulus' 
            ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lulus</span>'
            : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Tidak Lulus</span>';

        let actions = '';
        if (isActive) {
            actions = `
                <div class="flex items-center justify-end gap-2">
                    <button onclick="openEditModal(${item.id}, '${item.grade}', ${item.min_score}, ${item.max_score}, ${item.grade_point}, '${item.status_kelulusan}', '${item.keterangan || ''}')" 
                            class="px-3 py-1.5 text-xs bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="hapusGradeSystem(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-red-600 text-white rounded-md hover:bg-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;
        } else {
            actions = `
                <div class="flex items-center justify-end gap-2">
                    <button onclick="restoreGradeSystem(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-md hover:bg-green-700">Restore</button>
                    <button onclick="forceDeleteGradeSystem(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-red-700 text-white rounded-md hover:bg-red-800">Hapus</button>
                </div>
            `;
        }

        tbody.innerHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-bold text-gray-900">${item.grade}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.min_score}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.max_score}</td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">${item.grade_point}</td>
                <td class="px-6 py-4 text-sm">${statusBadge}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.keterangan || '-'}</td>
                <td class="px-6 py-4 text-sm">${actions}</td>
            </tr>
        `;
    });
}

// CRUD Operations
async function hapusGradeSystem(id) {
    if (!confirm('Arsipkan data ini?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { deleteGradeSystem(id: ${id}) { id } }` })
    });
    loadGradeSystemData(currentPageAktif, currentPageArsip);
}

async function restoreGradeSystem(id) {
    if (!confirm('Restore data ini?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { restoreGradeSystem(id: ${id}) { id } }` })
    });
    loadGradeSystemData(currentPageAktif, currentPageArsip);
}

async function forceDeleteGradeSystem(id) {
    if (!confirm('Hapus permanen?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { forceDeleteGradeSystem(id: ${id}) { id } }` })
    });
    loadGradeSystemData(currentPageAktif, currentPageArsip);
}

// Pagination & Search
async function searchGradeSystem() { loadGradeSystemData(1, 1); }
function prevPageAktif() { if (currentPageAktif > 1) loadGradeSystemData(currentPageAktif - 1, currentPageArsip); }
function nextPageAktif() { loadGradeSystemData(currentPageAktif + 1, currentPageArsip); }
function prevPageArsip() { if (currentPageArsip > 1) loadGradeSystemData(currentPageAktif, currentPageArsip - 1); }
function nextPageArsip() { loadGradeSystemData(currentPageAktif, currentPageArsip + 1); }

document.addEventListener("DOMContentLoaded", () => loadGradeSystemData());