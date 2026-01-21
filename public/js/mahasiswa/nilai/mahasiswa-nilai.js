const API_URL = "/graphql";
let allNilaiData = [];
let filteredNilaiData = [];
let currentMahasiswaId = null;

// Load data saat halaman dimuat
document.addEventListener("DOMContentLoaded", async () => {
    await getMahasiswaProfile();
    await loadSemesterOptions();
    await loadNilaiMahasiswa();
});

// Get Mahasiswa Profile (untuk mendapatkan ID mahasiswa yang login)
async function getMahasiswaProfile() {
    const query = `
    query {
        mahasiswaProfile {
            id
            nim
            nama_lengkap
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        console.log(result);
        if (result.data && result.data.mahasiswaProfile) {
            currentMahasiswaId = result.data.mahasiswaProfile.id;
            document.getElementById('headerNIM').textContent = result.data.mahasiswaProfile.nim;
        } else {
            console.error('Failed to get mahasiswa profile');
            alert('Gagal memuat profil mahasiswa');
        }
    } catch (error) {
        console.error('Error getting mahasiswa profile:', error);
        alert('Terjadi kesalahan saat memuat profil');
    }
}

// Load Semester Options untuk filter
async function loadSemesterOptions() {
    const query = `
    query {
        allSemester {
            id
            kode_semester
            nama_semester
            tahun_ajaran
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const semesterList = result.data.allSemester || [];
        
        const select = document.getElementById('filterSemester');
        select.innerHTML = '<option value="">Semua Semester</option>';
        semesterList.forEach(s => {
            select.innerHTML += `<option value="${s.id}">${s.nama_semester} (${s.tahun_ajaran})</option>`;
        });
    } catch (error) {
        console.error('Error loading semester:', error);
    }
}

// Load Nilai Mahasiswa
async function loadNilaiMahasiswa() {
    if (!currentMahasiswaId) {
        console.error('Mahasiswa ID not found');
        return;
    }

    showLoading();

    const semesterFilter = document.getElementById('filterSemester').value;
    
    // Query untuk mendapatkan nilai mahasiswa berdasarkan ID mahasiswa yang login
    const query = `
    query($mahasiswaId: ID!) {
        nilaiByMahasiswa(mahasiswa_id: $mahasiswaId) {
            id
            krsDetail {
                id
                krs {
                    semester {
                        id
                        nama_semester
                        tahun_ajaran
                    }
                }
                kelas {
                    id
                    kode_kelas
                    nama_kelas
                    dosen {
                        nama_lengkap
                    }
                }
                mataKuliah {
                    id
                    kode_mk
                    nama_mk
                    sks
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
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { mahasiswaId: parseInt(currentMahasiswaId) }
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            showEmpty();
            return;
        }

        allNilaiData = result.data.nilaiByMahasiswa || [];
        
        // Filter berdasarkan semester jika dipilih
        if (semesterFilter) {
            filteredNilaiData = allNilaiData.filter(n => 
                n.krsDetail.krs.semester.id === semesterFilter
            );
        } else {
            filteredNilaiData = [...allNilaiData];
        }

        renderTable();
        calculateIPK();

    } catch (error) {
        console.error('Error loading nilai:', error);
        showEmpty();
    }
}

// Search Nilai
function searchNilai() {
    const searchValue = document.getElementById('searchMK').value.toLowerCase();
    const semesterFilter = document.getElementById('filterSemester').value;
    
    // Filter berdasarkan semester
    let filtered = semesterFilter 
        ? allNilaiData.filter(n => n.krsDetail.krs.semester.id === semesterFilter)
        : [...allNilaiData];
    
    // Filter berdasarkan search
    if (searchValue) {
        filtered = filtered.filter(n => 
            n.krsDetail.mataKuliah.kode_mk.toLowerCase().includes(searchValue) ||
            n.krsDetail.mataKuliah.nama_mk.toLowerCase().includes(searchValue)
        );
    }
    
    filteredNilaiData = filtered;
    renderTable();
    calculateIPK();
}

// Render Table
function renderTable() {
    const tbody = document.getElementById('tableNilai');
    const emptyState = document.getElementById('emptyState');
    const loadingState = document.getElementById('loadingState');
    
    loadingState.classList.add('hidden');
    
    if (filteredNilaiData.length === 0) {
        tbody.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }
    
    emptyState.classList.add('hidden');
    tbody.innerHTML = '';
    
    // Group by semester untuk tampilan yang lebih rapi
    const groupedBySemester = {};
    filteredNilaiData.forEach(nilai => {
        const semesterKey = nilai.krsDetail.krs.semester.id;
        if (!groupedBySemester[semesterKey]) {
            groupedBySemester[semesterKey] = {
                semester: nilai.krsDetail.krs.semester,
                data: []
            };
        }
        groupedBySemester[semesterKey].data.push(nilai);
    });
    
    // Render per semester
    Object.values(groupedBySemester).forEach(group => {
        group.data.forEach((nilai, index) => {
            const isFirstInGroup = index === 0;
            const rowspan = group.data.length;
            
            tbody.innerHTML += `
                <tr class="hover:bg-gray-50 transition-colors">
                    ${isFirstInGroup ? `
                        <td rowspan="${rowspan}" class="px-6 py-4 text-sm bg-blue-50 border-r border-gray-200">
                            <div class="font-semibold text-blue-900">${group.semester.nama_semester}</div>
                            <div class="text-xs text-blue-700">${group.semester.tahun_ajaran}</div>
                        </td>
                    ` : ''}
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium text-gray-900">${nilai.krsDetail.mataKuliah.nama_mk}</div>
                        <div class="text-xs text-gray-500">${nilai.krsDetail.mataKuliah.kode_mk}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-center text-gray-900">
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-semibold">
                            ${nilai.krsDetail.mataKuliah.sks}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-center text-gray-900">${nilai.krsDetail.kelas.kode_kelas}</td>
                    <td class="px-6 py-4 text-sm text-center text-gray-700">${nilai.krsDetail.kelas.dosen.nama_lengkap}</td>
                    <td class="px-6 py-4 text-sm text-center bg-yellow-50">
                        <span class="font-bold text-blue-700 text-lg">${nilai.nilai_akhir || '-'}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-center bg-green-50">
                        <span class="px-3 py-1 text-sm font-bold rounded-full ${getGradeColor(nilai.nilai_huruf)}">
                            ${nilai.nilai_huruf || '-'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-center bg-blue-50">
                        <span class="font-bold text-purple-700 text-lg">${nilai.nilai_mutu || '-'}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(nilai.status)}">
                            ${nilai.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        <button onclick='openDetailModal(${JSON.stringify(nilai).replace(/'/g, "&#39;")})'
                            class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-xs font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Detail
                        </button>
                    </td>
                </tr>
            `;
        });
    });
}

// Calculate IPK
function calculateIPK() {
    const ipkSection = document.getElementById('ipkSummary');
    
    if (filteredNilaiData.length === 0) {
        ipkSection.classList.add('hidden');
        return;
    }
    
    ipkSection.classList.remove('hidden');
    
    let totalSKS = 0;
    let totalSKSLulus = 0;
    let totalMutu = 0;
    
    filteredNilaiData.forEach(nilai => {
        const sks = parseInt(nilai.krsDetail.mataKuliah.sks);
        const mutu = parseFloat(nilai.nilai_mutu) || 0;
        
        totalSKS += sks;
        totalMutu += (sks * mutu);
        
        // SKS lulus jika grade bukan E atau D (tergantung aturan kampus)
        if (nilai.nilai_huruf && ['A', 'B', 'C'].includes(nilai.nilai_huruf)) {
            totalSKSLulus += sks;
        }
    });
    
    const ipk = totalSKS > 0 ? (totalMutu / totalSKS).toFixed(2) : '0.00';
    
    document.getElementById('totalSKS').textContent = totalSKS;
    document.getElementById('totalSKSLulus').textContent = totalSKSLulus;
    document.getElementById('ipkValue').textContent = ipk;
    document.getElementById('totalMK').textContent = filteredNilaiData.length;
}

// Helper Functions
function getGradeColor(grade) {
    const colors = {
        'A': 'bg-green-100 text-green-800',
        'B': 'bg-blue-100 text-blue-800',
        'C': 'bg-yellow-100 text-yellow-800',
        'D': 'bg-orange-100 text-orange-800',
        'E': 'bg-red-100 text-red-800'
    };
    return colors[grade] || 'bg-gray-100 text-gray-800';
}

function getStatusColor(status) {
    const colors = {
        'Final': 'bg-blue-100 text-blue-800',
        'Draft': 'bg-gray-100 text-gray-800',
        'Lulus': 'bg-green-100 text-green-800',
        'Tidak Lulus': 'bg-red-100 text-red-800'
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
}

function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('emptyState').classList.add('hidden');
    document.getElementById('tableNilai').innerHTML = '';
}

function showEmpty() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('emptyState').classList.remove('hidden');
    document.getElementById('tableNilai').innerHTML = '';
    document.getElementById('ipkSummary').classList.add('hidden');
}