const API_URL = "/graphql";
let currentPage = 1;
let kelasList = [];
let semesterList = [];
let mahasiswaList = [];

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

function populateKelasDropdown() {
    const select = document.getElementById('filterKelas');
    if (select) {
        select.innerHTML = '<option value="">Semua Kelas</option>' + 
            kelasList.map(k => 
                `<option value="${k.id}">${k.kode_kelas} - ${k.mataKuliah?.nama_mk || ''}</option>`
            ).join('');
    }
}

function populateSemesterDropdown() {
    const select = document.getElementById('filterSemester');
    if (select) {
        select.innerHTML = '<option value="">Semua Semester</option>' + 
            semesterList.map(s => 
                `<option value="${s.id}">${s.nama_semester}</option>`
            ).join('');
    }
}

function populateMahasiswaDropdown() {
    const select = document.getElementById('filterMahasiswa');
    if (select) {
        select.innerHTML = '<option value="">Semua Mahasiswa</option>' + 
            mahasiswaList.map(m => 
                `<option value="${m.id}">${m.nim} - ${m.nama_lengkap}</option>`
            ).join('');
    }
}

async function loadRekapKehadiranData(page = 1) {
    currentPage = page;
    
    const perPage = parseInt(document.getElementById("perPage")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";
    const filterKelas = parseInt(document.getElementById("filterKelas")?.value) || null;
    const filterSemester = parseInt(document.getElementById("filterSemester")?.value) || null;
    const filterMahasiswa = parseInt(document.getElementById("filterMahasiswa")?.value) || null;
    const filterStatus = document.getElementById("filterStatus")?.value || null;
    
    // Map the filter value to GraphQL enum values
    let statusMinimal = null;
    if (filterStatus === "Memenuhi") {
        statusMinimal = "Memenuhi";
    } else if (filterStatus === "TidakMemenuhi" || filterStatus === "Tidak Memenuhi") {
        statusMinimal = "TidakMemenuhi";
    }

    const query = `
    query($first: Int, $page: Int, $search: String, $kelas_id: Int, $semester_id: Int, $mahasiswa_id: Int, $status_minimal: StatusMinimalKehadiran) {
        allRekapKehadiranPaginate(
            first: $first, 
            page: $page, 
            search: $search, 
            kelas_id: $kelas_id, 
            semester_id: $semester_id, 
            mahasiswa_id: $mahasiswa_id,
            status_minimal: $status_minimal
        ) {
            data { 
                id mahasiswa_id kelas_id semester_id
                total_pertemuan total_hadir total_izin total_sakit total_alpa
                persentase_kehadiran nilai_kehadiran status_minimal keterangan
                mahasiswa { nim nama_lengkap }
                kelas { kode_kelas nama_kelas mataKuliah { nama_mk } }
                semester { nama_semester }
            }
            paginatorInfo { currentPage lastPage total hasMorePages perPage }
        }
    }`;

    const res = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ 
            query, 
            variables: { 
                first: perPage, 
                page, 
                search: searchValue, 
                kelas_id: filterKelas,
                semester_id: filterSemester,
                mahasiswa_id: filterMahasiswa,
                status_minimal: statusMinimal
            }
        })
    });

    const data = await res.json();
    
    // Check for errors
    if (data.errors) {
        console.error("GraphQL Errors:", data.errors);
        alert("Terjadi kesalahan saat memuat data. Silakan cek console untuk detail.");
        return;
    }
    
    renderRekapKehadiranTable(data?.data?.allRekapKehadiranPaginate?.data || []);
    updatePagination(data?.data?.allRekapKehadiranPaginate?.paginatorInfo);
}

function renderRekapKehadiranTable(data) {
    const tbody = document.getElementById('dataRekapKehadiran');
    tbody.innerHTML = '';

    if (!data.length) {
        tbody.innerHTML = `<tr><td colspan="13" class="text-center text-gray-500 p-3">Tidak ada data</td></tr>`;
        return;
    }

    data.forEach(item => {
        const persentaseBadge = getPersentaseBadge(item.persentase_kehadiran);
        const statusBadge = getStatusMinimalBadge(item.status_minimal);
        
        tbody.innerHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">${item.mahasiswa?.nim || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.mahasiswa?.nama_lengkap || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.kelas?.kode_kelas || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.kelas?.mataKuliah?.nama_mk || '-'}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${item.semester?.nama_semester || '-'}</td>
                <td class="px-6 py-4 text-sm text-center text-gray-900">${item.total_pertemuan || 0}</td>
                <td class="px-6 py-4 text-sm text-center font-semibold text-green-600">${item.total_hadir || 0}</td>
                <td class="px-6 py-4 text-sm text-center text-blue-600">${item.total_izin || 0}</td>
                <td class="px-6 py-4 text-sm text-center text-yellow-600">${item.total_sakit || 0}</td>
                <td class="px-6 py-4 text-sm text-center text-red-600">${item.total_alpa || 0}</td>
                <td class="px-6 py-4 text-sm text-center">${persentaseBadge}</td>
                <td class="px-6 py-4 text-sm text-center">${statusBadge}</td>
                <td class="px-6 py-4 text-sm">
                    <button onclick='viewDetailKehadiran(${JSON.stringify(item)})' 
                            class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Detail
                    </button>
                </td>
            </tr>
        `;
    });
}

function getPersentaseBadge(persentase) {
    const value = parseFloat(persentase) || 0;
    let colorClass = 'bg-red-100 text-red-800';
    
    if (value >= 75) {
        colorClass = 'bg-green-100 text-green-800';
    } else if (value >= 50) {
        colorClass = 'bg-yellow-100 text-yellow-800';
    }
    
    return `<span class="px-2 py-1 text-xs font-semibold rounded-full ${colorClass}">${value.toFixed(2)}%</span>`;
}

function getStatusMinimalBadge(status) {
    // Handle both enum value and display value
    const statusMap = {
        'Memenuhi': 'Memenuhi',
        'TidakMemenuhi': 'Tidak Memenuhi',
        'Tidak Memenuhi': 'Tidak Memenuhi'
    };
    
    const displayStatus = statusMap[status] || status;
    
    const badges = {
        'Memenuhi': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Memenuhi</span>',
        'Tidak Memenuhi': '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Tidak Memenuhi</span>'
    };
    
    return badges[displayStatus] || displayStatus;
}

function updatePagination(pageInfo) {
    if (pageInfo) {
        document.getElementById("pageInfo").innerText =
            `Halaman ${pageInfo.currentPage} dari ${pageInfo.lastPage} (Total: ${pageInfo.total})`;
        document.getElementById("prevBtn").disabled = pageInfo.currentPage <= 1;
        document.getElementById("nextBtn").disabled = !pageInfo.hasMorePages;
    }
}

function viewDetailKehadiran(item) {
    // Populate modal detail
    document.getElementById('detailNim').textContent = item.mahasiswa?.nim || '-';
    document.getElementById('detailNama').textContent = item.mahasiswa?.nama_lengkap || '-';
    document.getElementById('detailKelas').textContent = item.kelas?.kode_kelas || '-';
    document.getElementById('detailMataKuliah').textContent = item.kelas?.mataKuliah?.nama_mk || '-';
    document.getElementById('detailSemester').textContent = item.semester?.nama_semester || '-';
    document.getElementById('detailTotalPertemuan').textContent = item.total_pertemuan || 0;
    document.getElementById('detailTotalHadir').textContent = item.total_hadir || 0;
    document.getElementById('detailTotalIzin').textContent = item.total_izin || 0;
    document.getElementById('detailTotalSakit').textContent = item.total_sakit || 0;
    document.getElementById('detailTotalAlpa').textContent = item.total_alpa || 0;
    document.getElementById('detailPersentase').innerHTML = getPersentaseBadge(item.persentase_kehadiran);
    document.getElementById('detailNilaiKehadiran').textContent = (item.nilai_kehadiran || 0).toFixed(2);
    document.getElementById('detailStatusMinimal').innerHTML = getStatusMinimalBadge(item.status_minimal);
    document.getElementById('detailKeterangan').textContent = item.keterangan || '-';
    
    // Show modal
    document.getElementById('modalDetail').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}

async function exportRekapKehadiran() {
    alert('Fitur export akan segera tersedia');
    // TODO: Implement export to Excel/PDF
}

async function searchRekapKehadiran() { loadRekapKehadiranData(1); }
function prevPage() { if (currentPage > 1) loadRekapKehadiranData(currentPage - 1); }
function nextPage() { loadRekapKehadiranData(currentPage + 1); }

document.addEventListener("DOMContentLoaded", async () => {
    await loadKelas();
    await loadSemester();
    await loadMahasiswa();
    loadRekapKehadiranData();
});