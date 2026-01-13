const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;
let pertemuanList = [];
let mahasiswaList = [];

async function loadPertemuan() {
    const query = `query { 
        allPertemuan { 
            id pertemuan_ke tanggal 
            kelas { 
                kode_kelas nama_kelas 
                mataKuliah { nama_mk } 
            } 
        } 
    }`;
    const res = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query })
    });
    const data = await res.json();
    pertemuanList = data?.data?.allPertemuan || [];
    populatePertemuanDropdown();
}

async function loadMahasiswa() {
    const query = `query { allMahasiswa { id nim nama_lengkap } }`;
    const res = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query })
    });
    const data = await res.json();
    mahasiswaList = data?.data?.allMahasiswa || [];
    populateMahasiswaDropdown();
}

function populatePertemuanDropdown() {
    const selects = ['addPertemuan', 'editPertemuan', 'filterPertemuan'];
    selects.forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            const defaultOption = id === 'filterPertemuan' 
                ? '<option value="">Semua Pertemuan</option>' 
                : '<option value="">Pilih Pertemuan</option>';
            select.innerHTML = defaultOption + pertemuanList.map(p => 
                `<option value="${p.id}">
                    ${p.kelas?.kode_kelas || ''} - Pertemuan ${p.pertemuan_ke} (${formatDate(p.tanggal)})
                </option>`
            ).join('');
        }
    });
}

function populateMahasiswaDropdown() {
    const selects = ['addMahasiswa', 'editMahasiswa', 'filterMahasiswa'];
    selects.forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            const defaultOption = id === 'filterMahasiswa' 
                ? '<option value="">Semua Mahasiswa</option>' 
                : '<option value="">Pilih Mahasiswa</option>';
            select.innerHTML = defaultOption + mahasiswaList.map(m => 
                `<option value="${m.id}">${m.nim} - ${m.nama_lengkap}</option>`
            ).join('');
        }
    });
}

async function loadKehadiranData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";
    const filterPertemuan = parseInt(document.getElementById("filterPertemuan")?.value) || null;
    const filterMahasiswa = parseInt(document.getElementById("filterMahasiswa")?.value) || null;
    const filterStatus = document.getElementById("filterStatus")?.value || null;

    const queryAktif = `
    query($first: Int, $page: Int, $search: String, $pertemuan_id: Int, $mahasiswa_id: Int, $status_kehadiran: StatusKehadiran) {
        allKehadiranPaginate(first: $first, page: $page, search: $search, pertemuan_id: $pertemuan_id, mahasiswa_id: $mahasiswa_id, status_kehadiran: $status_kehadiran) {
            data { 
                id pertemuan_id mahasiswa_id krs_detail_id status_kehadiran 
                waktu_input keterangan
                pertemuan { 
                    pertemuan_ke tanggal
                    kelas { kode_kelas mataKuliah { nama_mk } }
                }
                mahasiswa { nim nama_lengkap }
                diinput_oleh
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
                pertemuan_id: filterPertemuan,
                mahasiswa_id: filterMahasiswa,
                status_kehadiran: filterStatus
            }
        })
    });

    const dataAktif = await resAktif.json();
    renderKehadiranTable(dataAktif?.data?.allKehadiranPaginate?.data || [], 'dataKehadiran', true);

    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        allKehadiranArsip(first: $first, page: $page, search: $search) {
            data { 
                id pertemuan_id mahasiswa_id krs_detail_id status_kehadiran 
                waktu_input keterangan
                pertemuan { 
                    pertemuan_ke tanggal
                    kelas { kode_kelas mataKuliah { nama_mk } }
                }
                mahasiswa { nim nama_lengkap }
                diinput_oleh
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
    renderKehadiranTable(dataArsip?.data?.allKehadiranArsip?.data || [], 'dataKehadiranArsip', false);

    updatePagination(dataAktif?.data?.allKehadiranPaginate?.paginatorInfo, 'Aktif');
    updatePagination(dataArsip?.data?.allKehadiranArsip?.paginatorInfo, 'Arsip');
}

function renderKehadiranTable(data, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';

    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="9" class="text-center text-gray-500 p-3">Tidak ada data</td></tr>`;
        return;
    }

    data.forEach(item => {
        const statusBadge = getStatusKehadiranBadge(item.status_kehadiran);
        
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
                    <button onclick="hapusKehadiran(${item.id})" 
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
                    <button onclick="restoreKehadiran(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-md hover:bg-green-700">Restore</button>
                    <button onclick="forceDeleteKehadiran(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-red-700 text-white rounded-md hover:bg-red-800">Hapus</button>
                </div>
            `;
        }

        tbody.innerHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${item.pertemuan?.kelas?.kode_kelas || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">Pertemuan ${item.pertemuan?.pertemuan_ke || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${formatDate(item.pertemuan?.tanggal)}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.mahasiswa?.nim || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.mahasiswa?.nama_lengkap || '-'}</td>
                <td class="px-6 py-4 text-sm text-center">${statusBadge}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${formatDateTime(item.waktu_input)}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.keterangan || '-'}</td>
                <td class="px-6 py-4 text-sm">${actions}</td>
            </tr>
        `;
    });
}

function getStatusKehadiranBadge(status) {
    const badges = {
        'Hadir': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>',
        'Izin': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>',
        'Sakit': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Sakit</span>',
        'Alpa': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Alpa</span>'
    };
    return badges[status] || status;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('id-ID', options);
}

function formatDateTime(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
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

async function hapusKehadiran(id) {
    if (!confirm('Arsipkan data ini?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { deleteKehadiran(id: ${id}) { id } }` })
    });
    loadKehadiranData(currentPageAktif, currentPageArsip);
}

async function restoreKehadiran(id) {
    if (!confirm('Restore data ini?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { restoreKehadiran(id: ${id}) { id } }` })
    });
    loadKehadiranData(currentPageAktif, currentPageArsip);
}

async function forceDeleteKehadiran(id) {
    if (!confirm('Hapus permanen?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { forceDeleteKehadiran(id: ${id}) { id } }` })
    });
    loadKehadiranData(currentPageAktif, currentPageArsip);
}

async function searchKehadiran() { loadKehadiranData(1, 1); }
function prevPageAktif() { if (currentPageAktif > 1) loadKehadiranData(currentPageAktif - 1, currentPageArsip); }
function nextPageAktif() { loadKehadiranData(currentPageAktif + 1, currentPageArsip); }
function prevPageArsip() { if (currentPageArsip > 1) loadKehadiranData(currentPageAktif, currentPageArsip - 1); }
function nextPageArsip() { loadKehadiranData(currentPageAktif, currentPageArsip + 1); }

document.addEventListener("DOMContentLoaded", async () => {
    await loadPertemuan();
    await loadMahasiswa();
    loadKehadiranData();
});