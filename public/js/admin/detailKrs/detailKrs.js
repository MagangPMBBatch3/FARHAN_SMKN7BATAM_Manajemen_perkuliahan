// detailKrs.js - Tambahkan/Update bagian ini

const API_URL = "/graphql";
const krsId = window.location.pathname.split('/').pop();
let krsDetailList = [];

// Fungsi utama untuk load data KRS
async function loadKrsDetail() {
    try {
        const query = `
        query($id: ID!) {
            krs(id: $id) {
                id
                mahasiswa {
                    id
                    nama_lengkap
                    nim
                    jurusan {
                        id
                        nama_jurusan
                    }
                    ip_semester
                    semester_saat_ini
                }
                semester {
                    id
                    nama_semester
                    tahun_ajaran
                }
                tanggal_pengisian
                tanggal_persetujuan
                status
                total_sks
                catatan
                dosenPa {
                    id
                    nama_lengkap
                }
                krsDetail {
                    id
                    mata_kuliah_id
                    kelas_id
                    sks
                    status_ambil
                    mataKuliah {
                        id
                        nama_mk
                        kode_mk
                        sks
                    }
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
                }
                created_at
                updated_at
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query, 
                variables: { id: krsId } 
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }

        const krsData = result.data.krs;
        
        // Simpan data krsDetail ke variable global
        krsDetailList = krsData.krsDetail || [];
        
        // Render semua data
        renderKrsData(krsData);
        
        // Hide loading, show content
        document.getElementById('loading').classList.add('hidden');
        document.getElementById('content').classList.remove('hidden');
        
    } catch (error) {
        console.error('Error loading KRS:', error);
        alert('Gagal memuat data KRS: ' + error.message);
    }
}

// Fungsi untuk render data KRS ke UI
function renderKrsData(data) {
    // ===== HEADER SECTION =====
    // Initial (huruf pertama nama)
    const initial = data.mahasiswa?.nama_lengkap?.charAt(0).toUpperCase() || '?';
    document.getElementById('initial').textContent = initial;
    
    // Nama mahasiswa
    document.getElementById('nama').textContent = data.mahasiswa?.nama_lengkap || '-';
    
    // NIM
    document.getElementById('nim').textContent = data.mahasiswa?.nim || '-';
    
    // Jurusan
    document.getElementById('jurusan').textContent = data.mahasiswa?.jurusan?.nama_jurusan || '-';
    
    // Status Header
    const statusHeader = document.getElementById('statusHeader');
    statusHeader.innerHTML = getStatusBadge(data.status);
    
    // Semester di header
    document.getElementById('semester').textContent = data.mahasiswa?.semester_saat_ini || '-';
    
    // Tanggal Pengisian di header
    document.getElementById('tanggalPengisian').textContent = formatDate(data.tanggal_pengisian) || '-';
    
    // ===== STATS CARDS =====
    document.getElementById('totalSksBesar').textContent = data.total_sks || '0';
    document.getElementById('totalMatakuliah').textContent = krsDetailList.length || '0';
    document.getElementById('ipSemesterBesar').textContent = data.mahasiswa?.ip_semester || '0.00';
    
    // ===== TAB INFO KRS - Informasi Mahasiswa =====
    document.getElementById('mahasiswaNama').textContent = data.mahasiswa?.nama_lengkap || '-';
    document.getElementById('mahasiswaNim').textContent = data.mahasiswa?.nim || '-';
    
    // Jurusan di tab info (gunakan class atau id berbeda jika perlu)
    const jurusanElements = document.querySelectorAll('[id="jurusanInfo"], .jurusan-display');
    jurusanElements.forEach(el => {
        el.textContent = data.mahasiswa?.jurusan?.nama_jurusan || '-';
    });
    
    // ===== TAB INFO KRS - Detail KRS =====
    document.getElementById('krsId').textContent = data.id || '-';
    
    // Semester (menggunakan class selector untuk duplikat)
    document.querySelectorAll('.semester-display').forEach(el => {
        el.textContent = data.semester?.nama_semester || '-';
    });
    
    document.getElementById('tahunAjaran').textContent = data.semester?.tahun_ajaran || '-';
    
    // Tanggal Pengisian (menggunakan class selector untuk duplikat)
    document.querySelectorAll('.tanggal-pengisian-display').forEach(el => {
        el.textContent = formatDate(data.tanggal_pengisian) || '-';
    });
    
    // Status KRS
    const statusKrs = document.getElementById('statusKrs');
    statusKrs.innerHTML = getStatusBadge(data.status);
    
    document.getElementById('totalSks').textContent = data.total_sks || '0';
    document.getElementById('ipSemester').textContent = data.mahasiswa?.ip_semester || '0.00';
    
    // Field tambahan yang mungkin tidak ada di desain lama
    document.getElementById('tanggalPersetujuan').textContent = formatDate(data.tanggal_persetujuan) || '-';
    document.getElementById('dosenPa').textContent = data.dosenPa?.nama_lengkap || '-';
    document.getElementById('catatan').textContent = data.catatan || '-';
    
    // ===== METADATA =====
    document.getElementById('createdAt').textContent = formatDateTime(data.created_at) || '-';
    document.getElementById('updatedAt').textContent = formatDateTime(data.updated_at) || '-';
    
    // ===== RENDER TABLE MATA KULIAH =====
    renderMataKuliahTable();
}

// Fungsi untuk render tabel mata kuliah
function renderMataKuliahTable() {
    const tbody = document.getElementById('mataKuliahTableBody');
    tbody.innerHTML = '';
    
    if (!krsDetailList.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center gap-2">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-medium">Belum ada mata kuliah</p>
                        <p class="text-sm">Klik tombol "Tambah Mata Kuliah" untuk menambahkan</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    krsDetailList.forEach((detail, index) => {
        const jadwalHtml = detail.kelas?.jadwalKuliah?.map(j => 
            `<div class="text-xs text-gray-500">${j.hari}, ${j.jam_mulai}-${j.jam_selesai}</div>`
        ).join('') || '<div class="text-xs text-gray-500">-</div>';
        
        tbody.innerHTML += `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-900">${index + 1}</td>
                <td class="px-6 py-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900">${detail.mataKuliah?.nama_mk || '-'}</p>
                        <p class="text-xs text-gray-500">${detail.mataKuliah?.kode_mk || '-'}</p>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div>
                        <p class="text-sm text-gray-900">${detail.kelas?.nama_kelas || '-'}</p>
                        ${jadwalHtml}
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">${detail.kelas?.dosen?.nama_lengkap || '-'}</td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-700 text-sm font-semibold rounded-lg">
                        ${detail.sks || 0}
                    </span>
                </td>
                <td class="px-6 py-4">
                    ${getStatusAmbilBadge(detail.status_ambil)}
                </td>
                <td class="px-6 py-4 text-center text-sm font-medium text-gray-900">${detail.nilai || '-'}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="openEditKrsDetailModal(${detail.id})" 
                            class="p-2 hover:bg-blue-50 text-blue-600 rounded-lg transition-colors" 
                            title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteKrsDetail(${detail.id})" 
                            class="p-2 hover:bg-red-50 text-red-600 rounded-lg transition-colors" 
                            title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}

// Helper function untuk status badge
function getStatusBadge(status) {
    const badges = {
        'PENDING': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">Menunggu Persetujuan</span>',
        'DISETUJUI': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">Disetujui</span>',
        'DITOLAK': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">Ditolak</span>',
        'DRAFT': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">Draft</span>'
    };
    return badges[status] || `<span class="text-sm text-gray-900">${status || '-'}</span>`;
}

// Helper function untuk status ambil badge
function getStatusAmbilBadge(statusAmbil) {
    const badges = {
        'BARU': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">Baru</span>',
        'MENGULANG': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">Mengulang</span>',
        'PERBAIKAN': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">Perbaikan</span>'
    };
    return badges[statusAmbil] || `<span class="text-sm text-gray-900">${statusAmbil || '-'}</span>`;
}

// Helper function untuk format tanggal
function formatDate(dateString) {
    if (!dateString) return null;
    const date = new Date(dateString);
    const options = { day: 'numeric', month: 'short', year: 'numeric' };
    return date.toLocaleDateString('id-ID', options);
}

// Helper function untuk format tanggal dan waktu
function formatDateTime(dateString) {
    if (!dateString) return null;
    const date = new Date(dateString);
    const options = { 
        day: 'numeric', 
        month: 'short', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('id-ID', options);
}

// Fungsi untuk switch tab
function showTab(tabName) {
    // Hide all tabs
    document.getElementById('contentInfo').classList.add('hidden');
    document.getElementById('contentMatakuliah').classList.add('hidden');
    
    // Reset all tab buttons
    document.getElementById('tabInfo').classList.remove('border-blue-600', 'text-blue-600');
    document.getElementById('tabInfo').classList.add('border-transparent', 'text-gray-600');
    document.getElementById('tabMatakuliah').classList.remove('border-blue-600', 'text-blue-600');
    document.getElementById('tabMatakuliah').classList.add('border-transparent', 'text-gray-600');
    
    // Show selected tab
    if (tabName === 'info') {
        document.getElementById('contentInfo').classList.remove('hidden');
        document.getElementById('tabInfo').classList.add('border-blue-600', 'text-blue-600');
        document.getElementById('tabInfo').classList.remove('border-transparent', 'text-gray-600');
    } else if (tabName === 'matakuliah') {
        document.getElementById('contentMatakuliah').classList.remove('hidden');
        document.getElementById('tabMatakuliah').classList.add('border-blue-600', 'text-blue-600');
        document.getElementById('tabMatakuliah').classList.remove('border-transparent', 'text-gray-600');
    }
}

// Action functions (sesuaikan dengan backend Anda)
async function approveKrs() {
    if (!confirm('Setujui KRS ini?')) return;
    
    try {
        const mutation = `
        mutation($id: ID!) {
            approveKrs(id: $id) {
                id
                status
            }
        }`;
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: { id: krsId }
            })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('KRS berhasil disetujui!');
        loadKrsDetail(); // Reload data
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menyetujui KRS: ' + error.message);
    }
}

async function rejectKrs() {
    const reason = prompt('Alasan penolakan:');
    if (!reason) return;
    
    try {
        const mutation = `
        mutation($id: ID!, $reason: String!) {
            rejectKrs(id: $id, reason: $reason) {
                id
                status
            }
        }`;
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: { id: krsId, reason }
            })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('KRS ditolak!');
        loadKrsDetail(); // Reload data
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menolak KRS: ' + error.message);
    }
}

async function confirmDelete() {
    if (!confirm('Hapus KRS ini? Data tidak dapat dikembalikan!')) return;
    
    try {
        const mutation = `
        mutation($id: ID!) {
            deleteKrs(id: $id) {
                id
            }
        }`;
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: { id: krsId }
            })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('KRS berhasil dihapus!');
        window.location.href = '/admin/krs';
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menghapus KRS: ' + error.message);
    }
}

async function deleteKrsDetail(detailId) {
    if (!confirm('Hapus mata kuliah ini dari KRS?')) return;
    
    try {
        const mutation = `
        mutation($id: ID!) {
            deleteKrsDetail(id: $id) {
                id
            }
        }`;
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: { id: detailId }
            })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('Mata kuliah berhasil dihapus!');
        loadKrsDetail(); // Reload data
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menghapus mata kuliah: ' + error.message);
    }
}

// Load data saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    loadKrsDetail();
});