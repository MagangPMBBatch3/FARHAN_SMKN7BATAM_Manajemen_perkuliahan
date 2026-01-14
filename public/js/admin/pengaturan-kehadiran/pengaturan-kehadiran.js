const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;
let kelasList = [];

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

function populateKelasDropdown() {
    const selects = ['addKelas', 'editKelas', 'filterKelas'];
    selects.forEach(id => {
        const select = document.getElementById(id);
        if (select) {
            const defaultOption = id === 'filterKelas' 
                ? '<option value="">Semua Kelas</option>' 
                : '<option value="">Pilih Kelas</option>';
            select.innerHTML = defaultOption + kelasList.map(k => 
                `<option value="${k.id}">${k.kode_kelas} - ${k.nama_kelas} (${k.mataKuliah?.nama_mk || ''})</option>`
            ).join('');
        }
    });
}

async function loadPengaturanKehadiranData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";
    const filterKelas = parseInt(document.getElementById("filterKelas")?.value) || null;

    const queryAktif = `
    query($first: Int, $page: Int, $search: String, $kelas_id: Int) {
        allPengaturanKehadiranPaginate(first: $first, page: $page, search: $search, kelas_id: $kelas_id) {
            data { 
                id kelas_id minimal_kehadiran auto_generate_pertemuan 
                keterangan aktif
                kelas { kode_kelas nama_kelas mataKuliah { nama_mk } }
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
                kelas_id: filterKelas
            }
        })
    });

    const dataAktif = await resAktif.json();
    renderPengaturanKehadiranTable(dataAktif?.data?.allPengaturanKehadiranPaginate?.data || [], 'dataPengaturanKehadiran', true);

    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        allPengaturanKehadiranArsip(first: $first, page: $page, search: $search) {
            data { 
                id kelas_id minimal_kehadiran auto_generate_pertemuan 
                keterangan aktif
                kelas { kode_kelas nama_kelas mataKuliah { nama_mk } }
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
    renderPengaturanKehadiranTable(dataArsip?.data?.allPengaturanKehadiranArsip?.data || [], 'dataPengaturanKehadiranArsip', false);

    updatePagination(dataAktif?.data?.allPengaturanKehadiranPaginate?.paginatorInfo, 'Aktif');
    updatePagination(dataArsip?.data?.allPengaturanKehadiranArsip?.paginatorInfo, 'Arsip');
}

function renderPengaturanKehadiranTable(data, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';

    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-500 p-3">Tidak ada data</td></tr>`;
        return;
    }

    data.forEach(item => {
        const statusAktifBadge = item.aktif 
            ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>'
            : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>';
        
        const autoGenerateBadge = item.auto_generate_pertemuan
            ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Ya</span>'
            : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tidak</span>';
        
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
                    <button onclick="toggleAktif(${item.id}, ${item.aktif ? 0 : 1})" 
                            class="px-3 py-1.5 text-xs ${item.aktif ? 'bg-gray-600' : 'bg-green-600'} text-white rounded-md hover:opacity-80">
                        ${item.aktif ? 'Nonaktifkan' : 'Aktifkan'}
                    </button>
                    <button onclick="hapusPengaturanKehadiran(${item.id})" 
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
                    <button onclick="restorePengaturanKehadiran(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-green-600 text-white rounded-md hover:bg-green-700">Restore</button>
                    <button onclick="forceDeletePengaturanKehadiran(${item.id})" 
                            class="px-3 py-1.5 text-xs bg-red-700 text-white rounded-md hover:bg-red-800">Hapus</button>
                </div>
            `;
        }

        tbody.innerHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${item.kelas?.kode_kelas || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.kelas?.nama_kelas || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.kelas?.mataKuliah?.nama_mk || '-'}</td>
                <td class="px-6 py-4 text-sm text-center font-semibold text-blue-600">${item.minimal_kehadiran}%</td>
                <td class="px-6 py-4 text-sm text-center">${autoGenerateBadge}</td>
                <td class="px-6 py-4 text-sm text-center">${statusAktifBadge}</td>
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

async function toggleAktif(id, aktif) {
    const statusText = aktif ? 'mengaktifkan' : 'menonaktifkan';
    if (!confirm(`Yakin ingin ${statusText} pengaturan ini?`)) return;
    
    // Konversi ke boolean (aktif sudah 1 atau 0 dari parameter)
    const aktifBoolean = aktif === 1 || aktif === true;
    
    const mutation = `
    mutation {
        updatePengaturanKehadiran(id: ${id}, input: {
            aktif: ${aktifBoolean}
        }) {
            id aktif
        }
    }`;

    try {
        const response = await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Error:', result.errors);
            alert('Gagal mengubah status: ' + result.errors[0].message);
            return;
        }

        loadPengaturanKehadiranData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    }
}

async function hapusPengaturanKehadiran(id) {
    if (!confirm('Arsipkan data ini?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { deletePengaturanKehadiran(id: ${id}) { id } }` })
    });
    loadPengaturanKehadiranData(currentPageAktif, currentPageArsip);
}

async function restorePengaturanKehadiran(id) {
    if (!confirm('Restore data ini?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { restorePengaturanKehadiran(id: ${id}) { id } }` })
    });
    loadPengaturanKehadiranData(currentPageAktif, currentPageArsip);
}

async function forceDeletePengaturanKehadiran(id) {
    if (!confirm('Hapus permanen?')) return;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: `mutation { forceDeletePengaturanKehadiran(id: ${id}) { id } }` })
    });
    loadPengaturanKehadiranData(currentPageAktif, currentPageArsip);
}

async function searchPengaturanKehadiran() { loadPengaturanKehadiranData(1, 1); }
function prevPageAktif() { if (currentPageAktif > 1) loadPengaturanKehadiranData(currentPageAktif - 1, currentPageArsip); }
function nextPageAktif() { loadPengaturanKehadiranData(currentPageAktif + 1, currentPageArsip); }
function prevPageArsip() { if (currentPageArsip > 1) loadPengaturanKehadiranData(currentPageAktif, currentPageArsip - 1); }
function nextPageArsip() { loadPengaturanKehadiranData(currentPageAktif, currentPageArsip + 1); }

document.addEventListener("DOMContentLoaded", async () => {
    await loadKelas();
    loadPengaturanKehadiranData();
});