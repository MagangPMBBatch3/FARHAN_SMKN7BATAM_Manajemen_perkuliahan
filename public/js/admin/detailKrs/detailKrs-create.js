// ============================================================================
// STATE MANAGEMENT
// ============================================================================

let availableMataKuliah = [];
let selectedKelasData = null;

// ============================================================================
// MODAL MANAGEMENT
// ============================================================================

/**
 * Buka modal tambah mata kuliah
 */
async function openAddKrsDetailModal() {
    const modal = document.getElementById('modalAddKrsDetail');
    if (!modal) {
        console.error('Modal tidak ditemukan');
        return;
    }

    resetAddKrsDetailForm();
    await loadAvailableMataKuliah();

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

/**
 * Tutup modal tambah mata kuliah
 */
function closeAddKrsDetailModal() {
    document.getElementById('modalAddKrsDetail')?.classList.add('hidden');
    document.body.style.overflow = 'auto';
    resetAddKrsDetailForm();
}

/**
 * Reset form tambah mata kuliah
 */
function resetAddKrsDetailForm() {
    document.getElementById('formAddKrsDetail')?.reset();
    selectedKelasData = null;

    ['addKrsDetailKodeMk', 'addKrsDetailSksMk', 'addKrsDetailDosen',
        'addKrsDetailJadwal', 'addKrsDetailKuota'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = '-';
        });

    document.getElementById('addKrsDetailInfoSection')?.classList.add('hidden');

    const selectKelas = document.getElementById('addKrsDetailKelasId');
    if (selectKelas) {
        selectKelas.innerHTML = '<option value="">-- Pilih Mata Kuliah Terlebih Dahulu --</option>';
        selectKelas.disabled = true;
    }

    hideAddKrsDetailError();
    hideAddKrsDetailInfo();
}

// ============================================================================
// DATA LOADING
// ============================================================================

/**
 * Mengambil daftar mata kuliah yang tersedia
 */
async function loadAvailableMataKuliah() {
    const query = `query {
        allMataKuliah {
            id kode_mk nama_mk sks semester_rekomendasi
            kelas {
                id nama_kelas kapasitas kuota_terisi
                dosen { id nama_lengkap }
                jadwalKuliah {
                    id hari jam_mulai jam_selesai
                    ruangan { id nama_ruangan }
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
        if (result.errors) throw new Error(result.errors[0].message);

        availableMataKuliah = result.data.allMataKuliah || [];

        // Normalize kuota_terisi
        availableMataKuliah = availableMataKuliah.map(mk => ({
            ...mk,
            kelas: mk.kelas?.map(k => ({ ...k, kuota_terisi: k.kuota_terisi ?? 0 })) || []
        }));

        populateMataKuliahSelect();

    } catch (error) {
        console.error('Error loading mata kuliah:', error);
        showAddKrsDetailError('Gagal memuat data: ' + error.message);
    }
}

/**
 * Mengecek riwayat nilai mahasiswa
 */
async function checkPreviousEnrollment(mataKuliahId) {
    const query = `query($mahasiswaId: ID!, $mataKuliahId: ID!) {
        allNilai(mahasiswa_id: $mahasiswaId, mata_kuliah_id: $mataKuliahId) {
            id nilai_huruf
            semester { nama_semester }
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
        if (result.errors) return null;

        const nilais = result.data?.allNilai || [];
        return nilais.length > 0 ? nilais[nilais.length - 1] : null;

    } catch (error) {
        console.error('Error checking enrollment:', error);
        return null;
    }
}

// ============================================================================
// POPULATE DROPDOWNS
// ============================================================================

/**
 * Populate dropdown mata kuliah
 */
function populateMataKuliahSelect() {
    const select = document.getElementById('addKrsDetailMataKuliahId');
    if (!select) return;

    select.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';

    if (!currentKrsData?.mahasiswa) {
        showAddKrsDetailError('Data mahasiswa tidak tersedia');
        return;
    }

    const semesterMahasiswa = currentKrsData.mahasiswa.semester_saat_ini || 1;

    // Filter mata kuliah
    const filteredMk = availableMataKuliah.filter(mk => {
        // Cek semester rekomendasi
        if (mk.semester_rekomendasi && semesterMahasiswa < mk.semester_rekomendasi) return false;

        // Cek sudah diambil
        const sudahDiambil = krsDetailList.some(d => d.mata_kuliah_id === mk.id);

        return !sudahDiambil && mk.kelas?.length > 0;
    });

    if (filteredMk.length === 0) {
        select.innerHTML = '<option value="">-- Tidak ada mata kuliah tersedia --</option>';
        showAddKrsDetailInfo('Semua mata kuliah sudah diambil');
        return;
    }

    // Group by semester
    const grouped = {};
    filteredMk.forEach(mk => {
        const sem = mk.semester_rekomendasi || 0;
        if (!grouped[sem]) grouped[sem] = [];
        grouped[sem].push(mk);
    });

    // Render with optgroup
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

/**
 * Populate dropdown kelas
 */
function populateKelasSelect(mkData) {
    const select = document.getElementById('addKrsDetailKelasId');
    if (!select) return;

    select.innerHTML = '<option value="">-- Pilih Kelas --</option>';
    select.disabled = false;

    if (!mkData.kelas?.length) {
        select.innerHTML = '<option value="">-- Tidak ada kelas tersedia --</option>';
        select.disabled = true;
        return;
    }

    mkData.kelas.forEach(kelas => {
        const sisaKuota = (kelas.kapasitas ?? 0) - (kelas.kuota_terisi ?? 0);
        const isFull = sisaKuota <= 0;

        const option = document.createElement('option');
        option.value = kelas.id;
        option.textContent = `${kelas.nama_kelas} - Kuota: ${sisaKuota}/${kelas.kapasitas}${isFull ? ' (PENUH)' : ''}`;
        option.dataset.kelas = JSON.stringify({ ...kelas, mkData });
        option.disabled = isFull;

        select.appendChild(option);
    });
}

// ============================================================================
// EVENT HANDLERS
// ============================================================================

/**
 * Handler ketika mata kuliah dipilih
 */
function onMataKuliahChange() {
    const select = document.getElementById('addKrsDetailMataKuliahId');
    const selectedOption = select?.options[select.selectedIndex];

    if (!selectedOption?.value) {
        document.getElementById('addKrsDetailKelasId').disabled = true;
        document.getElementById('addKrsDetailInfoSection')?.classList.add('hidden');
        return;
    }

    const mkData = JSON.parse(selectedOption.dataset.mk);

    // Tampilkan info mata kuliah
    const kodeMkEl = document.getElementById('addKrsDetailKodeMk');
    const sksMkEl = document.getElementById('addKrsDetailSksMk');
    if (kodeMkEl) kodeMkEl.textContent = mkData.kode_mk;
    if (sksMkEl) sksMkEl.textContent = `${mkData.sks} SKS`;

    populateKelasSelect(mkData);
    document.getElementById('addKrsDetailInfoSection')?.classList.remove('hidden');
}

/**
 * Handler ketika kelas dipilih
 */
function onKelasChange() {
    const select = document.getElementById('addKrsDetailKelasId');
    if (!select?.value) {
        clearKelasInfo();
        return;
    }

    const kelasData = JSON.parse(select.options[select.selectedIndex].dataset.kelas);
    selectedKelasData = kelasData;
    displayKelasInfo(kelasData);
    validateKelasSelection(kelasData);
}

/**
 * Tampilkan info kelas yang dipilih
 */
function displayKelasInfo(kelasData) {
    // Dosen
    const dosenEl = document.getElementById('addKrsDetailDosen');
    if (dosenEl) dosenEl.textContent = kelasData.dosen?.nama_lengkap || 'Belum ditentukan';

    // Jadwal
    const jadwalEl = document.getElementById('addKrsDetailJadwal');
    if (jadwalEl) {
        if (kelasData.jadwalKuliah?.length > 0) {
            const jadwal = kelasData.jadwalKuliah.map(j =>
                `${j.hari}, ${j.jam_mulai}-${j.jam_selesai} (${j.ruangan?.nama_ruangan || '-'})`
            ).join('<br>');
            jadwalEl.innerHTML = jadwal;
        } else {
            jadwalEl.textContent = 'Belum ada jadwal';
        }
    }

    // Kuota
    const kuotaEl = document.getElementById('addKrsDetailKuota');
    if (kuotaEl) {
        const sisaKuota = (kelasData.kapasitas ?? 0) - (kelasData.kuota_terisi ?? 0);
        kuotaEl.textContent = `${sisaKuota} / ${kelasData.kapasitas ?? 0}`;
        kuotaEl.className = sisaKuota > 5 ? 'font-semibold text-green-600' :
            sisaKuota > 0 ? 'font-semibold text-yellow-600' : 'font-semibold text-red-600';
    }
}

/**
 * Bersihkan info kelas
 */
function clearKelasInfo() {
    selectedKelasData = null;
    const dosenEl = document.getElementById('addKrsDetailDosen');
    const jadwalEl = document.getElementById('addKrsDetailJadwal');
    const kuotaEl = document.getElementById('addKrsDetailKuota');

    if (dosenEl) dosenEl.textContent = '-';
    if (jadwalEl) jadwalEl.textContent = '-';
    if (kuotaEl) kuotaEl.textContent = '-';

    hideAddKrsDetailError();
}

// ============================================================================
// VALIDATION
// ============================================================================

/**
 * Validasi kelas yang dipilih
 */
async function validateKelasSelection(kelasData) {
    hideAddKrsDetailError();

    // Cek konflik jadwal
    if (checkJadwalConflict(kelasData)) {
        showAddKrsDetailError('⚠️ KONFLIK JADWAL: Waktu kuliah bentrok!');
        return false;
    }

    // Cek total SKS
    const currentTotalSks = krsDetailList.reduce((sum, d) => sum + (d.sks || 0), 0);
    const newTotalSks = currentTotalSks + kelasData.mkData.sks;
    const ipk = currentKrsData.mahasiswa.ipk || 0;
    const maxSks = await getMaxSks(ipk);

    if (newTotalSks > maxSks) {
        showAddKrsDetailError(`⚠️ Total SKS akan menjadi ${newTotalSks}, maksimal ${maxSks} SKS (IPK: ${ipk.toFixed(2)})`);
        return false;
    }

    showAddKrsDetailInfo(`✓ Total SKS setelah ditambah: ${newTotalSks}/${maxSks} SKS`);
    return true;
}

/**
 * Cek konflik jadwal
 */
function checkJadwalConflict(kelasData) {
    if (!kelasData.jadwalKuliah?.length) return false;

    for (const detail of krsDetailList) {
        if (!detail.kelas?.jadwalKuliah) continue;

        for (const jadwalBaru of kelasData.jadwalKuliah) {
            for (const jadwalLama of detail.kelas.jadwalKuliah) {
                if (jadwalBaru.hari === jadwalLama.hari &&
                    jadwalBaru.jam_mulai < jadwalLama.jam_selesai &&
                    jadwalBaru.jam_selesai > jadwalLama.jam_mulai) {
                    return true;
                }
            }
        }
    }
    return false;
}

// ============================================================================
// SUBMIT FORM
// ============================================================================

/**
 * Submit form tambah mata kuliah
 */
async function submitAddKrsDetail(event) {
    event?.preventDefault();

    const submitBtn = document.getElementById('btnSubmitAddKrsDetail');
    if (submitBtn) submitBtn.disabled = true;

    try {
        if (!selectedKelasData) throw new Error('Silakan pilih mata kuliah dan kelas');

        const isValid = await validateKelasSelection(selectedKelasData);
        if (!isValid) throw new Error('Validasi gagal');

        // Cek riwayat nilai
        const previousNilai = await checkPreviousEnrollment(selectedKelasData.mkData.id);
        const statusAmbil = previousNilai && ['D', 'E'].includes(previousNilai.nilai_huruf)
            ? 'MENGULANG' : 'BARU';

        if (statusAmbil === 'MENGULANG') {
            showAddKrsDetailInfo(`ℹ️ Status: MENGULANG (Nilai sebelumnya: ${previousNilai.nilai_huruf})`);
        }

        // Mutation
        const mutation = `mutation($input: CreateKrsDetailInput!) {
            createKrsDetail(input: $input) {
                id krs_id kelas_id mata_kuliah_id sks status_ambil
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query: mutation,
                variables: {
                    input: {
                        krs_id: parseInt(currentKrsId),
                        kelas_id: parseInt(selectedKelasData.id),
                        mata_kuliah_id: parseInt(selectedKelasData.mkData.id),
                        sks: parseInt(selectedKelasData.mkData.sks),
                        status_ambil: statusAmbil
                    }
                }
            })
        });

        const result = await response.json();
        if (result.errors) throw new Error(result.errors[0].message);

        // Update kuota kelas
        // await updateKuotaKelas(selectedKelasData.id, 1);

        // Reload data KRS
        await loadKrsDetail();

        closeAddKrsDetailModal();
        showSuccessNotification(`${selectedKelasData.mkData.nama_mk} berhasil ditambahkan!`);

    } catch (error) {
        console.error('Error:', error);
        showAddKrsDetailError('Gagal menambah: ' + error.message);
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
}

// ============================================================================
// UI HELPERS
// ============================================================================

function showAddKrsDetailError(msg) {
    const el = document.getElementById('addKrsDetailError');
    if (el) {
        el.textContent = msg;
        el.classList.remove('hidden');
    }
    hideAddKrsDetailInfo();
}

function hideAddKrsDetailError() {
    const el = document.getElementById('addKrsDetailError');
    if (el) {
        el.classList.add('hidden');
        el.textContent = '';
    }
}

function showAddKrsDetailInfo(msg) {
    const el = document.getElementById('addKrsDetailInfo');
    if (el) {
        el.textContent = msg;
        el.classList.remove('hidden');
    }
}

function hideAddKrsDetailInfo() {
    const el = document.getElementById('addKrsDetailInfo');
    if (el) {
        el.classList.add('hidden');
        el.textContent = '';
    }
}