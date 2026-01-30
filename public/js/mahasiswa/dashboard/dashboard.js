// ===== KONFIGURASI =====
const API_URL = '/graphql';

// ===== STATE MANAGEMENT =====
let currentMahasiswaData = null;
let currentSemesterAktif = null;

// ===== HELPER FUNCTIONS =====

/**
 * Mendapatkan ID mahasiswa dari mahasiswaProfile
 */
async function getCurrentMahasiswaId() {
    try {
        const query = `
        query {
            mahasiswaProfile {
                id
                nim
                nama_lengkap
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            return null;
        }

        if (result.data && result.data.mahasiswaProfile) {
            return result.data.mahasiswaProfile.id;
        }

        return null;
    } catch (error) {
        console.error('Error getting mahasiswa ID:', error);
        return null;
    }
}

/**
 * Mendapatkan semester aktif saat ini
 */
async function getCurrentSemester() {
    try {
        const query = `
        query {
            allSemester {
                id
                nama_semester
                tahun_ajaran
                status
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            credentials: 'same-origin',
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        
        if (result.data && result.data.allSemester) {
            // Cari semester dengan status aktif
            const semesterAktif = result.data.allSemester.find(s => s.status === 'Aktif');
            return semesterAktif || result.data.allSemester[0];
        }

        return null;
    } catch (error) {
        console.error('Error getting current semester:', error);
        return null;
    }
}

/**
 * Get CSRF Token
 */
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

/**
 * Update elemen dengan safety check
 */
function updateElement(selector, value) {
    const element = document.querySelector(selector);
    if (element) {
        element.textContent = value;
    }
}

/**
 * Update elemen HTML dengan safety check
 */
function updateElementHTML(selector, html) {
    const element = document.querySelector(selector);
    if (element) {
        element.innerHTML = html;
    }
}

/**
 * Format tanggal lengkap (Hari, Tanggal Bulan Tahun)
 */
function formatTanggalLengkap(date = new Date()) {
    const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    return `${hari[date.getDay()]}, ${date.getDate()} ${bulan[date.getMonth()]} ${date.getFullYear()}`;
}

// ===== GRAPHQL QUERIES =====

const MAHASISWA_FULL_QUERY = `
query {
    mahasiswaProfile {
        id
        nim
        nama_lengkap
        angkatan
        semester_saat_ini
        status
        jurusan {
            id
            nama_jurusan
            fakultas {
                id
                nama_fakultas
            }
        }
        krs {
            id
            semester {
                id
                nama_semester
                tahun_ajaran
            }
            krsDetail {
                id
                kelas {
                    id
                    nama_kelas
                    dosen {
                        id
                        nama_lengkap
                    }
                    mataKuliah {
                        id
                        kode_mk
                        nama_mk
                        sks
                        jenis
                    }
                    pertemuan {
                        id
                        pertemuan_ke
                        tanggal
                        waktu_mulai
                        waktu_selesai
                        materi
                        metode
                        status_pertemuan
                        link_daring
                        ruangan {
                            id
                            nama_ruangan
                        }
                    }
                }
            }
        }
    }
}`;

const KHS_QUERY = `
query($mahasiswaId: Int!) {
    khsByMahasiswa(mahasiswa_id: $mahasiswaId) {
        id
        semester {
            id
            nama_semester
            tahun_ajaran
        }
        sks_semester
        sks_kumulatif
        ip_semester
        ipk
    }
}`;

const NILAI_MAHASISWA_QUERY = `
query($mahasiswaId: ID!, $semesterId: ID!) {
    nilaiMahasiswaBySemester(mahasiswa_id: $mahasiswaId, semester_id: $semesterId) {
        id
        nilai_akhir
        nilai_huruf
        nilai_mutu
        krsDetail {
            id
            mataKuliah {
                id
                kode_mk
                nama_mk
            }
        }
    }
}`;

// ===== FETCH DATA FUNCTIONS =====

/**
 * Load data dashboard dari GraphQL API
 */
async function loadDashboard() {
    try {
        showLoading();
        
        // 1. Get Current Semester
        currentSemesterAktif = await getCurrentSemester();
        
        if (!currentSemesterAktif) {
            throw new Error('Semester aktif tidak ditemukan.');
        }

        // 2. Load Mahasiswa Profile dengan KRS
        const profileResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                query: MAHASISWA_FULL_QUERY
            })
        });

        const profileResult = await profileResponse.json();
        
        if (profileResult.errors) {
            console.error('GraphQL Errors:', profileResult.errors);
            throw new Error(profileResult.errors[0].message);
        }

        const mahasiswa = profileResult.data.mahasiswaProfile;
        currentMahasiswaData = mahasiswa;

        // 3. Load KHS (untuk IPK dan Total SKS)
        const khsResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                query: KHS_QUERY,
                variables: { mahasiswaId: parseInt(mahasiswa.id) }
            })
        });

        const khsResult = await khsResponse.json();
        const khsData = khsResult.data?.khsByMahasiswa || [];

        // 4. Filter KRS untuk semester aktif
        const krsData = mahasiswa.krs?.filter(krs => 
            krs.semester.id === currentSemesterAktif.id
        ) || [];

        // 5. Load Nilai Semester Aktif
        const nilaiResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                query: NILAI_MAHASISWA_QUERY,
                variables: { 
                    mahasiswaId: mahasiswa.id.toString(),
                    semesterId: currentSemesterAktif.id.toString()
                }
            })
        });

        const nilaiResult = await nilaiResponse.json();
        const nilaiData = nilaiResult.data?.nilaiMahasiswaBySemester || [];

        // Render semua komponen dashboard
        renderWelcomeBanner(mahasiswa, khsData);
        renderQuickStats(mahasiswa, khsData, krsData);
        renderMataKuliahSemesterIni(krsData, nilaiData);
        renderJadwalHariIni(krsData);
        
        hideLoading();
        
        console.log('Dashboard berhasil dimuat', {
            mahasiswa,
            khsData,
            krsData,
            nilaiData,
            semesterAktif: currentSemesterAktif
        });
        
    } catch (error) {
        console.error('Error loading dashboard:', error);
        showError(`Gagal memuat data dashboard: ${error.message}`);
        hideLoading();
    }
}

// ===== RENDER FUNCTIONS =====

/**
 * Render Welcome Banner
 */
function renderWelcomeBanner(mahasiswa, khsData) {
    // Hitung IPK dari KHS terakhir
    let ipk = 0;
    if (khsData && khsData.length > 0) {
        const latestKhs = khsData[khsData.length - 1];
        ipk = parseFloat(latestKhs.ipk) || 0;
    }
    
    // Update data mahasiswa
    updateElement('[data-mahasiswa-nama]', mahasiswa.nama_lengkap);
    updateElement('[data-mahasiswa-nim]', mahasiswa.nim || '-');
    updateElement('[data-mahasiswa-semester]', mahasiswa.semester_saat_ini || 1);
    updateElement('[data-mahasiswa-jurusan]', mahasiswa.jurusan?.nama_jurusan || '-');
    updateElement('[data-mahasiswa-angkatan]', mahasiswa.angkatan || '-');
    updateElement('[data-mahasiswa-ipk]', ipk.toFixed(2));
    
    // Update badge IPK
    const badgeElement = document.querySelector('[data-ipk-badge]');
    if (badgeElement) {
        if (ipk >= 3.75) {
            badgeElement.className = 'bg-yellow-400 text-yellow-900 text-xs px-3 py-1 rounded-full font-semibold';
            badgeElement.textContent = 'Cum Laude';
        } else if (ipk >= 3.50) {
            badgeElement.className = 'bg-green-400 text-green-900 text-xs px-3 py-1 rounded-full font-semibold';
            badgeElement.textContent = 'Sangat Memuaskan';
        } else if (ipk >= 3.00) {
            badgeElement.className = 'bg-blue-400 text-blue-900 text-xs px-3 py-1 rounded-full font-semibold';
            badgeElement.textContent = 'Memuaskan';
        } else if (ipk >= 2.75) {
            badgeElement.className = 'bg-cyan-400 text-cyan-900 text-xs px-3 py-1 rounded-full font-semibold';
            badgeElement.textContent = 'Baik';
        } else {
            badgeElement.className = 'bg-orange-400 text-orange-900 text-xs px-3 py-1 rounded-full font-semibold';
            badgeElement.textContent = 'Cukup';
        }
    }
}

/**
 * Render Quick Stats
 */
function renderQuickStats(mahasiswa, khsData, krsData) {
    // Total SKS dari KHS terakhir
    let totalSKS = 0;
    if (khsData && khsData.length > 0) {
        const latestKhs = khsData[khsData.length - 1];
        totalSKS = latestKhs.sks_kumulatif || 0;
    }
    
    const targetSKS = 144;
    const progressPercentage = Math.min((totalSKS / targetSKS) * 100, 100);
    
    // Total SKS
    updateElement('[data-stat-total-sks]', totalSKS);
    const progressBar = document.querySelector('[data-stat-progress]');
    if (progressBar) {
        progressBar.style.width = `${progressPercentage.toFixed(1)}%`;
    }
    
    // Semester Aktif
    updateElement('[data-stat-semester]', mahasiswa.semester_saat_ini || 1);
    
    // Mata Kuliah Aktif (KRS semester ini)
    let jumlahMK = 0;
    if (krsData && krsData.length > 0) {
        krsData.forEach(krs => {
            jumlahMK += krs.krsDetail?.length || 0;
        });
    }
    updateElement('[data-stat-mk-aktif]', jumlahMK);
    
    // Status Akademik
    updateElement('[data-stat-status]', mahasiswa.status || 'Tidak Diketahui');
    const statusBadge = document.querySelector('[data-stat-status-badge]');
    if (statusBadge) {
        if (mahasiswa.status === 'Aktif') {
            statusBadge.className = 'inline-block mt-2 bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full';
            statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Aktif';
        } else if (mahasiswa.status === 'Cuti') {
            statusBadge.className = 'inline-block mt-2 bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full';
            statusBadge.innerHTML = '<i class="fas fa-pause-circle mr-1"></i>Cuti';
        } else {
            statusBadge.className = 'inline-block mt-2 bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full';
            statusBadge.textContent = mahasiswa.status || 'Tidak Diketahui';
        }
    }
}

/**
 * Render Mata Kuliah Semester Ini
 */
function renderMataKuliahSemesterIni(krsData, nilaiData) {
    const tableBody = document.querySelector('[data-mk-semester-ini]');
    
    if (!tableBody) {
        console.warn('Element [data-mk-semester-ini] tidak ditemukan');
        return;
    }
    
    // Gabungkan semua krsDetail dari semua KRS
    let allKrsDetail = [];
    if (krsData && krsData.length > 0) {
        krsData.forEach(krs => {
            if (krs.krsDetail) {
                allKrsDetail = allKrsDetail.concat(krs.krsDetail);
            }
        });
    }
    
    if (allKrsDetail.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-8">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-3 block"></i>
                    <p class="text-gray-500 mb-4">Belum ada mata kuliah yang diambil semester ini</p>
                    <a href="/mahasiswa/krs" class="inline-block px-6 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition">
                        Isi KRS Sekarang
                    </a>
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = allKrsDetail.map(detail => {
        const kelas = detail.kelas;
        const mk = kelas?.mataKuliah;
        
        if (!mk) return '';
        
        // Cari nilai untuk mata kuliah ini
        const nilai = nilaiData.find(n => 
            n.krsDetail?.mataKuliah?.kode_mk === mk.kode_mk
        );
        
        let nilaiBadge = '<span class="text-xs text-gray-400">Belum ada</span>';
        if (nilai && nilai.nilai_huruf) {
            let badgeClass = 'bg-gray-100 text-gray-700';
            if (nilai.nilai_huruf === 'A') badgeClass = 'bg-green-100 text-green-700';
            else if (nilai.nilai_huruf.startsWith('B')) badgeClass = 'bg-blue-100 text-blue-700';
            else if (nilai.nilai_huruf.startsWith('C')) badgeClass = 'bg-yellow-100 text-yellow-700';
            else if (nilai.nilai_huruf === 'D' || nilai.nilai_huruf === 'E') badgeClass = 'bg-red-100 text-red-700';
            
            nilaiBadge = `<span class="inline-block px-3 py-1 rounded-full text-xs font-bold ${badgeClass}">${nilai.nilai_huruf}</span>`;
        }
        
        return `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-sm font-mono text-gray-600">${mk.kode_mk || '-'}</td>
                <td class="px-4 py-3">
                    <p class="text-sm font-semibold text-gray-800">${mk.nama_mk || '-'}</p>
                    <p class="text-xs text-gray-500">${mk.jenis || 'Wajib'}</p>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-block bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-semibold">
                        ${mk.sks || 0} SKS
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">${kelas?.dosen?.nama_lengkap || '-'}</td>
                <td class="px-4 py-3 text-sm text-gray-600">${kelas?.nama_kelas || '-'}</td>
                <td class="px-4 py-3 text-center">${nilaiBadge}</td>
            </tr>
        `;
    }).filter(Boolean).join('');
}

/**
 * Render Jadwal Hari Ini berdasarkan Pertemuan
 */
function renderJadwalHariIni(krsData) {
    const today = new Date();
    const todayString = today.toISOString().split('T')[0]; // Format: YYYY-MM-DD
    
    let pertemuanHariIni = [];
    
    if (krsData && krsData.length > 0) {
        krsData.forEach(krs => {
            if (krs.krsDetail) {
                krs.krsDetail.forEach(detail => {
                    const kelas = detail.kelas;
                    if (kelas && kelas.pertemuan) {
                        kelas.pertemuan.forEach(pertemuan => {
                            // Filter pertemuan berdasarkan tanggal hari ini
                            const pertemuanDate = new Date(pertemuan.tanggal).toISOString().split('T')[0];
                            
                            if (pertemuanDate === todayString) {
                                pertemuanHariIni.push({
                                    ...pertemuan,
                                    mataKuliah: kelas.mataKuliah,
                                    kelas: kelas,
                                    dosen: kelas.dosen
                                });
                            }
                        });
                    }
                });
            }
        });
    }
    
    const container = document.querySelector('[data-jadwal-hari-ini]');
    
    if (!container) {
        console.warn('Element [data-jadwal-hari-ini] tidak ditemukan');
        return;
    }
    
    if (pertemuanHariIni.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Tidak ada pertemuan kuliah hari ini</p>
                <p class="text-sm text-gray-400 mt-2">${formatTanggalLengkap()}</p>
            </div>
        `;
        return;
    }
    
    // Urutkan berdasarkan waktu_mulai
    pertemuanHariIni.sort((a, b) => {
        return a.waktu_mulai.localeCompare(b.waktu_mulai);
    });
    
    container.innerHTML = pertemuanHariIni.map(pertemuan => {
        // Tentukan warna border dan badge berdasarkan status_pertemuan
        let borderColor = 'border-emerald-500';
        let statusBadge = '';
        
        if (pertemuan.status_pertemuan === 'Selesai') {
            borderColor = 'border-gray-400';
            statusBadge = '<span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded ml-2">Selesai</span>';
        } else if (pertemuan.status_pertemuan === 'Dibatalkan') {
            borderColor = 'border-red-400';
            statusBadge = '<span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded ml-2">Dibatalkan</span>';
        } else if (pertemuan.status_pertemuan === 'Berlangsung') {
            borderColor = 'border-blue-500';
            statusBadge = '<span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded ml-2 animate-pulse">Sedang Berlangsung</span>';
        } else {
            statusBadge = '<span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-1 rounded ml-2">Dijadwalkan</span>';
        }
        
        // Badge metode pertemuan
        let metodeBadge = '';
        if (pertemuan.metode === 'Daring') {
            metodeBadge = '<span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded"><i class="fas fa-laptop mr-1"></i>Daring</span>';
        } else if (pertemuan.metode === 'Hybrid') {
            metodeBadge = '<span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded"><i class="fas fa-sync-alt mr-1"></i>Hybrid</span>';
        } else {
            metodeBadge = '<span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded"><i class="fas fa-chalkboard-teacher mr-1"></i>Tatap Muka</span>';
        }
        
        return `
            <div class="bg-gray-50 p-4 rounded-lg border-l-4 ${borderColor} hover:shadow-md transition">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">${pertemuan.mataKuliah?.nama_mk || '-'}</h4>
                        <p class="text-xs text-gray-500 mt-1">
                            Pertemuan ke-${pertemuan.pertemuan_ke || '-'}${statusBadge}
                        </p>
                    </div>
                    <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-1 rounded font-semibold">
                        ${pertemuan.mataKuliah?.sks || 0} SKS
                    </span>
                </div>
                
                <!-- Metode Pertemuan -->
                <div class="mb-2">
                    ${metodeBadge}
                </div>
                
                ${pertemuan.materi ? `
                    <div class="mb-2 bg-white px-3 py-2 rounded border border-gray-200">
                        <p class="text-xs text-gray-500">Materi:</p>
                        <p class="text-sm text-gray-700 font-medium">${pertemuan.materi}</p>
                    </div>
                ` : ''}
                
                ${pertemuan.metode === 'Daring' && pertemuan.link_daring ? `
                    <div class="mb-2">
                        <a href="${pertemuan.link_daring}" target="_blank" 
                           class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800">
                            <i class="fas fa-video mr-1"></i>
                            Join Meeting
                        </a>
                    </div>
                ` : ''}
                
                <p class="text-sm text-gray-600 mb-1">
                    <i class="fas fa-user mr-2"></i>${pertemuan.dosen?.nama_lengkap || '-'}
                </p>
                
                ${pertemuan.metode !== 'Daring' && pertemuan.ruangan ? `
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-door-open mr-2"></i>${pertemuan.ruangan?.nama_ruangan || '-'}
                    </p>
                ` : ''}
                
                <p class="text-sm text-gray-600 mb-1">
                    <i class="fas fa-users mr-2"></i>${pertemuan.kelas?.nama_kelas || '-'}
                </p>
                <p class="text-sm text-emerald-600 font-semibold">
                    <i class="fas fa-clock mr-2"></i>${pertemuan.waktu_mulai || '-'} - ${pertemuan.waktu_selesai || '-'}
                </p>
            </div>
        `;
    }).join('');
}

// ===== UI HELPER FUNCTIONS =====

function showLoading() {
    const loader = document.getElementById('dashboard-loader');
    if (loader) {
        loader.classList.remove('hidden');
    } else {
        const loaderHTML = `
            <div id="dashboard-loader" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg p-6 flex flex-col items-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-emerald-500 mb-4"></div>
                    <p class="text-gray-700 font-medium">Memuat data dashboard...</p>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', loaderHTML);
    }
}

function hideLoading() {
    const loader = document.getElementById('dashboard-loader');
    if (loader) {
        loader.remove();
    }
}

function showError(message) {
    const errorContainer = document.getElementById('error-notification');
    
    if (errorContainer) {
        errorContainer.innerHTML = `
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-3"></i>
                    <div>
                        <p class="font-bold">Error</p>
                        <p class="text-sm">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-auto">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        
        setTimeout(() => {
            if (errorContainer.firstElementChild) {
                errorContainer.firstElementChild.remove();
            }
        }, 10000);
    } else {
        alert(message);
    }
    
    console.error('Dashboard Error:', message);
}

// ===== INITIALIZE =====

document.addEventListener('DOMContentLoaded', () => {
    console.log('Dashboard script loaded');
    loadDashboard();
    
    // Auto refresh setiap 5 menit
    setInterval(loadDashboard, 5 * 60 * 1000);
});

// Refresh saat tab menjadi aktif kembali
document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
        console.log('Tab visible again, reloading dashboard...');
        loadDashboard();
    }
});

// Export fungsi untuk debugging
window.dashboardDebug = {
    reload: loadDashboard,
    getMahasiswaId: getCurrentMahasiswaId,
    getSemester: getCurrentSemester,
    data: () => currentMahasiswaData,
    semesterAktif: () => currentSemesterAktif
};