const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;
let currentGradeSystem = [];

async function loadNilaiData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";
    const filterKelas = parseInt(document.getElementById("filterKelas")?.value) || null;
    const filterStatus = document.getElementById("filterStatus")?.value || null;
    
    showTableLoading('dataNilai', 11, 10);
    showTableLoading('dataNilaiArsip', 11, 10);

    // Load filter kelas untuk dosen
    await loadKelasFilter();

    // Query Data Aktif - hanya nilai dari kelas yang diampu dosen
    const queryAktif = `
    query($first: Int, $page: Int, $search: String, $kelas_id: Int, $status: String) {
        currentDosenNilai(first: $first, page: $page, search: $search, kelas_id: $kelas_id, status: $status) {
            data { 
                id 
                krsDetail {
                    id
                    krs {
                        id
                        mahasiswa {
                            id 
                            nama_lengkap
                            nim
                        }
                    }
                    kelas {
                        id
                        nama_kelas
                        kode_kelas
                        semester{
                            id
                            kode_semester
                            nama_semester
                        }
                    }
                    mataKuliah {
                        id 
                        nama_mk
                        kode_mk
                    }
                }
                bobotNilai {
                    id
                    tugas
                    quiz
                    uts
                    uas
                    kehadiran
                    praktikum
                }
                tugas 
                quiz 
                uts 
                uas 
                kehadiran
                praktikum
                nilai_akhir 
                nilai_huruf 
                nilai_mutu 
                status 
            }
            paginatorInfo { currentPage lastPage total hasMorePages perPage }
        }
    }`;

    const variablesAktif = { 
        first: perPageAktif, 
        page: pageAktif, 
        search: searchValue,
        kelas_id: filterKelas,
        status: filterStatus
    };

    const resAktif = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query: queryAktif, variables: variablesAktif })
    });
    const dataAktif = await resAktif.json();
    renderNilaiTable(dataAktif?.data?.currentDosenNilai?.data || [], 'dataNilai', true);

    // Query Data Arsip
    const queryArsip = `
    query($first: Int, $page: Int, $search: String) {
        currentDosenNilaiArsip(first: $first, page: $page, search: $search) {
            data { 
                id 
                krsDetail {
                    id
                    krs {
                        mahasiswa {
                            nama_lengkap
                            nim
                        }
                    }
                    kelas{
                        id
                        nama_kelas
                        kode_kelas
                        semester{
                            id
                            kode_semester
                            nama_semester
                        }
                    }
                    mataKuliah {
                        nama_mk
                        kode_mk
                    }
                }
                tugas 
                quiz 
                uts 
                uas 
                kehadiran
                praktikum
                nilai_akhir 
                nilai_huruf 
                nilai_mutu 
                status 
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
    renderNilaiTable(dataArsip?.data?.currentDosenNilaiArsip?.data || [], 'dataNilaiArsip', false);

    // Update pagination
    const pageInfoAktif = dataAktif?.data?.currentDosenNilai?.paginatorInfo;
    if (pageInfoAktif) {
        document.getElementById("pageInfoAktif").innerText =
            `Halaman ${pageInfoAktif.currentPage} dari ${pageInfoAktif.lastPage} (Total: ${pageInfoAktif.total})`;
        document.getElementById("prevBtnAktif").disabled = pageInfoAktif.currentPage <= 1;
        document.getElementById("nextBtnAktif").disabled = !pageInfoAktif.hasMorePages;
    }

    const pageInfoArsip = dataArsip?.data?.currentDosenNilaiArsip?.paginatorInfo;
    if (pageInfoArsip) {
        document.getElementById("pageInfoArsip").innerText =
            `Halaman ${pageInfoArsip.currentPage} dari ${pageInfoArsip.lastPage} (Total: ${pageInfoArsip.total})`;
        document.getElementById("prevBtnArsip").disabled = pageInfoArsip.currentPage <= 1;
        document.getElementById("nextBtnArsip").disabled = !pageInfoArsip.hasMorePages;
    }
}

async function loadKelasFilter() {
    const query = `
    query { 
        currentDosenKelas { 
            id 
            kode_kelas 
            nama_kelas 
            mataKuliah { 
                nama_mk 
            } 
        } 
    }`;
    
    try {
        const res = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });
        const data = await res.json();
        const kelasList = data?.data?.currentDosenKelas || [];
        
        const select = document.getElementById('filterKelas');
        if (select) {
            select.innerHTML = '<option value="">Semua Kelas</option>';
            kelasList.forEach(k => {
                select.innerHTML += `<option value="${k.id}">${k.kode_kelas} - ${k.mataKuliah.nama_mk}</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading kelas filter:', error);
    }
}

function renderNilaiTable(Nilai, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';

    if (!Nilai.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="11" class="text-center text-gray-500 p-3">Tidak ada data</td>
            </tr>
        `;
        return;
    }

    Nilai.forEach(item => {
        let actions = '';       
        if (isActive) {
            actions = `
                <div class="flex items-center justify-end gap-2">
                    <button onclick='openEditModal(${JSON.stringify(item).replace(/'/g, "&#39;")})' 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors"
                            title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="hapusNilai(${item.id})" 
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
                    <button onclick="restoreNilai(${item.id})" 
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                            title="Restore">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                    <button onclick="forceDeleteNilai(${item.id})" 
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
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="font-medium">${item.krsDetail.krs.mahasiswa.nama_lengkap}</div>
                    <div class="text-gray-500 text-xs">${item.krsDetail.krs.mahasiswa.nim}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="font-medium">${item.krsDetail.mataKuliah.nama_mk}</div>
                    <div class="text-gray-500 text-xs">${item.krsDetail.mataKuliah.kode_mk}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">${item.tugas || "-"}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">${item.quiz || "-"}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">${item.uts || "-"}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">${item.uas || "-"}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-center">${item.nilai_akhir || "-"}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${getNilaiHurufColor(item.nilai_huruf)}">
                        ${item.nilai_huruf || "-"}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">${item.nilai_mutu || "-"}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(item.status)}">
                        ${item.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${actions}</td>
            </tr>
        `;
    });
}

function getNilaiHurufColor(huruf) {
    const colors = {
        'A': 'bg-green-100 text-green-800',
        'B': 'bg-blue-100 text-blue-800',
        'C': 'bg-yellow-100 text-yellow-800',
        'D': 'bg-orange-100 text-orange-800',
        'E': 'bg-red-100 text-red-800'
    };
    return colors[huruf] || 'bg-gray-100 text-gray-800';
}

function getStatusColor(status) {
    const colors = {
        'Lulus': 'bg-green-100 text-green-800',
        'Tidak Lulus': 'bg-red-100 text-red-800',
        'Pending': 'bg-yellow-100 text-yellow-800',
        'Draft': 'bg-gray-100 text-gray-800',
        'Final': 'bg-blue-100 text-blue-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

async function hapusNilai(id) {
    if (!confirm('Pindahkan ke arsip?')) return;
    const mutation = `mutation { deleteNilai(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadNilaiData(currentPageAktif, currentPageArsip);
}

async function restoreNilai(id) {
    if (!confirm('Kembalikan dari arsip?')) return;
    const mutation = `mutation { restoreNilai(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadNilaiData(currentPageAktif, currentPageArsip);
}

async function forceDeleteNilai(id) {
    if (!confirm('Hapus permanen? Data tidak bisa dikembalikan')) return;
    const mutation = `mutation { forceDeleteNilai(id: ${id}) { id } }`;
    await fetch(API_URL, { 
        method: 'POST', 
        headers: { 'Content-Type': 'application/json' }, 
        body: JSON.stringify({ query: mutation }) 
    });
    loadNilaiData(currentPageAktif, currentPageArsip);
}

async function searchNilai() {
    loadNilaiData(1, 1);
}

function prevPageAktif() {
    if (currentPageAktif > 1) loadNilaiData(currentPageAktif - 1, currentPageArsip);
}

function nextPageAktif() {
    loadNilaiData(currentPageAktif + 1, currentPageArsip);
}

function prevPageArsip() {
    if (currentPageArsip > 1) loadNilaiData(currentPageAktif, currentPageArsip - 1);
}

function nextPageArsip() {
    loadNilaiData(currentPageAktif, currentPageArsip + 1);
}

function showTableLoading(tableId, colspan, rows) {
    const tbody = document.getElementById(tableId);
    let loadingRows = '';
    for(let i = 0; i < rows; i++) {
        loadingRows += `
            <tr class="animate-pulse">
                ${Array(colspan).fill('<td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>').join('')}
            </tr>
        `;
    }
    tbody.innerHTML = loadingRows;
}

document.addEventListener("DOMContentLoaded", () => loadNilaiData());