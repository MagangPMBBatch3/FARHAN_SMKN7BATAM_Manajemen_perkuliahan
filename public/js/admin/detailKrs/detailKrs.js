// ============================================================================
// CONFIGURATION & GLOBAL STATE
// ============================================================================

const API_URL = "/graphql";
const MIN_SKS = 12;

let currentKrsId = null;
let currentKrsData = null;
let krsDetailList = [];

// ============================================================================
// SKS LIMIT MANAGEMENT
// ============================================================================

/**
 * Mengambil data batas SKS dari API
 */
async function getSksLimitList() {
    const query = `query {
        allSksLimit {
            id
            min_ipk
            max_ipk
            max_sks
            keterangan
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        return result.data.allSksLimit || [];
    } catch (error) {
        console.error('Error getting SKS limit:', error);
        return [];
    }
}

/**
 * Menghitung maksimal SKS berdasarkan IPK mahasiswa
 */
async function getMaxSks(ipk) {
    try {
        const sksLimitList = await getSksLimitList();

        // Mahasiswa baru (IPK = 0 atau null)
        if (!ipk || ipk === 0) {
            const mahasiswaBaru = sksLimitList.find(item =>
                item.keterangan?.toLowerCase().includes('baru')
            );
            return mahasiswaBaru?.max_sks || 12;
        }

        // Cari batas SKS berdasarkan range IPK
        const matchedLimit = sksLimitList.find(item => {
            const minIpk = parseFloat(item.min_ipk) || 0;
            const maxIpk = parseFloat(item.max_ipk) || 4.0;
            return ipk >= minIpk && ipk <= maxIpk;
        });

        return matchedLimit?.max_sks || 16;

    } catch (error) {
        console.error('Error in getMaxSks:', error);
        // Fallback hardcoded
        if (!ipk || ipk === 0) return 12;
        if (ipk >= 3.50) return 24;
        if (ipk >= 3.00) return 22;
        if (ipk >= 2.50) return 20;
        if (ipk >= 2.00) return 18;
        return 16;
    }
}

// ============================================================================
// DATA LOADING
// ============================================================================

/**
 * Mengambil detail KRS dari API
 */
async function loadKrsDetail() {
    currentKrsId = getKrsIdFromUrl();

    const query = `query($id: ID!) {
        krs(id: $id) {
            id mahasiswa_id semester_id tanggal_pengisian tanggal_persetujuan
            status total_sks catatan dosen_pa_id created_at updated_at
            mahasiswa {
                id nim nama_lengkap semester_saat_ini ip_semester ipk
                jurusan { id nama_jurusan }
            }
            semester { id nama_semester tahun_ajaran }
            dosenPa { id nama_lengkap }
            krsDetail {
                id krs_id kelas_id mata_kuliah_id sks status_ambil created_at updated_at
                kelas {
                    id nama_kelas kapasitas kuota_terisi
                    dosen { id nama_lengkap }
                    jadwalKuliah {
                        id hari jam_mulai jam_selesai
                        ruangan { id nama_ruangan }
                    }
                }
                mataKuliah { id kode_mk nama_mk sks semester_rekomendasi }
                nilai { id nilai_akhir nilai_huruf nilai_mutu }
            }
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query, variables: { id: currentKrsId } })
        });

        const result = await response.json();

        if (result.errors) throw new Error(result.errors[0].message);
        if (!result.data?.krs) {
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
        alert('Terjadi kesalahan: ' + error.message);
    }
}

// ============================================================================
// RENDER & UI
// ============================================================================

/**
 * Render detail KRS ke halaman
 */
function renderKrsDetail(krsData, detailList) {
    if (!krsData?.mahasiswa) return;

    // Header
    const initial = krsData.mahasiswa.nama_lengkap.charAt(0).toUpperCase();
    setContent('initial', initial);
    setContent('nama', krsData.mahasiswa.nama_lengkap);
    setContent('nim', krsData.mahasiswa.nim);
    setContent('statusHeader', krsData.status || '-');

    // Info KRS
    setContent('krsId', krsData.id);
    setContent('mahasiswaNama', krsData.mahasiswa.nama_lengkap);
    setContent('mahasiswaNim', krsData.mahasiswa.nim);
    setContent('jurusan', krsData.mahasiswa.jurusan?.nama_jurusan || '-');
    setContent('semester', krsData.semester?.nama_semester || '-');
    setContent('tahunAjaran', krsData.semester?.tahun_ajaran || '-');
    setContent('tanggalPengisian', formatDate(krsData.tanggal_pengisian));
    setHTML('statusKrs', getStatusKrsBadge(krsData.status));

    // Statistik
    const totalSks = detailList.reduce((sum, d) => sum + (d.sks || 0), 0);
    const ipk = krsData.mahasiswa.ipk || 0;

    setContent('totalSks', totalSks);
    setContent('totalSksBesar', totalSks);
    setContent('totalMatakuliah', detailList.length);
    setContent('ipSemester', ipk.toFixed(2));
    setContent('ipSemesterBesar', ipk.toFixed(2));

    // Table
    renderMataKuliahTable(detailList);

    // Metadata
    if (krsData.created_at) setContent('createdAt', formatDateTime(krsData.created_at));
    if (krsData.updated_at) setContent('updatedAt', formatDateTime(krsData.updated_at));

    // Info SKS
    updateSksInfo(totalSks, ipk);
}

/**
 * Render tabel mata kuliah
 */
function renderMataKuliahTable(detailList) {
    const tbody = document.getElementById('mataKuliahTableBody');
    if (!tbody) return;

    if (detailList.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="8" class="px-6 py-8 text-center">
                <div class="flex flex-col items-center text-gray-500">
                    <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-lg font-semibold mb-1">Belum ada mata kuliah</p>
                    <button onclick="openAddKrsDetailModal()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Tambah Mata Kuliah
                    </button>
                </div>
            </td></tr>
        `;
        return;
    }

    tbody.innerHTML = detailList.map((detail, index) => {
        const jadwal = detail.kelas?.jadwalKuliah?.map(j =>
            `${j.hari}, ${j.jam_mulai}-${j.jam_selesai}`
        ).join('<br>') || '-';

        const dosen = detail.kelas?.dosen?.nama_lengkap || '-';
        const nilaiHuruf = detail.nilai?.nilai_huruf || '-';
        const nilaiAngka = detail.nilai?.nilai_akhir || '';
        const nilaiText = nilaiAngka ? `${nilaiHuruf} (${nilaiAngka})` : nilaiHuruf;

        return `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-6 py-4 text-sm">${index + 1}</td>
                <td class="px-6 py-4">
                    <div class="font-medium">${detail.mataKuliah?.nama_mk || '-'}</div>
                    <div class="text-sm text-gray-500">${detail.mataKuliah?.kode_mk || '-'}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm">${detail.kelas?.nama_kelas || '-'}</div>
                    <div class="text-xs text-gray-500">${jadwal}</div>
                </td>
                <td class="px-6 py-4 text-sm">${dosen}</td>
                <td class="px-6 py-4 text-sm text-center">${detail.sks || 0}</td>
                <td class="px-6 py-4">${getStatusAmbilBadge(detail.status_ambil)}</td>
                <td class="px-6 py-4 text-center">
                    <span class="font-semibold ${getNilaiColor(nilaiHuruf)}">${nilaiText}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <button onclick="deleteKrsDetail(${detail.id}, '${detail.mataKuliah?.nama_mk}')" 
                        class="px-3 py-1.5 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                        Hapus
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

/**
 * Update informasi batas SKS
 */
async function updateSksInfo(currentSks, ipk) {
    const maxSks = await getMaxSks(ipk);
    let sksInfoEl = document.getElementById('sksInfoAlert');

    if (!sksInfoEl) {
        const container = document.getElementById('contentMatakuliah');
        if (container) {
            sksInfoEl = document.createElement('div');
            sksInfoEl.id = 'sksInfoAlert';
            sksInfoEl.className = 'mb-6';
            container.insertBefore(sksInfoEl, container.firstChild);
        }
    }

    if (!sksInfoEl) return;

    let alertClass, icon, message;

    if (currentSks < MIN_SKS) {
        alertClass = 'bg-red-50 border-red-500 text-red-800';
        icon = '⚠️';
        message = `Total SKS <strong>${currentSks}</strong> kurang dari minimal <strong>${MIN_SKS} SKS</strong>`;
    } else if (currentSks > maxSks) {
        alertClass = 'bg-red-50 border-red-500 text-red-800';
        icon = '⚠️';
        message = `Total SKS <strong>${currentSks}</strong> melebihi maksimal <strong>${maxSks} SKS</strong> (IPK: ${ipk.toFixed(2)})`;
    } else {
        alertClass = 'bg-green-50 border-green-500 text-green-800';
        icon = '✓';
        message = `Total SKS <strong>${currentSks}</strong> sudah sesuai (Min: ${MIN_SKS}, Max: ${maxSks} berdasarkan IPK ${ipk.toFixed(2)})`;
    }

    sksInfoEl.innerHTML = `
        <div class="${alertClass} p-4 rounded-lg border-l-4">
            <div class="flex items-start">
                <span class="text-xl mr-3">${icon}</span>
                <div class="text-sm">${message}</div>
            </div>
        </div>
    `;
}

// ============================================================================
// CRUD OPERATIONS
// ============================================================================

/**
 * Hapus mata kuliah dari KRS
 */
async function deleteKrsDetail(id, namaMk) {
    if (!confirm(`Hapus "${namaMk}" dari KRS?`)) return;

    try {
        const detail = krsDetailList.find(d => d.id == id);

        const mutation = `mutation($id: ID!) { deleteKrsDetail(id: $id) { id } }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation, variables: { id: parseInt(id) } })
        });

        const result = await response.json();
        if (result.errors) throw new Error(result.errors[0].message);

        // if (detail?.kelas_id) await updateKuotaKelas(detail.kelas_id, -1);

        await loadKrsDetail();
        showSuccessNotification(`"${namaMk}" berhasil dihapus`);

    } catch (error) {
        console.error('Error:', error);
        alert('Gagal menghapus: ' + error.message);
    }
}

/**
 * Update kuota kelas (increment/decrement)
 */
// async function updateKuotaKelas(kelasId, increment) {
//     try {
//         const queryKuota = `query($id: ID!) { kelas(id: $id) { id kuota_terisi } }`;
        
//         const res = await fetch(API_URL, {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json' },
//             body: JSON.stringify({ query: queryKuota, variables: { id: parseInt(kelasId) } })
//         });

//         const result = await res.json();
//         const currentKuota = result.data?.kelas?.kuota_terisi ?? 0;
//         const newKuota = Math.max(0, currentKuota + increment);

//         const mutation = `mutation($id: ID!, $input: UpdateKelasInput!) {
//             updateKelas(id: $id, input: $input) { id kuota_terisi }
//         }`;

//         await fetch(API_URL, {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json' },
//             body: JSON.stringify({
//                 query: mutation,
//                 variables: { id: parseInt(kelasId), input: { kuota_terisi: newKuota } }
//             })
//         });
//     } catch (error) {
//         console.error('Error updating kuota:', error);
//     }
// }

/**
 * Setujui KRS
 */
async function approveKrs() {
    if (!currentKrsData) return;

    const totalSks = krsDetailList.reduce((sum, d) => sum + (d.sks || 0), 0);
    const ipk = currentKrsData.mahasiswa.ipk || 0;
    const maxSks = await getMaxSks(ipk);

    if (totalSks < MIN_SKS) {
        alert(`Total SKS (${totalSks}) kurang dari minimal ${MIN_SKS} SKS`);
        return;
    }

    if (totalSks > maxSks) {
        alert(`Total SKS (${totalSks}) melebihi maksimal ${maxSks} SKS (IPK: ${ipk.toFixed(2)})`);
        return;
    }

    if (!confirm('Setujui KRS ini?')) return;

    const mutation = `mutation($id: ID!, $input: UpdateKrsInput!) {
        updateKrs(id: $id, input: $input) { id status }
    }`;

    try {
        const today = new Date().toISOString().split('T')[0];
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query: mutation,
                variables: {
                    id: parseInt(currentKrsId),
                    input: { status: "DISETUJUI", tanggal_persetujuan: today }
                }
            })
        });

        const result = await response.json();
        if (result.errors) throw new Error(result.errors[0].message);

        alert('KRS berhasil disetujui');
        loadKrsDetail();
    } catch (error) {
        alert('Gagal menyetujui: ' + error.message);
    }
}

/**
 * Tolak KRS
 */
async function rejectKrs() {
    if (!currentKrsData) return;

    const alasan = prompt('Berikan alasan penolakan (opsional):');
    if (alasan === null) return;

    const mutation = `mutation($id: ID!, $input: UpdateKrsInput!) {
        updateKrs(id: $id, input: $input) { id status }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query: mutation,
                variables: {
                    id: parseInt(currentKrsId),
                    input: { status: "DITOLAK", catatan: alasan || null }
                }
            })
        });

        const result = await response.json();
        if (result.errors) throw new Error(result.errors[0].message);

        alert('KRS berhasil ditolak');
        loadKrsDetail();
    } catch (error) {
        alert('Gagal menolak: ' + error.message);
    }
}

/**
 * Hapus KRS
 */
async function confirmDelete() {
    if (!currentKrsData) return;

    if (!confirm(`Hapus KRS ini? Semua detail akan ikut terhapus.`)) return;

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
        alert('Gagal menghapus: ' + error.message);
    }
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

function getKrsIdFromUrl() {
    const segments = window.location.pathname.split('/');
    return segments[segments.length - 1];
}

function setContent(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
}

function setHTML(id, html) {
    const el = document.getElementById(id);
    if (el) el.innerHTML = html;
}

function showSuccessNotification(msg) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50';
    toast.innerHTML = `<span>${msg}</span>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function getNilaiColor(nilai) {
    if (!nilai || nilai === '-') return 'text-gray-600';
    if (['A', 'A-'].includes(nilai)) return 'text-green-600';
    if (['B+', 'B', 'B-'].includes(nilai)) return 'text-blue-600';
    if (['C+', 'C'].includes(nilai)) return 'text-yellow-600';
    if (['D', 'E'].includes(nilai)) return 'text-red-600';
    return 'text-gray-600';
}

function getStatusKrsBadge(status) {
    const badges = {
        'DRAFT': 'bg-gray-100 text-gray-800',
        'DIAJUKAN': 'bg-yellow-100 text-yellow-800',
        'DISETUJUI': 'bg-green-100 text-green-800',
        'DITOLAK': 'bg-red-100 text-red-800',
        'AKTIF': 'bg-blue-100 text-blue-800'
    };
    const cls = badges[status?.toUpperCase()] || 'bg-gray-100 text-gray-800';
    return `<span class="${cls} px-3 py-1 rounded-full text-sm font-semibold">${status || '-'}</span>`;
}

function getStatusAmbilBadge(status) {
    const badges = {
        'BARU': 'bg-blue-100 text-blue-800',
        'MENGULANG': 'bg-orange-100 text-orange-800'
    };
    const cls = badges[status?.toUpperCase()] || 'bg-gray-100 text-gray-800';
    return `<span class="${cls} px-2 py-1 rounded text-xs font-semibold">${status || '-'}</span>`;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    try {
        return new Date(dateString).toLocaleDateString('id-ID', {
            year: 'numeric', month: 'long', day: 'numeric'
        });
    } catch { return '-'; }
}

function formatDateTime(dateString) {
    if (!dateString) return '-';
    try {
        return new Date(dateString).toLocaleDateString('id-ID', {
            year: 'numeric', month: 'long', day: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });
    } catch { return '-'; }
}

function showTab(tabName) {
    const tabs = ['info', 'matakuliah'];
    tabs.forEach(tab => {
        const btn = document.getElementById(`tab${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
        const content = document.getElementById(`content${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
        
        if (btn && content) {
            if (tab === tabName) {
                btn.classList.add('border-b-2', 'border-blue-500', 'text-blue-600', 'font-semibold');
                content.classList.remove('hidden');
            } else {
                btn.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600', 'font-semibold');
                content.classList.add('hidden');
            }
        }
    });
}

// ============================================================================
// INITIALIZATION
// ============================================================================

document.addEventListener('DOMContentLoaded', () => {
    loadKrsDetail();
});