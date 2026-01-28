const API_URL = "/graphql";
let currentPage = 1;
let allPertemuanData = [];
let isLoading = false;
let hasMorePages = true;

// ==================== LOAD DATA ====================
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

async function graphqlFetch(query, variables = {}) {
    const response = await fetch(API_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify({ query, variables })
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    return await response.json();
}

async function loadPertemuanData(page = 1, append = false) {
    if (isLoading) return;
    
    isLoading = true;
    currentPage = page;
    
    // Show loading indicator
    showLoadingIndicator();
    
    const perPage = parseInt(document.getElementById("perPage")?.value || 20);
    const searchValue = document.getElementById("search")?.value.trim() || "";
    const filterSemester = document.getElementById("filterSemester")?.value || null;
    const filterStatus = document.getElementById("filterStatus")?.value || null;

    try {
        const query = `
        query($first: Int, $page: Int, $search: String, $semester_id: Int, $status_pertemuan: StatusPertemuan) {
            pertemuanMahasiswa(
                first: $first
                page: $page
                search: $search
                semester_id: $semester_id
                status_pertemuan: $status_pertemuan
            ) {
                data {
                    id
                    kelas_id
                    pertemuan_ke
                    tanggal
                    waktu_mulai
                    waktu_selesai
                    materi
                    metode
                    status_pertemuan
                    link_daring
                    catatan
                    kelas {
                        kode_kelas
                        nama_kelas
                        mataKuliah {
                            kode_mk
                            nama_mk
                            sks
                        }
                        dosen {
                            nama_lengkap
                        }
                        semester {
                            id
                            nama_semester
                            tahun_ajaran
                        }
                    }
                    ruangan {
                        kode_ruangan
                        nama_ruangan
                        gedung
                    }
                }
                paginatorInfo {
                    currentPage
                    lastPage
                    total
                    hasMorePages
                    perPage
                }
            }
        }`;

        const result = await graphqlFetch(query, {
            first: perPage,
            page: page,
            search: searchValue,
            semester_id: filterSemester ? parseInt(filterSemester) : null,
            status_pertemuan: filterStatus
        });

        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            showError('Gagal memuat data pertemuan: ' + (result.errors[0]?.message || 'Unknown error'));
            return;
        }

        const data = result.data.pertemuanMahasiswa;
        
        if (append) {
            allPertemuanData = [...allPertemuanData, ...(data.data || [])];
        } else {
            allPertemuanData = data.data || [];
        }

        hasMorePages = data.paginatorInfo.hasMorePages;

        renderPertemuanCards(allPertemuanData, append);
        updatePagination(data.paginatorInfo);
        
        // Only update stats on initial load or filter change
        if (!append) {
            updateStats(allPertemuanData);
        }

    } catch (error) {
        console.error('Error loading data:', error);
        showError('Terjadi kesalahan saat memuat data: ' + error.message);
    } finally {
        isLoading = false;
        hideLoadingIndicator();
    }
}

async function loadSemesterOptions() {
    const query = `
    query {
        semesterMahasiswa {
            id
            nama_semester
            tahun_ajaran
        }
    }`;

    try {
        const result = await graphqlFetch(query);

        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            return;
        }

        const semesterList = result.data.semesterMahasiswa || [];

        const select = document.getElementById('filterSemester');
        select.innerHTML = '<option value="">Semua Semester</option>';
        semesterList.forEach(semester => {
            select.innerHTML += `<option value="${semester.id}">${semester.nama_semester} (${semester.tahun_ajaran})</option>`;
        });

    } catch (error) {
        console.error('Error loading semester:', error);
    }
}

// ==================== RENDER FUNCTIONS ====================

function renderPertemuanCards(data, append = false) {
    const container = document.getElementById('pertemuanContainer');
    const emptyState = document.getElementById('emptyState');

    if (!data || data.length === 0) {
        if (!append) {
            container.innerHTML = '';
            emptyState.classList.remove('hidden');
        }
        return;
    }

    emptyState.classList.add('hidden');
    
    const cardsHTML = data.map(item => createPertemuanCard(item)).join('');
    
    if (append) {
        container.insertAdjacentHTML('beforeend', cardsHTML);
    } else {
        container.innerHTML = cardsHTML;
    }
}

function createPertemuanCard(item) {
    const statusBadge = getStatusBadge(item.status_pertemuan);
    const metodeBadge = getMetodeBadge(item.metode);
    const isUpcoming = isUpcomingClass(item.tanggal, item.waktu_mulai);
    
    return `
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden pertemuan-card">
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="px-2.5 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                ${item.kelas.kode_kelas}
                            </span>
                            ${metodeBadge}
                            ${statusBadge}
                            ${isUpcoming ? '<span class="px-2.5 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full animate-pulse">🔔 Segera Dimulai</span>' : ''}
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">
                            ${item.kelas.mataKuliah.nama_mk}
                        </h3>
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">${item.kelas.mataKuliah.kode_mk}</span> • 
                            ${item.kelas.mataKuliah.sks} SKS • 
                            Dosen: ${item.kelas.dosen.nama_lengkap}
                        </p>
                    </div>
                    <div class="text-right ml-4">
                        <div class="text-2xl font-bold text-blue-600">
                            ${item.pertemuan_ke}
                        </div>
                        <div class="text-xs text-gray-500">Pertemuan</div>
                    </div>
                </div>

                ${item.materi ? `
                    <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs font-medium text-gray-500 mb-1">📚 Materi:</p>
                        <p class="text-sm text-gray-900 font-medium">${item.materi}</p>
                    </div>
                ` : ''}

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="truncate">${formatDate(item.tanggal)}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>${item.waktu_mulai} - ${item.waktu_selesai}</span>
                    </div>
                </div>

                ${item.ruangan ? `
                    <div class="flex items-center text-sm text-gray-600 mb-3">
                        <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="truncate">${item.ruangan.gedung} - ${item.ruangan.nama_ruangan} (${item.ruangan.kode_ruangan})</span>
                    </div>
                ` : ''}

                <div class="flex gap-2">
                    ${item.link_daring ? `
                        <a href="${item.link_daring}" target="_blank"
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Join Meeting
                        </a>
                    ` : ''}
                    <button onclick='openDetailModal(${JSON.stringify(item).replace(/'/g, "&#39;")})' 
                        class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Detail
                    </button>
                </div>
            </div>
        </div>
    `;
}

function updateStats(data) {
    // Count unique classes
    const uniqueClasses = new Set(data.map(item => item.kelas_id));
    document.getElementById('totalKelas').textContent = uniqueClasses.size;

    // Count by status
    const selesai = data.filter(item => item.status_pertemuan === 'Selesai').length;
    const dijadwalkan = data.filter(item => item.status_pertemuan === 'Dijadwalkan').length;

    document.getElementById('totalSelesai').textContent = selesai;
    document.getElementById('totalDijadwalkan').textContent = dijadwalkan;
    document.getElementById('totalPertemuan').textContent = data.length;
}

function updatePagination(pageInfo) {
    if (pageInfo) {
        document.getElementById('pageInfo').textContent = 
            `Menampilkan ${allPertemuanData.length} dari ${pageInfo.total} pertemuan`;
        
        // Update load more button
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (loadMoreBtn) {
            if (pageInfo.hasMorePages) {
                loadMoreBtn.classList.remove('hidden');
            } else {
                loadMoreBtn.classList.add('hidden');
            }
        }
    }
}

// ==================== INFINITE SCROLL ====================

function setupInfiniteScroll() {
    const scrollContainer = document.getElementById('pertemuanScrollContainer');
    
    if (!scrollContainer) return;
    
    scrollContainer.addEventListener('scroll', () => {
        const { scrollTop, scrollHeight, clientHeight } = scrollContainer;
        
        // Check if user has scrolled to bottom (with 100px threshold)
        if (scrollTop + clientHeight >= scrollHeight - 100) {
            if (!isLoading && hasMorePages) {
                loadPertemuanData(currentPage + 1, true);
            }
        }
    });
}

function loadMoreData() {
    if (!isLoading && hasMorePages) {
        loadPertemuanData(currentPage + 1, true);
    }
}

// ==================== LOADING INDICATOR ====================

function showLoadingIndicator() {
    const loadingEl = document.getElementById('loadingIndicator');
    if (loadingEl) {
        loadingEl.classList.remove('hidden');
    }
}

function hideLoadingIndicator() {
    const loadingEl = document.getElementById('loadingIndicator');
    if (loadingEl) {
        loadingEl.classList.add('hidden');
    }
}

// ==================== HELPER FUNCTIONS ====================

function getStatusBadge(status) {
    const badges = {
        'Dijadwalkan': '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">📅 Dijadwalkan</span>',
        'Berlangsung': '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">▶️ Berlangsung</span>',
        'Selesai': '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">✅ Selesai</span>',
        'Dibatalkan': '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">❌ Dibatalkan</span>'
    };
    return badges[status] || status;
}

function getMetodeBadge(metode) {
    const badges = {
        'Tatap Muka': '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">🏫 Tatap Muka</span>',
        'Daring': '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">💻 Daring</span>',
        'Hybrid': '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">🔄 Hybrid</span>',
        'TatapMuka': '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">🏫 Tatap Muka</span>'
    };
    return badges[metode] || metode;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('id-ID', options);
}

function formatTime(timeString) {
    if (!timeString) return '-';
    return timeString.substring(0, 5); // HH:MM
}

function isUpcomingClass(tanggal, waktuMulai) {
    const now = new Date();
    const classDateTime = new Date(`${tanggal}T${waktuMulai}`);
    const diffMs = classDateTime - now;
    const diffHours = diffMs / (1000 * 60 * 60);
    
    // Return true if class is within next 2 hours
    return diffHours > 0 && diffHours <= 2;
}

function showError(message) {
    alert(message);
}

// ==================== SEARCH & FILTER ====================

let searchTimeout;
function searchPertemuan() {
    // Debounce search
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        currentPage = 1;
        hasMorePages = true;
        loadPertemuanData(1, false);
    }, 300);
}

// ==================== DETAIL MODAL ====================

function openDetailModal(item) {
    // Fill modal with data
    document.getElementById('detailMataKuliah').textContent = item.kelas.mataKuliah.nama_mk;
    document.getElementById('detailKodeMK').textContent = item.kelas.mataKuliah.kode_mk;
    document.getElementById('detailKelas').textContent = `${item.kelas.kode_kelas} - ${item.kelas.nama_kelas}`;
    document.getElementById('detailDosen').textContent = item.kelas.dosen.nama_lengkap;
    document.getElementById('detailSemester').textContent = `${item.kelas.semester.nama_semester} (${item.kelas.semester.tahun_ajaran})`;
    document.getElementById('detailPertemuan').textContent = `Pertemuan ke-${item.pertemuan_ke}`;
    document.getElementById('detailTanggal').textContent = formatDate(item.tanggal);
    document.getElementById('detailWaktu').textContent = `${item.waktu_mulai} - ${item.waktu_selesai}`;
    document.getElementById('detailMateri').textContent = item.materi || '-';
    document.getElementById('detailMetode').innerHTML = getMetodeBadge(item.metode);
    document.getElementById('detailStatus').innerHTML = getStatusBadge(item.status_pertemuan);
    
    // Ruangan
    if (item.ruangan) {
        document.getElementById('detailRuanganContainer').classList.remove('hidden');
        document.getElementById('detailRuangan').textContent = 
            `${item.ruangan.gedung} - ${item.ruangan.nama_ruangan} (${item.ruangan.kode_ruangan})`;
    } else {
        document.getElementById('detailRuanganContainer').classList.add('hidden');
    }
    
    // Link Daring
    if (item.link_daring) {
        document.getElementById('detailLinkContainer').classList.remove('hidden');
        document.getElementById('detailLink').href = item.link_daring;
        document.getElementById('detailLink').textContent = item.link_daring;
    } else {
        document.getElementById('detailLinkContainer').classList.add('hidden');
    }
    
    // Catatan
    if (item.catatan) {
        document.getElementById('detailCatatanContainer').classList.remove('hidden');
        document.getElementById('detailCatatan').textContent = item.catatan;
    } else {
        document.getElementById('detailCatatanContainer').classList.add('hidden');
    }
    
    // Show modal
    document.getElementById('modalDetail').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}

// ==================== INIT ====================

document.addEventListener("DOMContentLoaded", async () => {
    await loadSemesterOptions();
    loadPertemuanData();
    setupInfiniteScroll();
});