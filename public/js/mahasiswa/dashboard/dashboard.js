// ===== KONFIGURASI =====
const API_URL = '/graphql';

// ===== HELPER FUNCTIONS =====

/**
 * Mendapatkan ID mahasiswa dari berbagai sumber
 * Prioritas: URL params > data attribute > meta tag > localStorage > prompt user
 */
function getCurrentMahasiswaId() {
    // 1. Coba dari URL params (?mahasiswa_id=1)
    const urlParams = new URLSearchParams(window.location.search);
    const urlId = urlParams.get('mahasiswa_id');
    if (urlId) return parseInt(urlId); // PERBAIKAN: Parse ke integer
    
    // 2. Coba dari data attribute di body/container
    const dataId = document.body.dataset.mahasiswaId || 
                   document.querySelector('[data-mahasiswa-id]')?.dataset.mahasiswaId;
    if (dataId) return parseInt(dataId); // PERBAIKAN: Parse ke integer
    
    // 3. Coba dari meta tag
    const metaId = document.querySelector('meta[name="mahasiswa-id"]')?.content;
    if (metaId) return parseInt(metaId); // PERBAIKAN: Parse ke integer
    
    // 4. Coba dari localStorage
    const storageId = localStorage.getItem('mahasiswa_id');
    if (storageId) return parseInt(storageId); // PERBAIKAN: Parse ke integer
    
    // 5. Jika tidak ada, prompt user (untuk development/testing)
    const promptId = prompt('Masukkan ID Mahasiswa untuk testing:');
    if (promptId) {
        const parsedId = parseInt(promptId);
        localStorage.setItem('mahasiswa_id', parsedId);
        return parsedId;
    }
    
    return null;
}

/**
 * Menghitung IPK dari data KHS
 */
function hitungIPK(khsList) {
    if (!khsList || khsList.length === 0) return 0;
    
    let totalNilai = 0;
    let totalSKS = 0;
    
    khsList.forEach(khs => {
        if (khs.ip_semester && khs.sks_semester) {
            totalNilai += khs.ip_semester * khs.sks_semester;
            totalSKS += khs.sks_semester;
        }
    });
    
    return totalSKS > 0 ? (totalNilai / totalSKS) : 0;
}

/**
 * Menghitung total SKS dari KHS
 */
function hitungTotalSKS(khsList) {
    if (!khsList || khsList.length === 0) return 0;
    
    return khsList.reduce((total, khs) => {
        return total + (khs.sks_semester || 0);
    }, 0);
}

/**
 * Format tanggal ke format Indonesia
 */
function formatTanggalIndonesia(tanggalString) {
    const date = new Date(tanggalString);
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    return `${date.getDate()} ${bulan[date.getMonth()]} ${date.getFullYear()}`;
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
 * Get CSRF Token
 */
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

// ===== GRAPHQL QUERIES =====

// PERBAIKAN: Ubah tipe dari ID! menjadi Int!
const DASHBOARD_QUERY = `
query ($id: Int!){
  mahasiswa(id: $id) {
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
                  jadwalKuliah {
                      id
                      hari
                      jam_mulai
                      jam_selesai
                      ruangan {
                          id
                          nama_ruangan
                      }
                  }
              }
              mataKuliah {
                  id
                  kode_mk
                  nama_mk
                  sks
                  jenis
              }
              nilai {
                  id
                  nilai_huruf
                  nilai_mutu
              }
          }
      }
      khs {
          id
          semester {
              id
              nama_semester
          }
          ip_semester
          sks_semester
      }
  }
}   
`;

// ===== FETCH DATA FUNCTIONS =====

/**
 * Load data dashboard dari GraphQL API
 */
async function loadDashboard() {
    try {
        const mahasiswaId = getCurrentMahasiswaId();
        
        if (!mahasiswaId) {
            showError('ID Mahasiswa tidak ditemukan. Silakan login kembali.');
            return;
        }
        
        // Validasi bahwa ID adalah integer
        if (!Number.isInteger(mahasiswaId)) {
            showError('ID Mahasiswa tidak valid. Harus berupa angka.');
            return;
        }
        
        showLoading();
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                query: DASHBOARD_QUERY,
                variables: { id: mahasiswaId } // Sudah dalam format integer
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            throw new Error(result.errors[0].message);
        }

        if (!result.data || !result.data.mahasiswa) {
            throw new Error('Data mahasiswa tidak ditemukan');
        }

        const data = result.data;
        console.log('Dashboard data loaded:', data);
        
        // Render semua komponen dashboard
        renderWelcomeBanner(data.mahasiswa);
        renderQuickStats(data.mahasiswa);
        renderMataKuliahSemesterIni(data.mahasiswa);
        renderJadwalHariIni(data.mahasiswa);
        
        hideLoading();
        
        console.log('Dashboard berhasil dimuat');
        
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
function renderWelcomeBanner(mahasiswa) {
    const ipk = hitungIPK(mahasiswa.khs);
    
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
        if (ipk >= 3.5) {
            badgeElement.className = 'bg-yellow-400 text-yellow-900 text-xs px-3 py-1 rounded-full font-semibold';
            badgeElement.textContent = 'Cumlaude';
        } else if (ipk >= 3.0) {
            badgeElement.className = 'bg-green-400 text-green-900 text-xs px-3 py-1 rounded-full font-semibold';
            badgeElement.textContent = 'Sangat Baik';
        } else if (ipk >= 2.5) {
            badgeElement.className = 'bg-blue-400 text-blue-900 text-xs px-3 py-1 rounded-full font-semibold';
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
function renderQuickStats(mahasiswa) {
    const totalSKS = hitungTotalSKS(mahasiswa.khs);
    const targetSKS = 144; // Sesuaikan dengan target SKS program studi
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
    const krsAktif = mahasiswa.krs?.find(krs => {
        // Cari semester yang sesuai dengan semester_saat_ini
        const semesterNumber = parseInt(krs.semester?.nama_semester?.match(/\d+/)?.[0]);
        return semesterNumber === mahasiswa.semester_saat_ini;
    });
    const jumlahMK = krsAktif ? krsAktif.krsDetail.length : 0;
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
function renderMataKuliahSemesterIni(mahasiswa) {
    const krsAktif = mahasiswa.krs?.find(krs => {
        // Cari semester yang sesuai dengan semester_saat_ini
        const semesterNumber = parseInt(krs.semester?.nama_semester?.match(/\d+/)?.[0]);
        return semesterNumber === mahasiswa.semester_saat_ini;
    });
    
    const tableBody = document.querySelector('[data-mk-semester-ini]');
    
    if (!tableBody) {
        console.warn('Element [data-mk-semester-ini] tidak ditemukan');
        return;
    }
    
    if (!krsAktif || !krsAktif.krsDetail || krsAktif.krsDetail.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-8">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-3 block"></i>
                    <p class="text-gray-500 mb-4">Belum ada mata kuliah yang diambil semester ini</p>
                    <a href="/krs" class="inline-block px-6 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition">
                        Isi KRS Sekarang
                    </a>
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = krsAktif.krsDetail.map(detail => {
        const mk = detail.mataKuliah;
        const kelas = detail.kelas;
        const nilai = detail.nilai;
        
        let nilaiBadge = '<span class="text-xs text-gray-400">Belum ada</span>';
        if (nilai && nilai.nilai_huruf) {
            let badgeClass = 'bg-gray-100 text-gray-700';
            if (nilai.nilai_huruf === 'A') badgeClass = 'bg-green-100 text-green-700';
            else if (nilai.nilai_huruf === 'B') badgeClass = 'bg-blue-100 text-blue-700';
            else if (nilai.nilai_huruf === 'C') badgeClass = 'bg-yellow-100 text-yellow-700';
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
    }).join('');
}

/**
 * Render Jadwal Hari Ini
 */
function renderJadwalHariIni(mahasiswa) {
    const hariIni = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][new Date().getDay()];
    
    // Kumpulkan semua jadwal dari KRS aktif
    const krsAktif = mahasiswa.krs?.find(krs => {
        const semesterNumber = parseInt(krs.semester?.nama_semester?.match(/\d+/)?.[0]);
        return semesterNumber === mahasiswa.semester_saat_ini;
    });
    
    let jadwalHariIni = [];
    if (krsAktif && krsAktif.krsDetail) {
        krsAktif.krsDetail.forEach(detail => {
            if (detail.kelas && detail.kelas.jadwalKuliah) {
                detail.kelas.jadwalKuliah.forEach(jadwal => {
                    if (jadwal.hari === hariIni) {
                        jadwalHariIni.push({
                            ...jadwal,
                            mataKuliah: detail.mataKuliah,
                            kelas: detail.kelas
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
    
    if (jadwalHariIni.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Tidak ada jadwal kuliah hari ini</p>
                <p class="text-sm text-gray-400 mt-2">${formatTanggalLengkap()}</p>
            </div>
        `;
        return;
    }
    
    // Urutkan berdasarkan jam_mulai
    jadwalHariIni.sort((a, b) => {
        return a.jam_mulai.localeCompare(b.jam_mulai);
    });
    
    container.innerHTML = jadwalHariIni.map(jadwal => `
        <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-emerald-500 hover:shadow-md transition">
            <div class="flex justify-between items-start mb-2">
                <h4 class="font-semibold text-gray-800">${jadwal.mataKuliah?.nama_mk || '-'}</h4>
                <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-1 rounded font-semibold">
                    ${jadwal.mataKuliah?.sks || 0} SKS
                </span>
            </div>
            <p class="text-sm text-gray-600 mb-1">
                <i class="fas fa-user mr-2"></i>${jadwal.kelas?.dosen?.nama_lengkap || '-'}
            </p>
            <p class="text-sm text-gray-600 mb-1">
                <i class="fas fa-door-open mr-2"></i>${jadwal.ruangan?.nama_ruangan || '-'}
            </p>
            <p class="text-sm text-gray-600 mb-1">
                <i class="fas fa-users mr-2"></i>${jadwal.kelas?.nama_kelas || '-'}
            </p>
            <p class="text-sm text-emerald-600 font-semibold">
                <i class="fas fa-clock mr-2"></i>${jadwal.jam_mulai || '-'} - ${jadwal.jam_selesai || '-'}
            </p>
        </div>
    `).join('');
}

// ===== UI HELPER FUNCTIONS =====

function showLoading() {
    const loader = document.getElementById('dashboard-loader');
    if (loader) {
        loader.classList.remove('hidden');
    } else {
        // Buat loader sederhana jika belum ada
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
        loader.classList.add('hidden');
        // Atau hapus dari DOM
        // loader.remove();
    }
}

function showError(message) {
    // Gunakan alert sederhana atau implement notifikasi kustom
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
        
        // Auto hide after 10 seconds
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

// Load dashboard saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    console.log('Dashboard script loaded');
    console.log('Mahasiswa ID:', getCurrentMahasiswaId());
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

// Export fungsi untuk debugging di console
window.dashboardDebug = {
    reload: loadDashboard,
    getMahasiswaId: getCurrentMahasiswaId,
    API_URL: API_URL,
    testQuery: () => {
        const id = getCurrentMahasiswaId();
        console.log('Testing with ID:', id, 'Type:', typeof id);
        return fetch(API_URL, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                query: DASHBOARD_QUERY,
                variables: { id: id }
            })
        }).then(r => r.json()).then(console.log);
    }
};