const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;
let kelasList = [];
let ruanganList = [];

async function loadKelas() {
    const query = `query { allKelas { id kode_kelas nama_kelas mataKuliah { nama_mk } } }`;
    const res = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query })
    });
    const data = await res.json();
    kelasList = data?.data?.allKelas || [];
    populateKelasDropdown();
}

async function loadRuangan() {
    const query = `query { allRuangan { id kode_ruangan nama_ruangan } }`;
    const res = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query })
    });
    const data = await res.json();
    ruanganList = data?.data?.allRuangan || [];
    populateRuanganDropdown();
}

function populateKelasDropdown() {
    const selects = ['addKelas', 'editKelas', 'filterKelas'];
    selects.forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            const defaultOption = id === 'filterKelas' 
                ? '<option value="">Semua Kelas</option>' 
                : '<option value="">Pilih Kelas</option>';
            select.innerHTML = defaultOption + kelasList.map(k => 
                `<option value="${k.id}">${k.kode_kelas} - ${k.mataKuliah?.nama_mk || ''}</option>`
            ).join('');
        }
    });
}

function populateRuanganDropdown() {
    const selects = ['addRuangan', 'editRuangan'];
    selects.forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            select.innerHTML = '<option value="">Pilih Ruangan (Opsional)</option>' + 
                ruanganList.map(r => 
                    `<option value="${r.id}">${r.kode_ruangan} - ${r.nama_ruangan}</option>`
                ).join('');
        }
    });
}

async function loadPertemuanData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";
    const filterKelas = parseInt(document.getElementById("filterKelas")?.value) || null;
    const filterStatus = document.getElementById("filterStatus")?.value || null;

    const queryAktif = `
    query($first: Int, $page: Int, $search: String, $kelas_id: Int, $status_pertemuan: StatusPertemuan) {
        allPertemuanPaginate(first: $first, page: $page, search: $search, kelas_id: $kelas_id, status_pertemuan: $status_pertemuan) {
            data { 
                id kelas_id pertemuan_ke tanggal waktu_mulai waktu_selesai 
                materi metode ruangan_id status_pertemuan link_daring catatan
                kelas { kode_kelas nama_kelas mataKuliah { nama_mk } }
                ruangan { kode_ruangan nama_ruangan }
            }
            paginatorInfo { currentPage lastPage total hasMorePages perPage }
        }
    }`;

    const resAktif = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            query: queryAktif, 
            variables: { 
                first: perPageAktif, 
                page: pageAktif, 
                search: searchValue, 
                kelas_id: filterKelas,
                status_pertemuan: filterStatus
            }
        })
    });

    const dataAktif = await resAktif.json();
    renderPertemuanTable(dataAktif?.data?.allPertemuanPaginate?.data || [], 'dataPertemuan', true);

    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        allPertemuanArsip(first: $first, page: $page, search: $search) {
            data { 
                id kelas_id pertemuan_ke tanggal waktu_mulai waktu_selesai 
                materi metode ruangan_id status_pertemuan link_daring catatan
                kelas { kode_kelas nama_kelas mataKuliah { nama_mk } }
                ruangan { kode_ruangan nama_ruangan }
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
    renderPertemuanTable(dataArsip?.data?.allPertemuanArsip?.data || [], 'dataPertemuanArsip', false);

    updatePagination(dataAktif?.data?.allPertemuanPaginate?.paginatorInfo, 'Aktif');
    updatePagination(dataArsip?.data?.allPertemuanArsip?.paginatorInfo, 'Arsip');
}

function renderPertemuanTable(data, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';

    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="10" class="text-center text-gray-500 p-3">Tidak ada data</td></tr>`;
        return;
    }

    data.forEach(item => {
        const statusBadge = getStatusBadge(item.status_pertemuan);
        const metodeBadge = getMetodeBadge(item.metode);
        console.log(item);
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
                    <button onclick="hapusPertemuan(${item.id})" 
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
                    <button onclick="restorePertemuan(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-md hover:bg-green-700">Restore</button>
                    <button onclick="forceDeletePertemuan(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-red-700 text-white rounded-md hover:bg-red-800">Hapus</button>
                </div>
            `;
        }

        tbody.innerHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${item.kelas?.kode_kelas || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.kelas?.mataKuliah?.nama_mk || '-'}</td>
                <td class="px-6 py-4 text-sm text-center font-semibold text-gray-900">Ke-${item.pertemuan_ke}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${formatDate(item.tanggal)}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.waktu_mulai} - ${item.waktu_selesai}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.materi || '-'}</td>
                <td class="px-6 py-4 text-sm text-center">${metodeBadge}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.ruangan?.nama_ruangan || '-'}</td>
                <td class="px-6 py-4 text-sm text-center">${statusBadge}</td>
                <td class="px-6 py-4 text-sm">${actions}</td>
            </tr>
        `;
    });
}

function getStatusBadge(status) {
    const badges = {
        'Dijadwalkan': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Dijadwalkan</span>',
        'Berlangsung': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Berlangsung</span>',
        'Selesai': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Selesai</span>',
        'Dibatalkan': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Dibatalkan</span>'
    };
    return badges[status] || status;
}

function getMetodeBadge(metode) {
    const badges = {
        'Tatap Muka': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Tatap Muka</span>',
        'Daring': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">Daring</span>',
        'Hybrid': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">Hybrid</span>'
    };
    return badges[metode] || metode;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('id-ID', options);
}

function updatePagination(pageInfo, type) {
    if (pageInfo) {
        document.getElementById(`pageInfo${type}`).innerText =
            `Halaman ${pageInfo.currentPage} dari ${pageInfo.lastPage} (Total: ${pageInfo.total})`;
        document.getElementById(`prevBtn${type}`).disabled = pageInfo.currentPage <= 1;
        document.getElementById(`nextBtn${type}`).disabled = !pageInfo.hasMorePages;
    }
}

async function hapusPertemuan(id) {
    if (!confirm('Arsipkan data ini?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { deletePertemuan(id: ${id}) { id } }` })
    });
    loadPertemuanData(currentPageAktif, currentPageArsip);
}

async function restorePertemuan(id) {
    if (!confirm('Restore data ini?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { restorePertemuan(id: ${id}) { id } }` })
    });
    loadPertemuanData(currentPageAktif, currentPageArsip);
}

async function forceDeletePertemuan(id) {
    if (!confirm('Hapus permanen?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { forceDeletePertemuan(id: ${id}) { id } }` })
    });
    loadPertemuanData(currentPageAktif, currentPageArsip);
}

async function searchPertemuan() { loadPertemuanData(1, 1); }
function prevPageAktif() { if (currentPageAktif > 1) loadPertemuanData(currentPageAktif - 1, currentPageArsip); }
function nextPageAktif() { loadPertemuanData(currentPageAktif + 1, currentPageArsip); }
function prevPageArsip() { if (currentPageArsip > 1) loadPertemuanData(currentPageAktif, currentPageArsip - 1); }
function nextPageArsip() { loadPertemuanData(currentPageAktif, currentPageArsip + 1); }

document.addEventListener("DOMContentLoaded", async () => {
    await loadKelas();
    await loadRuangan();
    loadPertemuanData();
});