// detailKrs-create.js - Sistem Tambah Mata Kuliah KRS

const API_URL = "/graphql";

// State untuk modal tambah
let availableMataKuliah = [];
let selectedKelasData = null;

// Batas SKS berdasarkan IP
function getMaxSks(ipSemester) {
    if (!ipSemester || ipSemester === 0) return 20; // Mahasiswa baru default 20 SKS
    if (ipSemester >= 3.50) return 24;
    if (ipSemester >= 3.00) return 22;
    if (ipSemester >= 2.50) return 20;
    if (ipSemester >= 2.00) return 18;
    return 16; // IP < 2.00
}

const MIN_SKS = 12; // Minimal SKS per semester

// Buka modal tambah mata kuliah
async function openAddKrsDetailModal() {
    const modal = document.getElementById('modalAddKrsDetail');
    if (!modal) {
        console.error('Modal add KRS Detail tidak ditemukan');
        return;
    }

    // Reset form
    resetAddKrsDetailForm();
    
    // Load data mata kuliah
    await loadAvailableMataKuliah();
    
    // Tampilkan modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Tutup modal
function closeAddKrsDetailModal() {
    const modal = document.getElementById('modalAddKrsDetail');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    resetAddKrsDetailForm();
}

// Reset form
function resetAddKrsDetailForm() {
    const form = document.getElementById('formAddKrsDetail');
    if (form) form.reset();
    
    selectedKelasData = null;
    
    // Reset displays
    const elements = [
        'addKrsDetailKodeMk',
        'addKrsDetailSksMk',
        'addKrsDetailDosen',
        'addKrsDetailJadwal',
        'addKrsDetailKuota',
        'addKrsDetailError',
        'addKrsDetailInfo'
    ];
    
    elements.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = '-';
    });
    
    // Hide info sections
    const infoSection = document.getElementById('addKrsDetailInfoSection');
    if (infoSection) infoSection.classList.add('hidden');
    
    // Reset select kelas
    const selectKelas = document.getElementById('addKrsDetailKelasId');
    if (selectKelas) {
        selectKelas.innerHTML = '<option value="">-- Pilih Mata Kuliah Terlebih Dahulu --</option>';
        selectKelas.disabled = true;
    }
}

// Load mata kuliah yang tersedia
async function loadAvailableMataKuliah() {
    const query = `
    query {
        allMataKuliah {
            id
            kode_mk
            nama_mk
            sks
            semester_rekomendasi
            kelas {
                id
                nama_kelas
                kapasitas
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
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }

        availableMataKuliah = result.data.allMataKuliah || [];
        
        // Normalize data: set default kuota_terisi = 0 jika null
        availableMataKuliah = availableMataKuliah.map(mk => ({
            ...mk,
            kelas: mk.kelas?.map(k => ({
                ...k,
                kuota_terisi: k.kuota_terisi ?? 0  // Default 0 jika null/undefined
            })) || []
        }));
        
        populateMataKuliahSelect();
        
    } catch (error) {
        console.error('Error loading mata kuliah:', error);
        showAddKrsDetailError('Gagal memuat data mata kuliah: ' + error.message);
    }
}

// Populate select mata kuliah
function populateMataKuliahSelect() {
    const select = document.getElementById('addKrsDetailMataKuliahId');
    if (!select) return;

    select.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';
    
    if (!currentKrsData || !currentKrsData.mahasiswa) {
        showAddKrsDetailError('Data mahasiswa tidak tersedia');
        return;
    }

    const semesterMahasiswa = currentKrsData.mahasiswa.semester_saat_ini || 1;
    
    // Filter mata kuliah
    const filteredMk = availableMataKuliah.filter(mk => {
        // Cek semester rekomendasi
        if (mk.semester_rekomendasi && semesterMahasiswa < mk.semester_rekomendasi) {
            return false;
        }
        
        // Cek apakah sudah diambil di KRS ini
        const sudahDiambil = krsDetailList.some(detail => 
            detail.mata_kuliah_id === mk.id
        );
        
        return !sudahDiambil && mk.kelas && mk.kelas.length > 0;
    });

    if (filteredMk.length === 0) {
        select.innerHTML = '<option value="">-- Tidak ada mata kuliah tersedia --</option>';
        showAddKrsDetailInfo('Semua mata kuliah sudah diambil atau tidak sesuai semester rekomendasi');
        return;
    }

    // Grouping berdasarkan semester rekomendasi
    const grouped = {};
    filteredMk.forEach(mk => {
        const sem = mk.semester_rekomendasi || 0;
        if (!grouped[sem]) grouped[sem] = [];
        grouped[sem].push(mk);
    });

    // Tampilkan dengan optgroup
    Object.keys(grouped).sort((a, b) => a - b).forEach(sem => {
        const optgroup = document.createElement('optgroup');
        optgroup.label = `Semester ${sem || 'Umum'}`;
        
        grouped[sem].forEach(mk => {
            const option = document.createElement('option');
            option.value = mk.id;
            option.textContent = `${mk.kode_mk} - ${mk.nama_mk} (${mk.sks} SKS)`;
            option.dataset.mk = JSON.stringify(mk);
            optgroup.appendChild(option);
        });
        
        select.appendChild(optgroup);
    });
}

// Handle perubahan mata kuliah
function onMataKuliahChange() {
    const select = document.getElementById('addKrsDetailMataKuliahId');
    const kelasSelect = document.getElementById('addKrsDetailKelasId');
    const infoSection = document.getElementById('addKrsDetailInfoSection');
    
    if (!select || !kelasSelect || !infoSection) return;

    const selectedOption = select.options[select.selectedIndex];
    
    if (!selectedOption || !selectedOption.value) {
        // Reset
        kelasSelect.innerHTML = '<option value="">-- Pilih Mata Kuliah Terlebih Dahulu --</option>';
        kelasSelect.disabled = true;
        infoSection.classList.add('hidden');
        return;
    }

    const mkData = JSON.parse(selectedOption.dataset.mk);
    
    // Tampilkan info mata kuliah
    document.getElementById('addKrsDetailKodeMk').textContent = mkData.kode_mk;
    document.getElementById('addKrsDetailSksMk').textContent = `${mkData.sks} SKS`;
    
    // Populate kelas
    populateKelasSelect(mkData);
    
    infoSection.classList.remove('hidden');
}

// Populate select kelas
function populateKelasSelect(mkData) {
    const select = document.getElementById('addKrsDetailKelasId');
    if (!select) return;

    select.innerHTML = '<option value="">-- Pilih Kelas --</option>';
    select.disabled = false;

    if (!mkData.kelas || mkData.kelas.length === 0) {
        select.innerHTML = '<option value="">-- Tidak ada kelas tersedia --</option>';
        select.disabled = true;
        return;
    }

    mkData.kelas.forEach(kelas => {
        const kuotaTerisi = kelas.kuota_terisi ?? 0;  // Default 0
        const sisaKuota = (kelas.kapasitas ?? 0) - kuotaTerisi;
        const isFull = sisaKuota <= 0;
        
        const option = document.createElement('option');
        option.value = kelas.id;
        option.textContent = `${kelas.nama_kelas} - Sisa Kuota: ${sisaKuota}/${kelas.kapasitas ?? 0}${isFull ? ' (PENUH)' : ''}`;
        option.dataset.kelas = JSON.stringify({...kelas, mkData});
        option.disabled = isFull;
        
        select.appendChild(option);
    });
}

// Handle perubahan kelas
function onKelasChange() {
    const select = document.getElementById('addKrsDetailKelasId');
    if (!select || !select.value) {
        clearKelasInfo();
        return;
    }

    const selectedOption = select.options[select.selectedIndex];
    const kelasData = JSON.parse(selectedOption.dataset.kelas);
    
    selectedKelasData = kelasData;
    displayKelasInfo(kelasData);
    validateKelasSelection(kelasData);
}

// Tampilkan info kelas
function displayKelasInfo(kelasData) {
    // Dosen
    const dosen = kelasData.dosen?.nama_lengkap || 'Belum ditentukan';
    document.getElementById('addKrsDetailDosen').textContent = dosen;
    
    // Jadwal
    if (kelasData.jadwalKuliah && kelasData.jadwalKuliah.length > 0) {
        const jadwalTexts = kelasData.jadwalKuliah.map(j => {
            const ruangan = j.ruangan?.nama_ruangan || '-';
            return `${j.hari}, ${j.jam_mulai}-${j.jam_selesai} (${ruangan})`;
        });
        document.getElementById('addKrsDetailJadwal').innerHTML = jadwalTexts.join('<br>');
    } else {
        document.getElementById('addKrsDetailJadwal').textContent = 'Belum ada jadwal';
    }
    
    // Kuota
    const kuotaTerisi = kelasData.kuota_terisi ?? 0;
    const kapasitas = kelasData.kapasitas ?? 0;
    const sisaKuota = kapasitas - kuotaTerisi;
    const kuotaText = `${sisaKuota} / ${kapasitas}`;
    const kuotaEl = document.getElementById('addKrsDetailKuota');
    kuotaEl.textContent = kuotaText;
    kuotaEl.className = sisaKuota > 5 ? 'font-semibold text-green-600' : 
                        sisaKuota > 0 ? 'font-semibold text-yellow-600' : 
                        'font-semibold text-red-600';
}

// Clear info kelas
function clearKelasInfo() {
    selectedKelasData = null;
    document.getElementById('addKrsDetailDosen').textContent = '-';
    document.getElementById('addKrsDetailJadwal').textContent = '-';
    document.getElementById('addKrsDetailKuota').textContent = '-';
    hideAddKrsDetailError();
}

// Validasi pemilihan kelas
function validateKelasSelection(kelasData) {
    hideAddKrsDetailError();
    
    // 1. Cek konflik jadwal
    const hasConflict = checkJadwalConflict(kelasData);
    if (hasConflict) {
        showAddKrsDetailError('⚠️ KONFLIK JADWAL: Waktu kuliah bentrok dengan mata kuliah lain yang sudah diambil!');
        return false;
    }
    
    // 2. Cek total SKS
    const currentTotalSks = krsDetailList.reduce((sum, d) => sum + (d.sks || 0), 0);
    const newTotalSks = currentTotalSks + kelasData.mkData.sks;
    
    const ipSemester = currentKrsData.mahasiswa.ip_semester || 0;
    const maxSks = getMaxSks(ipSemester);
    
    if (newTotalSks > maxSks) {
        showAddKrsDetailError(`⚠️ MELEBIHI BATAS SKS: Total SKS akan menjadi ${newTotalSks}, maksimal ${maxSks} SKS (IP Semester: ${ipSemester.toFixed(2)})`);
        return false;
    }
    
    // Info SKS
    showAddKrsDetailInfo(`✓ Total SKS setelah ditambahkan: ${newTotalSks}/${maxSks} SKS`);
    return true;
}

// Cek konflik jadwal
function checkJadwalConflict(kelasData) {
    if (!kelasData.jadwalKuliah || kelasData.jadwalKuliah.length === 0) {
        return false; // Tidak ada jadwal, tidak ada konflik
    }
    
    for (const detail of krsDetailList) {
        if (!detail.kelas?.jadwalKuliah) continue;
        
        for (const jadwalBaru of kelasData.jadwalKuliah) {
            for (const jadwalLama of detail.kelas.jadwalKuliah) {
                // Cek hari yang sama
                if (jadwalBaru.hari === jadwalLama.hari) {
                    // Cek overlap waktu
                    if (isTimeOverlap(
                        jadwalBaru.jam_mulai, jadwalBaru.jam_selesai,
                        jadwalLama.jam_mulai, jadwalLama.jam_selesai
                    )) {
                        return true;
                    }
                }
            }
        }
    }
    
    return false;
}

// Helper untuk cek overlap waktu
function isTimeOverlap(start1, end1, start2, end2) {
    return (start1 < end2 && end1 > start2);
}

// Cek apakah mahasiswa pernah ambil mata kuliah ini
async function checkPreviousEnrollment(mataKuliahId) {
    // Query untuk cek nilai mahasiswa di mata kuliah tertentu
    // Sesuaikan dengan schema Nilai Anda
    const query = `
    query($mahasiswaId: ID!, $mataKuliahId: ID!) {
        allNilai(
            mahasiswa_id: $mahasiswaId,
            mata_kuliah_id: $mataKuliahId
        ) {
            id
            nilai_huruf
            semester {
                nama_semester
            }
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: {
                    mahasiswaId: currentKrsData.mahasiswa_id,
                    mataKuliahId: mataKuliahId
                }
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.warn('Tidak bisa cek riwayat nilai:', result.errors[0].message);
            return null;
        }
        
        const nilais = result.data?.allNilai || [];
        if (nilais.length === 0) return null;
        
        // Ambil nilai terakhir
        const lastNilai = nilais[nilais.length - 1];
        return lastNilai;
        
    } catch (error) {
        console.error('Error checking previous enrollment:', error);
        return null;
    }
}

// Submit tambah KRS Detail
async function submitAddKrsDetail(event) {
    if (event) event.preventDefault();
    
    const submitBtn = document.getElementById('btnSubmitAddKrsDetail');
    if (submitBtn) submitBtn.disabled = true;

    try {
        // Validasi
        if (!selectedKelasData) {
            throw new Error('Silakan pilih mata kuliah dan kelas');
        }

        // Validasi ulang
        if (!validateKelasSelection(selectedKelasData)) {
            throw new Error('Validasi gagal. Periksa pesan error di atas.');
        }

        // Tentukan status_ambil
        const previousNilai = await checkPreviousEnrollment(selectedKelasData.mkData.id);
        let statusAmbil = 'BARU';
        
        if (previousNilai) {
            if (['D', 'E'].includes(previousNilai.nilai_huruf)) {
                statusAmbil = 'MENGULANG';
                showAddKrsDetailInfo(`ℹ️ Status: MENGULANG (Nilai sebelumnya: ${previousNilai.nilai_huruf})`);
            }
        }

        // Mutation GraphQL
        const mutation = `
        mutation($input: CreateKrsDetailInput!) {
            createKrsDetail(input: $input) {
                id
                krs_id
                kelas_id
                mata_kuliah_id
                sks
                status_ambil
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
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: {
                    input: {
                        krs_id: parseInt(currentKrsId),  // Convert to Int
                        kelas_id: parseInt(selectedKelasData.id),  // Convert to Int
                        mata_kuliah_id: parseInt(selectedKelasData.mkData.id),  // Convert to Int
                        sks: parseInt(selectedKelasData.mkData.sks),  // Convert to Int
                        status_ambil: statusAmbil
                    }
                }
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }

        // Update kuota kelas (increment kuota_terisi)
        await updateKuotaKelas(selectedKelasData.id, 1);

        // Reload data KRS
        await loadKrsDetail();
        
        // Close modal
        closeAddKrsDetailModal();
        
        // Show success message
        showSuccessNotification(`Mata kuliah ${selectedKelasData.mkData.nama_mk} berhasil ditambahkan!`);
        
    } catch (error) {
        console.error('Error adding KRS Detail:', error);
        showAddKrsDetailError('Gagal menambah mata kuliah: ' + error.message);
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
}

// Update kuota kelas
async function updateKuotaKelas(kelasId, increment) {
    // Query untuk get current kuota
    const queryKelas = `
    query($id: ID!) {
        kelas(id: $id) {
            id
            kuota_terisi
        }
    }`;
    
    const mutation = `
    mutation($id: ID!, $input: UpdateKelasInput!) {
        updateKelas(id: $id, input: $input) {
            id
            kuota_terisi
        }
    }`;

    try {
        // Get current kuota_terisi
        const responseGet = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: queryKelas,
                variables: { id: parseInt(kelasId) }  // Convert to Int
            })
        });

        const resultGet = await responseGet.json();
        
        if (resultGet.errors) {
            console.error('Error getting kelas:', resultGet.errors);
            return;
        }

        const currentKuotaTerisi = resultGet.data?.kelas?.kuota_terisi ?? 0;
        const newKuotaTerisi = Math.max(0, currentKuotaTerisi + increment); // Tidak boleh negatif

        // Update kuota
        const responseUpdate = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: {
                    id: parseInt(kelasId),  // Convert to Int
                    input: {
                        kuota_terisi: newKuotaTerisi
                    }
                }
            })
        });

        const resultUpdate = await responseUpdate.json();
        
        if (resultUpdate.errors) {
            console.error('Error updating kuota:', resultUpdate.errors);
        }
    } catch (error) {
        console.error('Error updating kuota:', error);
    }
}

// Show error message
function showAddKrsDetailError(message) {
    const errorEl = document.getElementById('addKrsDetailError');
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }
    hideAddKrsDetailInfo();
}

// Hide error message
function hideAddKrsDetailError() {
    const errorEl = document.getElementById('addKrsDetailError');
    if (errorEl) {
        errorEl.classList.add('hidden');
        errorEl.textContent = '';
    }
}

// Show info message
function showAddKrsDetailInfo(message) {
    const infoEl = document.getElementById('addKrsDetailInfo');
    if (infoEl) {
        infoEl.textContent = message;
        infoEl.classList.remove('hidden');
    }
}

// Hide info message
function hideAddKrsDetailInfo() {
    const infoEl = document.getElementById('addKrsDetailInfo');
    if (infoEl) {
        infoEl.classList.add('hidden');
        infoEl.textContent = '';
    }
}

// Show success notification
function showSuccessNotification(message) {
    // Buat notifikasi toast
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-3 animate-slide-in';
    toast.innerHTML = `
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-slide-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Delete KRS Detail
async function deleteKrsDetail(id, namaMk) {
    if (!confirm(`Hapus mata kuliah "${namaMk}" dari KRS?`)) return;

    try {
        // Ambil data detail untuk update kuota
        const detail = krsDetailList.find(d => d.id == id);  // Loose comparison
        
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
                variables: { id: parseInt(id) }  // Convert to Int
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }

        // Update kuota kelas (decrement)
        if (detail && detail.kelas_id) {
            await updateKuotaKelas(detail.kelas_id, -1);
        }

        // Reload data
        await loadKrsDetail();
        
        showSuccessNotification(`Mata kuliah "${namaMk}" berhasil dihapus dari KRS`);
        
    } catch (error) {
        console.error('Error deleting KRS Detail:', error);
        alert('Gagal menghapus mata kuliah: ' + error.message);
    }
}