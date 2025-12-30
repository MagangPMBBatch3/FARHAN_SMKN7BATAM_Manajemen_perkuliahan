let currentKrsId = null;
let currentKrsData = null;
let krsDetailList = [];

function getKrsIdFromUrl() {
    const path = window.location.pathname;
    const segments = path.split('/');
    return segments[segments.length - 1];
}

async function loadKrsDetail() {
    currentKrsId = getKrsIdFromUrl();
    
    const query = `
    query($id: ID!) {
        krs(id: $id) {
            id
            mahasiswa_id
            semester_id
            tanggal_pengisian
            tanggal_persetujuan
            status
            total_sks
            catatan
            dosen_pa_id
            created_at
            updated_at
            mahasiswa {
                id
                nim
                nama_lengkap
                semester_saat_ini
                ip_semester
                jurusan {
                    id
                    nama_jurusan
                }
            }
            semester {
                id
                nama_semester
                tahun_ajaran
            }
            dosenPa {
                id
                nama_lengkap
            }
            krsDetail {
                id
                krs_id
                kelas_id
                mata_kuliah_id
                sks
                status_ambil
                created_at
                updated_at
                kelas {
                    id
                    nama_kelas
                    kapasitas
                    kuota_terisi
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
                    semester_rekomendasi
                }
                nilai {
                    id
                    nilai_akhir
                    nilai_huruf
                    nilai_mutu
                }
            }
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: query, 
                variables: { id: currentKrsId } 
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            alert('Gagal memuat data KRS: ' + result.errors[0].message);
            return;
        }

        if (!result.data || !result.data.krs) {
            alert('Data KRS tidak ditemukan');
            window.location.href = '/admin/krs';
            return;
        }

        currentKrsData = result.data.krs;
        krsDetailList = currentKrsData.krsDetail || [];
        
        renderKrsDetail(currentKrsData, krsDetailList);
        
        document.getElementById('loading')?.classList.add('hidden');
        document.getElementById('content')?.classList.remove('hidden');
        
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data: ' + error.message);
    }
}

function renderKrsDetail(krsData, detailList) {
    if (!krsData || !krsData.mahasiswa) {
        console.error('Data KRS atau mahasiswa tidak lengkap');
        return;
    }
    
    // Header Section
    const initial = krsData.mahasiswa.nama_lengkap.charAt(0).toUpperCase();
    safeSetContent('initial', initial);
    safeSetContent('nama', krsData.mahasiswa.nama_lengkap);
    safeSetContent('nim', krsData.mahasiswa.nim);
    safeSetContent('statusHeader', krsData.status || '-');

    // Tab Info KRS
    safeSetContent('krsId', krsData.id);
    safeSetContent('mahasiswaNama', krsData.mahasiswa.nama_lengkap);
    safeSetContent('mahasiswaNim', krsData.mahasiswa.nim);
    safeSetContent('jurusan', krsData.mahasiswa.jurusan?.nama_jurusan || '-');
    safeSetContent('semester', krsData.semester?.nama_semester || '-');
    safeSetContent('tahunAjaran', krsData.semester?.tahun_ajaran || '-');
    safeSetContent('tanggalPengisian', formatDate(krsData.tanggal_pengisian));
    safeSetHTML('statusKrs', getStatusKrsBadge(krsData.status));
    
    // Calculate totals
    const totalSks = detailList.reduce((sum, detail) => sum + (detail.sks || 0), 0);
    const ipSemester = krsData.mahasiswa.ip_semester || 0;
    
    safeSetContent('totalSks', totalSks);
    safeSetContent('totalSksBesar', totalSks);
    safeSetContent('totalMatakuliah', detailList.length);
    safeSetContent('ipSemester', ipSemester.toFixed(2));
    safeSetContent('ipSemesterBesar', ipSemester.toFixed(2));

    // Render table
    renderMataKuliahTable(detailList);
    
    // Metadata
    if (krsData.created_at) safeSetContent('createdAt', formatDateTime(krsData.created_at));
    if (krsData.updated_at) safeSetContent('updatedAt', formatDateTime(krsData.updated_at));
    
    // Update info batas SKS
    updateSksInfo(totalSks, ipSemester);
}

function updateSksInfo(currentSks, ipSemester) {
    const maxSks = getMaxSks(ipSemester);
    const minSks = 12;
    
    // Cek apakah ada element untuk menampilkan info SKS
    let sksInfoEl = document.getElementById('sksInfoAlert');
    
    if (!sksInfoEl) {
        // Buat element baru jika belum ada
        const contentMatakuliah = document.getElementById('contentMatakuliah');
        if (contentMatakuliah) {
            sksInfoEl = document.createElement('div');
            sksInfoEl.id = 'sksInfoAlert';
            sksInfoEl.className = 'mb-6';
            contentMatakuliah.insertBefore(sksInfoEl, contentMatakuliah.firstChild);
        }
    }
    
    if (!sksInfoEl) return;
    
    // Tentukan warna dan pesan
    let alertClass = 'bg-blue-50 border-blue-500 text-blue-800';
    let icon = `<svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>`;
    let message = '';
    
    if (currentSks < minSks) {
        alertClass = 'bg-red-50 border-red-500 text-red-800';
        icon = `<svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>`;
        message = `⚠️ Total SKS saat ini <strong>${currentSks} SKS</strong> kurang dari minimal <strong>${minSks} SKS</strong>. Silakan tambah mata kuliah.`;
    } else if (currentSks > maxSks) {
        alertClass = 'bg-red-50 border-red-500 text-red-800';
        icon = `<svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>`;
        message = `⚠️ Total SKS saat ini <strong>${currentSks} SKS</strong> melebihi maksimal <strong>${maxSks} SKS</strong> (IP: ${ipSemester.toFixed(2)}). Silakan kurangi mata kuliah.`;
    } else if (currentSks >= minSks && currentSks <= maxSks) {
        alertClass = 'bg-green-50 border-green-500 text-green-800';
        icon = `<svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>`;
        message = `✓ Total SKS <strong>${currentSks} SKS</strong> sudah sesuai (Minimal: ${minSks} SKS, Maksimal: ${maxSks} SKS berdasarkan IP ${ipSemester.toFixed(2)})`;
    }
    
    sksInfoEl.innerHTML = `
        <div class="${alertClass} p-4 rounded-lg border-l-4">
            <div class="flex items-start">
                ${icon}
                <div class="text-sm">${message}</div>
            </div>
        </div>
    `;
}

function renderMataKuliahTable(detailList) {
    const tbody = document.getElementById('mataKuliahTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';

    if (detailList.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-lg font-semibold mb-1">Belum ada mata kuliah</p>
                        <p class="text-sm mb-4">Silakan tambah mata kuliah untuk KRS ini</p>
                        <button onclick="openAddKrsDetailModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Mata Kuliah
                        </button>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    detailList.forEach((detail, index) => {
        const row = document.createElement('tr');
        row.className = 'border-b hover:bg-gray-50 transition-colors';
        
        // Jadwal
        let jadwalText = '-';
        if (detail.kelas?.jadwalKuliah && detail.kelas.jadwalKuliah.length > 0) {
            jadwalText = detail.kelas.jadwalKuliah.map(j => 
                `${j.hari}, ${j.jam_mulai}-${j.jam_selesai}`
            ).join('<br>');
        }
        
        // Dosen
        const dosen = detail.kelas?.dosen?.nama_lengkap || '-';
        
        // Nilai
        const nilaiHuruf = detail.nilai?.nilai_huruf || '-';
        const nilaiAngka = detail.nilai?.nilai_akhir || '';
        const nilaiText = nilaiAngka ? `${nilaiHuruf} (${nilaiAngka})` : nilaiHuruf;
        
        // Actions
        const actions = `
            <div class="flex items-center justify-center gap-2">
                <button onclick="openEditKrsDetailModal(${detail.id})" 
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors"
                    title="Edit mata kuliah">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
                <button onclick="deleteKrsDetail(${detail.id}, '${detail.mataKuliah?.nama_mk}')" 
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                    title="Hapus dari KRS">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
        
        row.innerHTML = `
            <td class="px-6 py-4 text-sm text-gray-900">${index + 1}</td>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">${detail.mataKuliah?.nama_mk || '-'}</div>
                <div class="text-sm text-gray-500">${detail.mataKuliah?.kode_mk || '-'}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">${detail.kelas?.nama_kelas || '-'}</div>
                <div class="text-xs text-gray-500">${jadwalText}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">${dosen}</div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 text-center">${detail.sks || '0'}</td>
            <td class="px-6 py-4">${getStatusAmbilBadge(detail.status_ambil)}</td>
            <td class="px-6 py-4 text-center">
                <span class="font-semibold ${getNilaiColor(nilaiHuruf)}">${nilaiText}</span>
            </td>
            <td class="px-6 py-4 text-center">${actions}</td>
        `;
        tbody.appendChild(row);
    });
}

// Helper functions
function safeSetContent(elementId, content) {
    const element = document.getElementById(elementId);
    if (element) element.textContent = content;
}

function safeSetHTML(elementId, html) {
    const element = document.getElementById(elementId);
    if (element) element.innerHTML = html;
}

function getNilaiColor(nilai) {
    if (!nilai || nilai === '-') return 'text-gray-600';
    if (nilai === 'A' || nilai === 'A-') return 'text-green-600';
    if (nilai === 'B+' || nilai === 'B' || nilai === 'B-') return 'text-blue-600';
    if (nilai === 'C+' || nilai === 'C') return 'text-yellow-600';
    if (nilai === 'D' || nilai === 'E') return 'text-red-600';
    return 'text-gray-600';
}

function getStatusKrsBadge(status) {
    const badges = {
        'DRAFT': '<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">Draft</span>',
        'DIAJUKAN': '<span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Diajukan</span>',
        'DISETUJUI': '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Disetujui</span>',
        'DITOLAK': '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">Ditolak</span>',
        'AKTIF': '<span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">Aktif</span>'
    };
    return badges[status?.toUpperCase()] || `<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">${status || '-'}</span>`;
}

function getStatusAmbilBadge(status) {
    const badges = {
        'BARU': '<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">Baru</span>',
        'MENGULANG': '<span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-semibold">Mengulang</span>'
    };
    return badges[status?.toUpperCase()] || `<span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-semibold">${status || '-'}</span>`;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
    } catch (error) {
        return '-';
    }
}

function formatDateTime(dateString) {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            year: 'numeric', month: 'long', day: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    } catch (error) {
        return '-';
    }
}

function showTab(tabName) {
    const tabs = ['info', 'matakuliah'];
    tabs.forEach(tab => {
        const tabBtn = document.getElementById(`tab${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
        const content = document.getElementById(`content${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
        
        if (!tabBtn || !content) return;
        
        if (tab === tabName) {
            tabBtn.classList.add('border-b-2', 'border-blue-500', 'text-blue-600', 'font-semibold');
            tabBtn.classList.remove('text-gray-600');
            content.classList.remove('hidden');
        } else {
            tabBtn.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600', 'font-semibold');
            tabBtn.classList.add('text-gray-600');
            content.classList.add('hidden');
        }
    });
}

// KRS Actions
async function confirmDelete() {
    if (!currentKrsData) return;
    
    const semesterInfo = currentKrsData.semester?.nama_semester || 'semester ini';
    if (!confirm(`Hapus KRS ${semesterInfo} mahasiswa ${currentKrsData.mahasiswa.nama_lengkap}?\n\nSemua detail mata kuliah akan ikut terhapus.`)) return;
    
    const mutation = `mutation($id: ID!) { deleteKrs(id: $id) { id } }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation, variables: { id: currentKrsId } })
        });
        
        const result = await response.json();
        if (result.errors) throw new Error(result.errors[0].message);
        
        alert('KRS berhasil dihapus');
        window.location.href = '/admin/krs';
    } catch (error) {
        alert('Gagal menghapus KRS: ' + error.message);
    }
}

async function approveKrs() {
    if (!currentKrsData) return;
    
    // Validasi SKS
    const totalSks = krsDetailList.reduce((sum, d) => sum + (d.sks || 0), 0);
    const ipSemester = currentKrsData.mahasiswa.ip_semester || 0;
    const maxSks = getMaxSks(ipSemester);
    const minSks = 12;
    
    if (totalSks < minSks) {
        alert(`Tidak dapat menyetujui KRS!\n\nTotal SKS (${totalSks}) kurang dari minimal ${minSks} SKS.`);
        return;
    }
    
    if (totalSks > maxSks) {
        alert(`Tidak dapat menyetujui KRS!\n\nTotal SKS (${totalSks}) melebihi maksimal ${maxSks} SKS (berdasarkan IP ${ipSemester.toFixed(2)}).`);
        return;
    }
    
    const semesterInfo = currentKrsData.semester?.nama_semester || 'semester ini';
    if (!confirm(`Setujui KRS ${semesterInfo} mahasiswa ${currentKrsData.mahasiswa.nama_lengkap}?`)) return;
    
    const mutation = `
    mutation($id: ID!, $input: UpdateKrsInput!) {
        updateKrs(id: $id, input: $input) {
            id status tanggal_persetujuan
        }
    }`;

    try {
        const today = new Date().toISOString().split('T')[0];
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: { 
                    id: parseInt(currentKrsId),  // Convert to Int
                    input: { status: "DISETUJUI", tanggal_persetujuan: today }
                }
            })
        });
        
        const result = await response.json();
        if (result.errors) throw new Error(result.errors[0].message);
        
        alert('KRS berhasil disetujui');
        loadKrsDetail();
    } catch (error) {
        alert('Gagal menyetujui KRS: ' + error.message);
    }
}

async function rejectKrs() {
    if (!currentKrsData) return;
    
    const semesterInfo = currentKrsData.semester?.nama_semester || 'semester ini';
    const alasan = prompt(`Tolak KRS ${semesterInfo} mahasiswa ${currentKrsData.mahasiswa.nama_lengkap}?\n\nBerikan alasan penolakan (opsional):`);
    if (alasan === null) return;
    
    const mutation = `
    mutation($id: ID!, $input: UpdateKrsInput!) {
        updateKrs(id: $id, input: $input) {
            id status catatan
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: { 
                    id: parseInt(currentKrsId),  // Convert to Int
                    input: { status: "DITOLAK", catatan: alasan || null }
                }
            })
        });
        
        const result = await response.json();
        if (result.errors) throw new Error(result.errors[0].message);
        
        alert('KRS berhasil ditolak' + (alasan ? `\nAlasan: ${alasan}` : ''));
        loadKrsDetail();
    } catch (error) {
        alert('Gagal menolak KRS: ' + error.message);
    }
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    loadKrsDetail();
});