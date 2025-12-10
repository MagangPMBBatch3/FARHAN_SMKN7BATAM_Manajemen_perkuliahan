const API_URL = "/graphql";
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
    
    // Query untuk mengambil KRS dengan semua detail-nya
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
                jurusan {
                    id
                    nama_jurusan
                }
                semester_saat_ini
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
                    dosen {
                        id
                        nama_lengkap
                    }
                }
                mataKuliah {
                    id
                    kode_mk
                    nama_mk
                    sks
                }
                nilai {
                    id
                    nilai_akhir
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
        console.log('GraphQL Response:', result);
        
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
        
        console.log('KRS Data:', currentKrsData);
        console.log('KRS Detail List:', krsDetailList);
        
        renderKrsDetail(currentKrsData, krsDetailList);
        
        const loadingEl = document.getElementById('loading');
        const contentEl = document.getElementById('content');
        
        if (loadingEl) loadingEl.classList.add('hidden');
        if (contentEl) contentEl.classList.remove('hidden');
        
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data: ' + error.message);
    }
}

// Helper function untuk safely set element content
function safeSetContent(elementId, content) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = content;
    } else {
        console.warn(`Element dengan ID "${elementId}" tidak ditemukan`);
    }
}

// Helper function untuk safely set innerHTML
function safeSetHTML(elementId, html) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = html;
    } else {
        console.warn(`Element dengan ID "${elementId}" tidak ditemukan`);
    }
}

function renderKrsDetail(krsData, detailList) {
    if (!krsData || !krsData.mahasiswa) {
        console.error('Data KRS atau mahasiswa tidak lengkap');
        return;
    }
    
    console.log('Rendering KRS Detail:', krsData);
    
    // Header Section
    const initial = krsData.mahasiswa.nama_lengkap.charAt(0).toUpperCase();
    safeSetContent('initial', initial);
    safeSetContent('nama', krsData.mahasiswa.nama_lengkap);
    safeSetContent('nim', krsData.mahasiswa.nim);
    safeSetContent('statusHeader', krsData.status || '-');

    // Tab Info KRS - Mahasiswa Section
    safeSetContent('krsId', krsData.id);
    safeSetContent('mahasiswaNama', krsData.mahasiswa.nama_lengkap);
    safeSetContent('mahasiswaNim', krsData.mahasiswa.nim);
    safeSetContent('jurusan', krsData.mahasiswa.jurusan?.nama_jurusan || '-');
    
    // Tab Info KRS - Detail KRS Section
    safeSetContent('semester', krsData.semester?.nama_semester || '-');
    safeSetContent('tahunAjaran', krsData.semester?.tahun_ajaran || '-');
    safeSetContent('tanggalPengisian', formatDate(krsData.tanggal_pengisian));
    safeSetContent('tanggalPersetujuan', formatDate(krsData.tanggal_persetujuan));
    
    // Status dengan badge
    safeSetHTML('statusKrs', getStatusKrsBadge(krsData.status));
    
    // Dosen PA
    safeSetContent('dosenPa', krsData.dosenPa?.nama_lengkap || '-');
    
    // Catatan
    safeSetContent('catatan', krsData.catatan || '-');
    
    // Calculate total SKS from detail list
    const totalSks = detailList.reduce((sum, detail) => sum + (detail.sks || 0), 0);
    
    safeSetContent('totalSks', totalSks);
    
    // Update total_sks di KRS jika berbeda
    const krsDataTotalSks = krsData.total_sks || 0;
    if (krsDataTotalSks !== totalSks) {
        console.warn(`Total SKS tidak sama: KRS=${krsDataTotalSks}, Detail=${totalSks}`);
    }

    // Summary Cards
    safeSetContent('totalSksBesar', totalSks);
    safeSetContent('totalMatakuliah', detailList.length);
    
    // IP Semester - jika ada elementnya
    safeSetContent('ipSemester', '-');
    safeSetContent('ipSemesterBesar', '-');

    // Render tabel mata kuliah
    renderMataKuliahTable(detailList);
    
    // Metadata
    if (krsData.created_at) {
        safeSetContent('createdAt', formatDateTime(krsData.created_at));
    }
    if (krsData.updated_at) {
        safeSetContent('updatedAt', formatDateTime(krsData.updated_at));
    }
}

function renderMataKuliahTable(detailList) {
    const tbody = document.getElementById('mataKuliahTableBody');
    if (!tbody) {
        console.error('Element mataKuliahTableBody tidak ditemukan');
        return;
    }
    
    tbody.innerHTML = '';

    if (detailList.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    Tidak ada mata kuliah yang diambil
                </td>
            </tr>
        `;
        return;
    }

    detailList.forEach((detail, index) => {
        const row = document.createElement('tr');
        row.className = 'border-b hover:bg-gray-50';
        
        // Dosen
        const dosen = detail.kelas?.dosen?.nama_lengkap || '-';
        
        // Nilai
        const nilaiHuruf = detail.nilai?.nilai_akhir || '-';
        const nilaiAngka = detail.nilai?.nilai_mutu || '';
        const nilaiText = nilaiAngka ? `${nilaiHuruf} (${nilaiAngka})` : nilaiHuruf;
        
        row.innerHTML = `
            <td class="px-6 py-4 text-sm text-gray-900">${index + 1}</td>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">${detail.mataKuliah?.nama_mk || '-'}</div>
                <div class="text-sm text-gray-500">${detail.mataKuliah?.kode_mk || '-'}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">${detail.kelas?.nama_kelas || '-'}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">${dosen}</div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 text-center">${detail.sks || '0'}</td>
            <td class="px-6 py-4">${getStatusAmbilBadge(detail.status_ambil)}</td>
            <td class="px-6 py-4 text-center">
                <span class="font-semibold ${getNilaiColor(nilaiHuruf)}">${nilaiText}</span>
            </td>
        `;
        tbody.appendChild(row);
    });
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
        'MENGULANG': '<span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs font-semibold">Mengulang</span>',
        'PERBAIKAN': '<span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-semibold">Perbaikan</span>'
    };
    return badges[status?.toUpperCase()] || `<span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-semibold">${status || '-'}</span>`;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    } catch (error) {
        console.error('Error formatting date:', error);
        return '-';
    }
}

function formatDateTime(dateString) {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return date.toLocaleDateString('id-ID', options);
    } catch (error) {
        console.error('Error formatting datetime:', error);
        return '-';
    }
}

// Tab Navigation
function showTab(tabName) {
    const tabs = ['info', 'matakuliah'];
    tabs.forEach(tab => {
        const tabBtn = document.getElementById(`tab${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
        const content = document.getElementById(`content${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
        
        if (!tabBtn || !content) {
            console.warn(`Tab element not found: ${tab}`);
            return;
        }
        
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

// Delete KRS
async function confirmDelete() {
    if (!currentKrsData) {
        alert('Data KRS tidak tersedia');
        return;
    }
    
    const semesterInfo = currentKrsData.semester?.nama_semester || 'semester ini';
    const confirmText = `Hapus KRS ${semesterInfo} mahasiswa ${currentKrsData.mahasiswa.nama_lengkap}?\n\nSemua detail mata kuliah akan ikut terhapus.`;
    
    if (!confirm(confirmText)) return;
    
    const mutation = `
    mutation($id: ID!) {
        deleteKrs(id: $id) { 
            id 
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: { id: currentKrsId }
            })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('KRS berhasil dihapus');
        window.location.href = '/admin/krs';
        
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menghapus KRS: ' + error.message);
    }
}

// Approve KRS
async function approveKrs() {
    if (!currentKrsData) {
        alert('Data KRS tidak tersedia');
        return;
    }
    
    const semesterInfo = currentKrsData.semester?.nama_semester || 'semester ini';
    const confirmText = `Setujui KRS ${semesterInfo} mahasiswa ${currentKrsData.mahasiswa.nama_lengkap}?`;
    
    if (!confirm(confirmText)) return;
    
    const mutation = `
    mutation($id: ID!, $input: UpdateKrsInput!) {
        updateKrs(id: $id, input: $input) {
            id
            status
            tanggal_persetujuan
        }
    }`;

    try {
        const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: { 
                    id: currentKrsId,
                    input: { 
                        status: "DISETUJUI",
                        tanggal_persetujuan: today
                    }
                }
            })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('KRS berhasil disetujui');
        loadKrsDetail(); // Reload data
        
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menyetujui KRS: ' + error.message);
    }
}

// Reject KRS
async function rejectKrs() {
    if (!currentKrsData) {
        alert('Data KRS tidak tersedia');
        return;
    }
    
    const semesterInfo = currentKrsData.semester?.nama_semester || 'semester ini';
    const alasan = prompt(`Tolak KRS ${semesterInfo} mahasiswa ${currentKrsData.mahasiswa.nama_lengkap}?\n\nBerikan alasan penolakan (opsional):`);
    
    if (alasan === null) return; // User cancelled
    
    const mutation = `
    mutation($id: ID!, $input: UpdateKrsInput!) {
        updateKrs(id: $id, input: $input) {
            id
            status
            catatan
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: { 
                    id: currentKrsId,
                    input: { 
                        status: "DITOLAK",
                        catatan: alasan || null
                    }
                }
            })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        alert('KRS berhasil ditolak' + (alasan ? `\nAlasan: ${alasan}` : ''));
        loadKrsDetail(); // Reload data
        
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menolak KRS: ' + error.message);
    }
}

// Load data on page load
document.addEventListener('DOMContentLoaded', () => {
    loadKrsDetail();
});