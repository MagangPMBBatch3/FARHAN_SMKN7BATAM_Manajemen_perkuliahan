const API_URL = "/graphql";
let allKhsData = [];
let mahasiswaData = null;

// Load data saat halaman dimuat
document.addEventListener("DOMContentLoaded", async () => {
    await getMahasiswaProfile();
    await loadMahasiswaInfo();
    await loadMahasiswaKHS();
});
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
        if (result.data && result.data.mahasiswaProfile) {
            idMahasiswa = result.data.mahasiswaProfile.id;
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
/**
 * Load informasi mahasiswa
 */
async function loadMahasiswaInfo() {
    try {
        const query = `
        query($mahasiswaId: Int!) {
            mahasiswa(id: $mahasiswaId) {
                id
                nim
                nama_lengkap
                jurusan {
                    nama_jurusan
                }
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { mahasiswaId: parseInt(idMahasiswa) }
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            return;
        }

        mahasiswaData = result.data.mahasiswa;

        // Update info mahasiswa di header
        document.getElementById('infoNIM').textContent = mahasiswaData.nim;
        document.getElementById('infoNama').textContent = mahasiswaData.nama_lengkap;
        document.getElementById('infoProdi').textContent = mahasiswaData.jurusan.nama_jurusan;

    } catch (error) {
        console.error('Error loading mahasiswa info:', error);
    }
}

/**
 * Load semua KHS mahasiswa
 */
async function loadMahasiswaKHS() {
    const loadingEl = document.getElementById('loadingKHS');
    if (loadingEl) loadingEl.style.display = 'flex';

    try {
        const query = `
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

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { mahasiswaId: parseInt(idMahasiswa) }
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            showErrorMessage('Gagal memuat data KHS');
            return;
        }

        allKhsData = result.data.khsByMahasiswa || [];

        // Urutkan berdasarkan semester (terbaru di atas)
        allKhsData.sort((a, b) => b.semester.id - a.semester.id);

        // Update IPK terakhir
        if (allKhsData.length > 0) {
            const latestKhs = allKhsData[0];
            document.getElementById('latestIPK').textContent = parseFloat(latestKhs.ipk).toFixed(2);
        }

        // Render KHS list
        renderKhsList();

    } catch (error) {
        console.error('Error loading KHS:', error);
        showErrorMessage('Terjadi kesalahan saat memuat data KHS');
    } finally {
        if (loadingEl) loadingEl.style.display = 'none';
    }
}

/**
 * Render daftar KHS
 */
function renderKhsList() {
    const khsList = document.getElementById('khsList');
    khsList.innerHTML = '';

    if (allKhsData.length === 0) {
        khsList.innerHTML = `
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data KHS</h3>
                <p class="mt-1 text-sm text-gray-500">Data KHS Anda akan muncul di sini setelah nilai diinput</p>
            </div>
        `;
        return;
    }

    allKhsData.forEach(khs => {
        const ipSemester = parseFloat(khs.ip_semester);
        const ipk = parseFloat(khs.ipk);
        
        const card = document.createElement('div');
        card.className = 'bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow cursor-pointer';
        card.onclick = () => openDetailModal(khs.id);
        
        card.innerHTML = `
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-900">${khs.semester.nama_semester}</h4>
                            <p class="text-sm text-gray-500">${khs.semester.tahun_ajaran}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-600">SKS Semester</p>
                            <p class="text-lg font-bold text-gray-900">${khs.sks_semester}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">IP Semester</p>
                            <p class="text-lg font-bold">
                                <span class="px-3 py-1 rounded-full text-sm ${getIPColor(ipSemester)}">
                                    ${ipSemester.toFixed(2)}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">SKS Kumulatif</p>
                            <p class="text-lg font-bold text-blue-600">${khs.sks_kumulatif}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">IPK</p>
                            <p class="text-lg font-bold">
                                <span class="px-3 py-1 rounded-full text-sm ${getIPKColor(ipk)}">
                                    ${ipk.toFixed(2)}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="ml-4">
                    <button onclick="event.stopPropagation(); openDetailModal(${khs.id})" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Detail
                    </button>
                </div>
            </div>
        `;
        
        khsList.appendChild(card);
    });
}

/**
 * Load dan render transkrip nilai
 */
async function loadTranskrip() {
    const transkripContent = document.getElementById('transkripContent');
    transkripContent.innerHTML = `
        <div class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>
    `;

    try {
        // Load semua nilai mahasiswa
        const query = `
        query($mahasiswaId: Int!) {
            khsByMahasiswa(mahasiswa_id: $mahasiswaId) {
                id
                semester {
                    id
                    nama_semester
                    tahun_ajaran
                }
                sks_semester
                ip_semester
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { mahasiswaId: parseInt(idMahasiswa) }
            })
        });

        const result = await response.json();
        const khsData = result.data.khsByMahasiswa || [];

        // Render transkrip
        renderTranskrip(khsData);

    } catch (error) {
        console.error('Error loading transkrip:', error);
        transkripContent.innerHTML = `
            <div class="text-center py-12 text-red-600">
                <p>Gagal memuat transkrip nilai</p>
            </div>
        `;
    }
}

/**
 * Render transkrip
 */
function renderTranskrip(khsData) {
    const transkripContent = document.getElementById('transkripContent');
    
    if (khsData.length === 0) {
        transkripContent.innerHTML = `
            <div class="text-center py-12">
                <p class="text-gray-500">Belum ada data transkrip</p>
            </div>
        `;
        return;
    }

    // Sort berdasarkan semester
    khsData.sort((a, b) => a.semester.id - b.semester.id);

    const latestKhs = khsData[khsData.length - 1];
    const totalSKS = latestKhs ? khsData[khsData.length - 1].sks_semester : 0;
    const ipk = latestKhs ? parseFloat(latestKhs.ip_semester).toFixed(2) : '0.00';

    transkripContent.innerHTML = `
        <div id="printableTranskrip" class="bg-white">
            <!-- Header Transkrip -->
            <div class="border-b-4 border-blue-600 pb-6 mb-6">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">TRANSKRIP NILAI</h2>
                    <h3 class="text-xl font-semibold text-gray-800">POLITEKNIK BATAM</h3>
                </div>
                
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">NIM</p>
                        <p class="font-semibold text-gray-900">${mahasiswaData.nim}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama</p>
                        <p class="font-semibold text-gray-900">${mahasiswaData.nama_lengkap}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Program Studi</p>
                        <p class="font-semibold text-gray-900">${mahasiswaData.jurusan.nama_jurusan}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Cetak</p>
                        <p class="font-semibold text-gray-900">${new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})}</p>
                    </div>
                </div>
            </div>

            <!-- Riwayat Per Semester -->
            <div class="space-y-6">
                ${khsData.map(khs => `
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-2">${khs.semester.nama_semester} - ${khs.semester.tahun_ajaran}</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">SKS Semester:</span>
                                <span class="font-semibold ml-2">${khs.sks_semester}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">IP Semester:</span>
                                <span class="font-semibold ml-2">${parseFloat(khs.ip_semester).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>

            <!-- Summary -->
            <div class="mt-8 bg-blue-50 border-2 border-blue-200 rounded-lg p-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Total SKS</p>
                        <p class="text-3xl font-bold text-blue-600">${totalSKS}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Indeks Prestasi Kumulatif (IPK)</p>
                        <p class="text-3xl font-bold text-blue-600">${ipk}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Switch antara tab
 */
function showTab(tabName) {
    // Update tab buttons
    const tabKHS = document.getElementById('tabKHS');
    const tabTranskrip = document.getElementById('tabTranskrip');
    const contentKHS = document.getElementById('contentKHS');
    const contentTranskrip = document.getElementById('contentTranskrip');

    if (tabName === 'khs') {
        tabKHS.className = 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600';
        tabTranskrip.className = 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
        contentKHS.classList.remove('hidden');
        contentTranskrip.classList.add('hidden');
    } else {
        tabKHS.className = 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
        tabTranskrip.className = 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600';
        contentKHS.classList.add('hidden');
        contentTranskrip.classList.remove('hidden');
        
        // Load transkrip jika belum diload
        if (contentTranskrip.querySelector('#transkripContent').innerHTML === '') {
            loadTranskrip();
        }
    }
}

/**
 * Print transkrip
 */
function printTranskrip() {
    const printContent = document.getElementById('printableTranskrip');
    if (!printContent) return;

    const winPrint = window.open('', '', 'width=800,height=600');
    winPrint.document.write(`
        <html>
            <head>
                <title>Transkrip Nilai</title>
                <script src="https://cdn.tailwindcss.com"></script>
                <style>
                    @media print {
                        body { padding: 20px; }
                        @page { margin: 1cm; }
                    }
                </style>
            </head>
            <body>
                ${printContent.innerHTML}
            </body>
        </html>
    `);
    
    winPrint.document.close();
    winPrint.focus();
    setTimeout(() => {
        winPrint.print();
        winPrint.close();
    }, 250);
}

/**
 * Helper functions untuk warna
 */
function getIPColor(ip) {
    if (ip >= 3.50) return 'bg-green-100 text-green-800';
    if (ip >= 3.00) return 'bg-blue-100 text-blue-800';
    if (ip >= 2.75) return 'bg-yellow-100 text-yellow-800';
    if (ip >= 2.00) return 'bg-orange-100 text-orange-800';
    return 'bg-red-100 text-red-800';
}

function getIPKColor(ipk) {
    if (ipk >= 3.75) return 'bg-green-100 text-green-800 border-2 border-green-400';
    if (ipk >= 3.50) return 'bg-blue-100 text-blue-800 border-2 border-blue-400';
    if (ipk >= 3.00) return 'bg-cyan-100 text-cyan-800 border-2 border-cyan-400';
    if (ipk >= 2.75) return 'bg-yellow-100 text-yellow-800 border-2 border-yellow-400';
    if (ipk >= 2.00) return 'bg-orange-100 text-orange-800 border-2 border-orange-400';
    return 'bg-red-100 text-red-800 border-2 border-red-400';
}

function showErrorMessage(message) {
    const khsList = document.getElementById('khsList');
    khsList.innerHTML = `
        <div class="text-center py-12 text-red-600">
            <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">${message}</h3>
            <button onclick="loadMahasiswaKHS()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Coba Lagi
            </button>
        </div>
    `;
}