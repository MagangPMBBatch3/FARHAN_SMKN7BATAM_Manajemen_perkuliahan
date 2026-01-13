const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;
let mataKuliahList = [];
let semesterList = [];

async function loadMataKuliah() {
    const query = `query { allMataKuliah { id kode_mk nama_mk } }`;
    const res = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query })
    });
    const data = await res.json();
    mataKuliahList = data?.data?.allMataKuliah || [];
    populateMataKuliahDropdown();
}

async function loadSemester() {
    const query = `query { allSemester { id kode_semester nama_semester } }`;
    const res = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query })
    });
    const data = await res.json();
    semesterList = data?.data?.allSemester || [];
    populateSemesterDropdown();
}

function populateMataKuliahDropdown() {
    const selects = ['addMataKuliah', 'editMataKuliah', 'filterMataKuliah'];
    selects.forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            const defaultOption = id === 'filterMataKuliah' 
                ? '<option value="">Semua Mata Kuliah</option>' 
                : '<option value="">Pilih Mata Kuliah</option>';
            select.innerHTML = defaultOption + mataKuliahList.map(mk => 
                `<option value="${mk.id}">${mk.kode_mk} - ${mk.nama_mk}</option>`
            ).join('');
        }
    });
}

function populateSemesterDropdown() {
    const selects = ['addSemester', 'editSemester', 'filterSemester'];
    selects.forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            const defaultOption = id === 'filterSemester' 
                ? '<option value="">Semua Semester</option>' 
                : '<option value="">Pilih Semester</option>';
            select.innerHTML = defaultOption + semesterList.map(s => 
                `<option value="${s.id}">${s.nama_semester}</option>`
            ).join('');
        }
    });
}

async function loadBobotNilaiData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";
    const filterMK = parseInt(document.getElementById("filterMataKuliah")?.value) || null;
    const filterSemester = parseInt(document.getElementById("filterSemester")?.value) || null;

    const queryAktif = `
    query($first: Int, $page: Int, $search: String, $mata_kuliah_id: Int, $semester_id: Int) {
        allBobotNilaiPaginate(first: $first, page: $page, search: $search, mata_kuliah_id: $mata_kuliah_id, semester_id: $semester_id) {
            data { 
                id mata_kuliah_id semester_id tugas quiz uts uas kehadiran praktikum total_bobot keterangan
                mataKuliah { kode_mk nama_mk }
                semester { nama_semester }
            }
            paginatorInfo { currentPage lastPage total hasMorePages perPage }
        }
    }`;

    const resAktif = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            query: queryAktif, 
            variables: { first: perPageAktif, page: pageAktif, search: searchValue, mata_kuliah_id: filterMK, semester_id: filterSemester }
        })
    });

    const dataAktif = await resAktif.json();
    renderBobotNilaiTable(dataAktif?.data?.allBobotNilaiPaginate?.data || [], 'dataBobotNilai', true);

    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        allBobotNilaiArsip(first: $first, page: $page, search: $search) {
            data { 
                id mata_kuliah_id semester_id tugas quiz uts uas kehadiran praktikum total_bobot keterangan
                mataKuliah { kode_mk nama_mk }
                semester { nama_semester }
            }
            paginatorInfo { currentPage lastPage total hasMorePages perPage }
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
    renderBobotNilaiTable(dataArsip?.data?.allBobotNilaiArsip?.data || [], 'dataBobotNilaiArsip', false);

    updatePagination(dataAktif?.data?.allBobotNilaiPaginate?.paginatorInfo, 'Aktif');
    updatePagination(dataArsip?.data?.allBobotNilaiArsip?.paginatorInfo, 'Arsip');
}

function renderBobotNilaiTable(data, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';

    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="11" class="text-center text-gray-500 p-3">Tidak ada data</td></tr>`;
        return;
    }

    data.forEach(item => {
        const totalBadge = item.total_bobot == 100 
            ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">100%</span>'
            : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">' + item.total_bobot + '%</span>';

        let actions = '';
        if (isActive) {
            actions = `
                <div class="flex items-center justify-end gap-2">
                    <button onclick='openEditModal(${JSON.stringify(item)})' 
                            class="px-3 py-1.5 text-xs bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="hapusBobotNilai(${item.id})" 
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
                    <button onclick="restoreBobotNilai(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-md hover:bg-green-700">Restore</button>
                    <button onclick="forceDeleteBobotNilai(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-red-700 text-white rounded-md hover:bg-red-800">Hapus</button>
                </div>
            `;
        }

        tbody.innerHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${item.mataKuliah?.kode_mk || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.mataKuliah?.nama_mk || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.semester?.nama_semester || '-'}</td>
                <td class="px-6 py-4 text-sm text-center text-gray-900">${item.tugas || 0}%</td>
                <td class="px-6 py-4 text-sm text-center text-gray-900">${item.quiz || 0}%</td>
                <td class="px-6 py-4 text-sm text-center text-gray-900">${item.uts || 0}%</td>
                <td class="px-6 py-4 text-sm text-center text-gray-900">${item.uas || 0}%</td>
                <td class="px-6 py-4 text-sm text-center text-gray-900">${item.kehadiran || 0}%</td>
                <td class="px-6 py-4 text-sm text-center text-gray-900">${item.praktikum || 0}%</td>
                <td class="px-6 py-4 text-sm text-center">${totalBadge}</td>
                <td class="px-6 py-4 text-sm">${actions}</td>
            </tr>
        `;
    });
}

function updatePagination(pageInfo, type) {
    if (pageInfo) {
        document.getElementById(`pageInfo${type}`).innerText =
            `Halaman ${pageInfo.currentPage} dari ${pageInfo.lastPage} (Total: ${pageInfo.total})`;
        document.getElementById(`prevBtn${type}`).disabled = pageInfo.currentPage <= 1;
        document.getElementById(`nextBtn${type}`).disabled = !pageInfo.hasMorePages;
    }
}

async function hapusBobotNilai(id) {
    if (!confirm('Arsipkan data ini?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { deleteBobotNilai(id: ${id}) { id } }` })
    });
    loadBobotNilaiData(currentPageAktif, currentPageArsip);
}

async function restoreBobotNilai(id) {
    if (!confirm('Restore data ini?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { restoreBobotNilai(id: ${id}) { id } }` })
    });
    loadBobotNilaiData(currentPageAktif, currentPageArsip);
}

async function forceDeleteBobotNilai(id) {
    if (!confirm('Hapus permanen?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { forceDeleteBobotNilai(id: ${id}) { id } }` })
    });
    loadBobotNilaiData(currentPageAktif, currentPageArsip);
}

async function searchBobotNilai() { loadBobotNilaiData(1, 1); }
function prevPageAktif() { if (currentPageAktif > 1) loadBobotNilaiData(currentPageAktif - 1, currentPageArsip); }
function nextPageAktif() { loadBobotNilaiData(currentPageAktif + 1, currentPageArsip); }
function prevPageArsip() { if (currentPageArsip > 1) loadBobotNilaiData(currentPageAktif, currentPageArsip - 1); }
function nextPageArsip() { loadBobotNilaiData(currentPageAktif, currentPageArsip + 1); }

document.addEventListener("DOMContentLoaded", async () => {
    await loadMataKuliah();
    await loadSemester();
    loadBobotNilaiData();
});