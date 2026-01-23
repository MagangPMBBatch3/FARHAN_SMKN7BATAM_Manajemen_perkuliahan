// ============================================================================
// CONFIGURATION & GLOBAL STATE
// ============================================================================

const API_URL = "/graphql";

let allKrsHistory = [];
let filteredHistory = [];
let mahasiswaData = null;

// ============================================================================
// INITIALIZATION
// ============================================================================

document.addEventListener('DOMContentLoaded', async () => {
    await loadMahasiswaData();
    await loadKrsHistory();
});

// ============================================================================
// DATA LOADING
// ============================================================================

async function loadMahasiswaData() {
    const userId = getUserIdFromSession();
    
    const query = `query($userId: ID!) {
        mahasiswaByUserId(user_id: $userId) {
            id nim nama_lengkap semester_saat_ini ipk
            jurusan { id nama_jurusan }
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { userId }
            })
        });

        const result = await response.json();
        
        if (result.errors) throw new Error(result.errors[0].message);
        
        mahasiswaData = result.data.mahasiswaByUserId;
        
        renderMahasiswaStats();
        
    } catch (error) {
        console.error('Error loading mahasiswa data:', error);
        showNotification('Gagal memuat data mahasiswa', 'error');
    }
}

async function loadKrsHistory() {
    if (!mahasiswaData) return;

    const query = `query($mahasiswaId: ID!) {
        krsHistoryByMahasiswa(mahasiswa_id: $mahasiswaId) {
            id mahasiswa_id semester_id tanggal_pengisian 
            tanggal_persetujuan status total_sks catatan
            semester { id nama_semester tahun_ajaran }
            dosenPa { id nama_lengkap }
            krsDetail {
                id krs_id kelas_id mata_kuliah_id sks status_ambil
                kelas {
                    id nama_kelas
                    dosen { id nama_lengkap }
                    jadwalKuliah {
                        id hari jam_mulai jam_selesai
                        ruangan { id nama_ruangan }
                    }
                }
                mataKuliah { 
                    id kode_mk nama_mk sks semester_rekomendasi 
                }
                nilai { 
                    id nilai_akhir nilai_huruf nilai_mutu 
                }
            }
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query,
                variables: { mahasiswaId: mahasiswaData.id }
            })
        });

        const result = await response.json();
        
        if (result.errors) throw new Error(result.errors[0].message);
        
        allKrsHistory = result.data.krsHistoryByMahasiswa || [];
        
        // Sort by semester descending (newest first)
        allKrsHistory.sort((a, b) => {
            const semA = parseInt(a.semester?.nama_semester?.replace(/\D/g, '') || 0);
            const semB = parseInt(b.semester?.nama_semester?.replace(/\D/g, '') || 0);
            return semB - semA;
        });
        
        filteredHistory = [...allKrsHistory];
        
        populateSemesterFilter();
        renderKrsHistory();
        calculateStats();
        
    } catch (error) {
        console.error('Error loading KRS history:', error);
        showNotification('Gagal memuat riwayat KRS', 'error');
    }
}

// ============================================================================
// RENDERING
// ============================================================================

function renderMahasiswaStats() {
    if (!mahasiswaData) return;

    setContent('ipkDisplay', (mahasiswaData.ipk || 0).toFixed(2));
    setContent('semesterCurrent', mahasiswaData.semester_saat_ini || '-');
}

function calculateStats() {
    const totalSemester = allKrsHistory.filter(krs => 
        krs.status === 'DISETUJUI'
    ).length;
    
    let totalSksLulus = 0;
    
    allKrsHistory.forEach(krs => {
        if (krs.status === 'DISETUJUI' && krs.krsDetail) {
            krs.krsDetail.forEach(detail => {
                const nilaiHuruf = detail.nilai?.nilai_huruf;
                // SKS lulus jika nilai >= C
                if (nilaiHuruf && !['D', 'E'].includes(nilaiHuruf)) {
                    totalSksLulus += detail.sks || 0;
                }
            });
        }
    });

    setContent('totalSemester', totalSemester);
    setContent('totalSksLulus', totalSksLulus);
}

function populateSemesterFilter() {
    const filterSelect = document.getElementById('filterSemester');
    if (!filterSelect) return;

    // Get unique semesters
    const semesters = [...new Set(allKrsHistory.map(krs => 
        JSON.stringify({
            id: krs.semester?.id,
            nama: krs.semester?.nama_semester,
            tahun: krs.semester?.tahun_ajaran
        })
    ))].map(s => JSON.parse(s));

    filterSelect.innerHTML = '<option value="">Semua Semester</option>';
    
    semesters.forEach(sem => {
        const option = document.createElement('option');
        option.value = sem.id;
        option.textContent = `${sem.nama} - ${sem.tahun}`;
        filterSelect.appendChild(option);
    });
}

function renderKrsHistory() {
    const container = document.getElementById('historyContainer');
    const emptyState = document.getElementById('emptyHistory');
    
    if (!container) return;

    if (filteredHistory.length === 0) {
        container.innerHTML = '';
        emptyState?.classList.remove('hidden');
        return;
    }

    emptyState?.classList.add('hidden');

    container.innerHTML = filteredHistory.map(krs => {
        const statusBadge = getStatusBadge(krs.status);
        const jumlahMk = krs.krsDetail?.length || 0;
        const totalSks = krs.total_sks || 0;
        
        // Calculate IP Semester if available
        let ipSemester = '-';
        if (krs.status === 'DISETUJUI' && krs.krsDetail) {
            const { totalMutu, totalSks: sksValid } = calculateIpSemester(krs.krsDetail);
            if (sksValid > 0) {
                ipSemester = (totalMutu / sksValid).toFixed(2);
            }
        }

        return `
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    ${krs.semester?.nama_semester} - ${krs.semester?.tahun_ajaran}
                                </h3>
                                ${statusBadge}
                            </div>
                            <div class="flex items-center gap-6 text-sm text-gray-600">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Diisi: ${formatDate(krs.tanggal_pengisian)}</span>
                                </div>
                                ${krs.tanggal_persetujuan ? `
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Disetujui: ${formatDate(krs.tanggal_persetujuan)}</span>
                                    </div>
                                ` : ''}
                            </div>
                            ${krs.catatan ? `
                                <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-sm text-yellow-800 rounded-r">
                                    <p class="font-medium">Catatan:</p>
                                    <p>${krs.catatan}</p>
                                </div>
                            ` : ''}
                        </div>
                        <button onclick="viewKrsDetail(${krs.id})" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat Detail
                        </button>
                    </div>

                    <div class="grid grid-cols-4 gap-4 pt-4 border-t border-gray-100">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-1">Mata Kuliah</p>
                            <p class="text-2xl font-bold text-gray-900">${jumlahMk}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-1">Total SKS</p>
                            <p class="text-2xl font-bold text-gray-900">${totalSks}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-1">IP Semester</p>
                            <p class="text-2xl font-bold text-gray-900">${ipSemester}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-1">Dosen PA</p>
                            <p class="text-sm font-medium text-gray-900">${krs.dosenPa?.nama_lengkap || '-'}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// ============================================================================
// FILTERS
// ============================================================================

function filterKrsHistory() {
    const semesterFilter = document.getElementById('filterSemester')?.value;
    const statusFilter = document.getElementById('filterStatus')?.value;

    filteredHistory = allKrsHistory.filter(krs => {
        let match = true;

        if (semesterFilter && krs.semester_id != semesterFilter) {
            match = false;
        }

        if (statusFilter && krs.status !== statusFilter) {
            match = false;
        }

        return match;
    });

    renderKrsHistory();
}

// ============================================================================
// VIEW DETAIL
// ============================================================================

function viewKrsDetail(krsId) {
    const krs = allKrsHistory.find(k => k.id == krsId);
    if (!krs) return;

    // Populate modal
    const modal = document.getElementById('modalDetailHistory');
    if (!modal) return;

    // Header info
    setContent('detailSemester', `${krs.semester?.nama_semester} - ${krs.semester?.tahun_ajaran}`);
    setContent('detailStatus', krs.status || '-');
    setContent('detailTanggalPengisian', formatDate(krs.tanggal_pengisian));
    setContent('detailTanggalPersetujuan', krs.tanggal_persetujuan ? formatDate(krs.tanggal_persetujuan) : '-');
    setContent('detailDosenPa', krs.dosenPa?.nama_lengkap || '-');
    setContent('detailTotalSks', krs.total_sks || 0);
    setContent('detailCatatan', krs.catatan || '-');

    // Calculate IP
    const { totalMutu, totalSks: sksValid } = calculateIpSemester(krs.krsDetail || []);
    const ipSemester = sksValid > 0 ? (totalMutu / sksValid).toFixed(2) : '-';
    setContent('detailIpSemester', ipSemester);

    // Render table
    renderDetailTable(krs.krsDetail || []);

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal() {
    document.getElementById('modalDetailHistory')?.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function renderDetailTable(krsDetails) {
    const tbody = document.getElementById('detailTableBody');
    if (!tbody) return;

    tbody.innerHTML = krsDetails.map((detail, index) => {
        const mk = detail.mataKuliah;
        const kelas = detail.kelas;
        const nilai = detail.nilai;

        const jadwal = kelas?.jadwalKuliah?.map(j =>
            `${j.hari}, ${j.jam_mulai}-${j.jam_selesai}`
        ).join('<br>') || '-';

        const nilaiText = nilai ? 
            `${nilai.nilai_huruf} (${nilai.nilai_akhir})` : '-';
        
        const nilaiColor = getNilaiColor(nilai?.nilai_huruf);

        return `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4 text-sm">${index + 1}</td>
                <td class="px-6 py-4">
                    <div class="font-medium">${mk?.nama_mk || '-'}</div>
                    <div class="text-sm text-gray-500">${mk?.kode_mk || '-'}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm">${kelas?.nama_kelas || '-'}</div>
                    <div class="text-xs text-gray-500">${jadwal}</div>
                </td>
                <td class="px-6 py-4 text-sm">${kelas?.dosen?.nama_lengkap || '-'}</td>
                <td class="px-6 py-4 text-sm text-center">${detail.sks || 0}</td>
                <td class="px-6 py-4 text-center">
                    <span class="font-semibold ${nilaiColor}">${nilaiText}</span>
                </td>
            </tr>
        `;
    }).join('');
}

// ============================================================================
// CALCULATIONS
// ============================================================================

function calculateIpSemester(krsDetails) {
    let totalMutu = 0;
    let totalSks = 0;

    krsDetails.forEach(detail => {
        const nilai = detail.nilai;
        if (nilai && nilai.nilai_mutu !== null) {
            totalMutu += (nilai.nilai_mutu * detail.sks);
            totalSks += detail.sks;
        }
    });

    return { totalMutu, totalSks };
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

function setContent(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
}

function getStatusBadge(status) {
    const badges = {
        'DRAFT': 'bg-gray-100 text-gray-800',
        'DIAJUKAN': 'bg-yellow-100 text-yellow-800',
        'DISETUJUI': 'bg-green-100 text-green-800',
        'DITOLAK': 'bg-red-100 text-red-800'
    };
    const cls = badges[status?.toUpperCase()] || 'bg-gray-100 text-gray-800';
    return `<span class="${cls} px-3 py-1 rounded-full text-xs font-semibold">${status || '-'}</span>`;
}

function getNilaiColor(nilai) {
    if (!nilai || nilai === '-') return 'text-gray-600';
    if (['A', 'A-'].includes(nilai)) return 'text-green-600';
    if (['B+', 'B', 'B-'].includes(nilai)) return 'text-blue-600';
    if (['C+', 'C'].includes(nilai)) return 'text-yellow-600';
    if (['D', 'E'].includes(nilai)) return 'text-red-600';
    return 'text-gray-600';
}

function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        return new Date(dateString).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    } catch {
        return '-';
    }
}

function showNotification(message, type = 'info') {
    const bgColor = type === 'success' ? 'bg-green-500' :
                     type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-md`;
    toast.innerHTML = `
        <div class="flex items-start gap-3">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

function getUserIdFromSession() {
    return document.querySelector('meta[name="user-id"]')?.content || '1';
}