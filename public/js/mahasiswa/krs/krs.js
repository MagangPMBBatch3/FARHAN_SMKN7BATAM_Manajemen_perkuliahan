// ============================================================================
// CONFIGURATION & GLOBAL STATE
// ============================================================================

const API_URL = "/graphql";
const MIN_SKS = 10;

let currentMahasiswaId = null;
let currentKrsId = null;
let currentKrsData = null;
let krsDetailList = [];
let mahasiswaData = null;

// ============================================================================
// INITIALIZATION
// ============================================================================

document.addEventListener('DOMContentLoaded', async () => {
    await loadMahasiswaData();
    await loadCurrentKrs();
});

// ============================================================================
// DATA LOADING
// ============================================================================

async function getMahasiswaProfile() {
    const query = `
    query {
        mahasiswaProfile {
            id
            user_id
            nim
            nama_lengkap
        }
    }`;

    const response = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query })
    });

    const result = await response.json();

    if (!result.data?.mahasiswaProfile) {
        throw new Error('Profil mahasiswa tidak ditemukan');
    }

    currentMahasiswaId = result.data.mahasiswaProfile.id;
    document.getElementById('headerNIM').textContent =
        result.data.mahasiswaProfile.nim;

    return result.data.mahasiswaProfile;
}

async function loadMahasiswaData() {
    try {
        const profile = await getMahasiswaProfile();
        const userId = profile.user_id;

        const query = `
        query($userId: ID!) {
            mahasiswaByUserId(user_id: $userId) {
                id user_id nim nama_lengkap semester_saat_ini ipk
                jurusan { id nama_jurusan }
            }
        }`;

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
        currentMahasiswaId = mahasiswaData.id;

        renderMahasiswaInfo();

    } catch (error) {
        console.error(error);
        showNotification('Gagal memuat data mahasiswa', 'error');
    }
}

async function loadCurrentKrs() {
    if (!currentMahasiswaId) return;

    const query = `query($mahasiswaId: ID!) {
        currentKrsByMahasiswa(mahasiswa_id: $mahasiswaId) {
            id mahasiswa_id semester_id tanggal_pengisian 
            tanggal_persetujuan status total_sks catatan
            semester { id nama_semester tahun_ajaran }
            dosenPa { id nama_lengkap }
            krsDetail {
                id krs_id kelas_id mata_kuliah_id sks status_ambil
                kelas {
                    id nama_kelas kapasitas kuota_terisi
                    dosen { id nama_lengkap }
                    jadwalKuliah {
                        id hari jam_mulai jam_selesai
                        ruangan { id nama_ruangan }
                    }
                }
                mataKuliah { 
                    id kode_mk nama_mk sks semester_rekomendasi 
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
                variables: { mahasiswaId: currentMahasiswaId }
            })
        });

        const result = await response.json();
        
        if (result.errors) throw new Error(result.errors[0].message);
        
        currentKrsData = result.data.currentKrsByMahasiswa;
        
        // ✅ Jika KRS belum ada, buat otomatis
        if (!currentKrsData) {
            console.log('KRS belum ada, membuat KRS baru...');
            await createNewKrs();
            // Reload setelah create
            await loadCurrentKrs();
            return;
        }
        
        if (currentKrsData) {
            currentKrsId = currentKrsData.id;
            krsDetailList = currentKrsData.krsDetail || [];
        }
        
        renderKrsData();
        
    } catch (error) {
        console.error('Error loading KRS:', error);
        showNotification('Gagal memuat data KRS', 'error');
    }
}

async function createNewKrs() {
    const query = `query {
        currentSemester {
            id nama_semester tahun_ajaran
        }
    }`;

    try {
        const semesterResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const semesterResult = await semesterResponse.json();
        const currentSemester = semesterResult.data.currentSemester;

        if (!currentSemester) {
            throw new Error('Tidak ada semester aktif');
        }

        const mutation = `mutation($input: CreateKrsInput!) {
            createKrs(input: $input) {
                id mahasiswa_id semester_id status total_sks
                semester { id nama_semester tahun_ajaran }
            }
        }`;

        const today = new Date().toISOString().split('T')[0];

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query: mutation,
                variables: {
                    input: {
                        mahasiswa_id: parseInt(currentMahasiswaId),
                        semester_id: parseInt(currentSemester.id),
                        tanggal_pengisian: today,
                        status: 'Draft', // ✅ Sesuaikan dengan trigger (bukan DRAFT)
                        total_sks: 0
                    }
                }
            })
        });

        const result = await response.json();
        
        if (result.errors) throw new Error(result.errors[0].message);
        
        currentKrsData = result.data.createKrs;
        currentKrsId = currentKrsData.id;
        krsDetailList = [];

    } catch (error) {
        console.error('Error creating KRS:', error);
        showNotification('Gagal membuat KRS baru', 'error');
    }
}

// ============================================================================
// RENDERING
// ============================================================================

function renderMahasiswaInfo() {
    if (!mahasiswaData) return;

    const initial = mahasiswaData.nama_lengkap.charAt(0).toUpperCase();
    
    setContent('initialMhs', initial);
    setContent('namaMhs', mahasiswaData.nama_lengkap);
    setContent('nimMhs', mahasiswaData.nim);
    setContent('jurusanMhs', mahasiswaData.jurusan?.nama_jurusan || '-');
    setContent('semesterMhs', mahasiswaData.semester_saat_ini || '-');
    setContent('ipkMhs', (mahasiswaData.ipk || 0).toFixed(2));
}

async function renderKrsData() {
    if (!currentKrsData) return;

    // Semester aktif
    setContent('semesterAktif', 
        `${currentKrsData.semester?.nama_semester} ${currentKrsData.semester?.tahun_ajaran}`
    );

    // Stats - ✅ total_sks sudah auto-update dari trigger
    const totalSks = currentKrsData.total_sks || 0;
    const ipk = mahasiswaData?.ipk || 0;
    const maxSks = await getMaxSks(ipk);

    setContent('totalSksKrs', totalSks);
    setContent('maxSksKrs', maxSks);
    setContent('totalMkKrs', krsDetailList.length);

    // Alert status
    renderStatusAlert(currentKrsData.status, totalSks, maxSks);

    // Table
    renderKrsTable();

    // Update button states
    updateButtonStates();
}

function renderStatusAlert(status, totalSks, maxSks) {
    const alertEl = document.getElementById('alertStatusKrs');
    if (!alertEl) return;

    let alertClass, icon, message;

    // ✅ Sesuaikan dengan status dari database (Title Case)
    if (status === 'Disetujui') {
        alertClass = 'bg-green-50 border-green-500 text-green-800';
        icon = '✓';
        message = `<strong>KRS Anda telah disetujui!</strong> Anda dapat melihat jadwal dan mengikuti perkuliahan.`;
    } else if (status === 'Ditolak') {
        alertClass = 'bg-red-50 border-red-500 text-red-800';
        icon = '✗';
        message = `<strong>KRS Anda ditolak.</strong> Silakan revisi dan ajukan kembali. ${currentKrsData.catatan ? `<br><em>Catatan: ${currentKrsData.catatan}</em>` : ''}`;
    } else if (status === 'Diajukan') {
        alertClass = 'bg-yellow-50 border-yellow-500 text-yellow-800';
        icon = '⏳';
        message = `<strong>KRS sedang menunggu persetujuan dosen PA.</strong> Mohon tunggu konfirmasi.`;
    } else {
        // Draft - check SKS
        if (totalSks < MIN_SKS) {
            alertClass = 'bg-red-50 border-red-500 text-red-800';
            icon = '⚠️';
            message = `Total SKS <strong>${totalSks}</strong> kurang dari minimal <strong>${MIN_SKS} SKS</strong>. Tambahkan mata kuliah lagi.`;
        } else if (totalSks > maxSks) {
            alertClass = 'bg-red-50 border-red-500 text-red-800';
            icon = '⚠️';
            message = `Total SKS <strong>${totalSks}</strong> melebihi maksimal <strong>${maxSks} SKS</strong> (IPK: ${mahasiswaData.ipk.toFixed(2)}). Kurangi mata kuliah.`;
        } else {
            alertClass = 'bg-blue-50 border-blue-500 text-blue-800';
            icon = 'ℹ️';
            message = `KRS masih dalam status <strong>DRAFT</strong>. Total SKS: <strong>${totalSks}/${maxSks}</strong>. Ajukan KRS jika sudah sesuai.`;
        }
    }

    alertEl.innerHTML = `
        <div class="${alertClass} p-4 rounded-lg border-l-4">
            <div class="flex items-start">
                <span class="text-xl mr-3">${icon}</span>
                <div class="text-sm">${message}</div>
            </div>
        </div>
    `;
    alertEl.classList.remove('hidden');
}

function renderKrsTable() {
    const tbody = document.getElementById('tableKrsBody');
    const emptyState = document.getElementById('emptyState');
    
    if (!tbody) return;

    if (krsDetailList.length === 0) {
        tbody.innerHTML = '';
        emptyState?.classList.remove('hidden');
        return;
    }

    emptyState?.classList.add('hidden');

    tbody.innerHTML = krsDetailList.map((detail, index) => {
        const mk = detail.mataKuliah;
        const kelas = detail.kelas;
        const jadwal = kelas?.jadwalKuliah?.map(j =>
            `${j.hari}, ${j.jam_mulai}-${j.jam_selesai}`
        ).join('<br>') || '-';

        const statusBadge = getStatusAmbilBadge(detail.status_ambil);
        // ✅ Sesuaikan dengan status dari database
        const canEdit = currentKrsData.status === 'Draft' || currentKrsData.status === 'Ditolak';

        return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-900">${index + 1}</td>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">${mk?.nama_mk || '-'}</div>
                    <div class="text-sm text-gray-500">${mk?.kode_mk || '-'}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900">${kelas?.nama_kelas || '-'}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    ${kelas?.dosen?.nama_lengkap || '-'}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    ${jadwal}
                </td>
                <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900">
                    ${detail.sks || 0}
                </td>
                <td class="px-6 py-4 text-center">
                    ${statusBadge}
                </td>
                <td class="px-6 py-4 text-center">
                    ${canEdit ? `
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openEditMkModal(${detail.id})" 
                                class="px-3 py-1.5 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors">
                                Edit
                            </button>
                            <button onclick="deleteMataKuliah(${detail.id}, '${mk?.nama_mk}')" 
                                class="px-3 py-1.5 text-xs bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                Hapus
                            </button>
                        </div>
                    ` : `
                        <span class="text-xs text-gray-400">Terkunci</span>
                    `}
                </td>
            </tr>
        `;
    }).join('');
}

function updateButtonStates() {
    const btnAddMk = document.getElementById('btnAddMk');
    const btnSubmitKrs = document.getElementById('btnSubmitKrs');

    if (!currentKrsData) return;

    // ✅ Sesuaikan dengan status dari database
    const canEdit = currentKrsData.status === 'Draft' || currentKrsData.status === 'Ditolak';
    const canSubmit = canEdit && krsDetailList.length > 0;

    if (btnAddMk) {
        btnAddMk.disabled = !canEdit;
        if (!canEdit) {
            btnAddMk.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            btnAddMk.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    if (btnSubmitKrs) {
        btnSubmitKrs.disabled = !canSubmit;
        if (!canSubmit) {
            btnSubmitKrs.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            btnSubmitKrs.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}

// ============================================================================
// KRS ACTIONS
// ============================================================================

async function submitKrs() {
    if (!currentKrsData || !currentKrsId) return;

    // ✅ total_sks sudah auto-update dari trigger, ambil dari currentKrsData
    const totalSks = currentKrsData.total_sks || 0;
    const ipk = mahasiswaData?.ipk || 0;
    const maxSks = await getMaxSks(ipk);

    // Validasi
    if (totalSks < MIN_SKS) {
        showNotification(`Total SKS (${totalSks}) kurang dari minimal ${MIN_SKS} SKS`, 'error');
        return;
    }

    if (totalSks > maxSks) {
        showNotification(`Total SKS (${totalSks}) melebihi maksimal ${maxSks} SKS`, 'error');
        return;
    }

    if (!confirm('Ajukan KRS untuk disetujui dosen PA? Anda tidak dapat mengubah KRS setelah diajukan.')) {
        return;
    }

    const mutation = `mutation($id: ID!, $input: UpdateKrsInput!) {
        updateKrs(id: $id, input: $input) {
            id status total_sks
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query: mutation,
                variables: {
                    id: parseInt(currentKrsId),
                    input: {
                        status: 'Diajukan', // ✅ Sesuaikan dengan trigger
                        // total_sks tidak perlu diupdate manual, trigger akan handle
                    }
                }
            })
        });

        const result = await response.json();
        
        if (result.errors) throw new Error(result.errors[0].message);

        showNotification('KRS berhasil diajukan! Menunggu persetujuan dosen PA.', 'success');
        await loadCurrentKrs();

    } catch (error) {
        console.error('Error submitting KRS:', error);
        showNotification('Gagal mengajukan KRS: ' + error.message, 'error');
    }
}

async function deleteMataKuliah(detailId, namaMk) {
    if (!confirm(`Hapus "${namaMk}" dari KRS?`)) return;

    const mutation = `mutation($id: ID!) {
        forceDeleteKrsDetail(id: $id) { id }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query: mutation,
                variables: { id: parseInt(detailId) }
            })
        });

        const result = await response.json();
        
        if (result.errors) throw new Error(result.errors[0].message);

        // ✅ HAPUS manual update kuota - trigger akan handle
        // await updateKuotaKelas(detail.kelas_id, -1);

        showNotification(`"${namaMk}" berhasil dihapus`, 'success');
        
        // ✅ Tunggu reload selesai
        await loadCurrentKrs();

    } catch (error) {
        console.error('Error deleting:', error);
        showNotification('Gagal menghapus: ' + error.message, 'error');
    }
}

// ============================================================================
// SKS LIMIT
// ============================================================================

async function getMaxSks(ipk) {
    try {
        const query = `query {
            allSksLimit {
                id min_ipk max_ipk max_sks keterangan
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const sksLimitList = result.data.allSksLimit || [];

        // Mahasiswa baru
        if (!ipk || ipk === 0) {
            const mahasiswaBaru = sksLimitList.find(item =>
                item.keterangan?.toLowerCase().includes('baru')
            );
            return mahasiswaBaru?.max_sks || 12;
        }

        // Berdasarkan IPK
        const matchedLimit = sksLimitList.find(item => {
            const minIpk = parseFloat(item.min_ipk) || 0;
            const maxIpk = parseFloat(item.max_ipk) || 4.0;
            return ipk >= minIpk && ipk <= maxIpk;
        });

        return matchedLimit?.max_sks || 16;

    } catch (error) {
        console.error('Error getting max SKS:', error);
        // Fallback
        if (!ipk || ipk === 0) return 12;
        if (ipk >= 3.50) return 24;
        if (ipk >= 3.00) return 22;
        if (ipk >= 2.50) return 20;
        if (ipk >= 2.00) return 18;
        return 16;
    }
}

// ============================================================================
// ✅ HAPUS FUNGSI updateKuotaKelas - Trigger akan handle
// ============================================================================

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

function setContent(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
}

function getStatusAmbilBadge(status) {
    const badges = {
        'BARU': 'bg-blue-100 text-blue-800',
        'MENGULANG': 'bg-orange-100 text-orange-800'
    };
    const cls = badges[status?.toUpperCase()] || 'bg-gray-100 text-gray-800';
    return `<span class="${cls} px-2 py-1 rounded text-xs font-semibold">${status || '-'}</span>`;
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