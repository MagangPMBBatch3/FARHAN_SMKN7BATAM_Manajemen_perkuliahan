// ============================================================================
// STATE MANAGEMENT
// ============================================================================

let availableMataKuliah = [];
let selectedKelasData = null;

// ============================================================================
// MODAL MANAGEMENT
// ============================================================================

async function openAddMkModal() {
    const modal = document.getElementById('modalAddMk');
    if (!modal) return;

    resetAddMkForm();
    await loadAvailableMataKuliah();

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAddMkModal() {
    document.getElementById('modalAddMk')?.classList.add('hidden');
    document.body.style.overflow = 'auto';
    resetAddMkForm();
}

function resetAddMkForm() {
    document.getElementById('formAddMk')?.reset();
    selectedKelasData = null;

    ['addMkKode', 'addMkSks', 'addMkDosen', 'addMkJadwal', 'addMkKuota']
        .forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = '-';
        });

    document.getElementById('addMkInfoSection')?.classList.add('hidden');

    const selectKelas = document.getElementById('addMkKelasId');
    if (selectKelas) {
        selectKelas.innerHTML = '<option value="">-- Pilih Mata Kuliah Terlebih Dahulu --</option>';
        selectKelas.disabled = true;
    }

    hideAddMkError();
    hideAddMkInfo();
}

// ============================================================================
// DATA LOADING
// ============================================================================

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
            kelas: mk.kelas?.map(k => ({
                ...k,
                kuota_terisi: k.kuota_terisi ?? 0
            })) || []
        }));

        populateMataKuliahSelect();

    } catch (error) {
        console.error('Error loading mata kuliah:', error);
        showAddMkError('Gagal memuat data: ' + error.message);
    }
}

async function checkPreviousEnrollment(mataKuliahId) {
    const query = `query($mahasiswaId: ID!, $mataKuliahId: ID!) {
        nilaiByMahasiswaAndMataKuliah(
            mahasiswa_id: $mahasiswaId, 
            mata_kuliah_id: $mataKuliahId
        ) {
            id 
            nilai_huruf
            krsDetail {
                krs {
                    semester {
                        nama_semester
                    }
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
                variables: {
                    mahasiswaId: currentMahasiswaId,
                    mataKuliahId: mataKuliahId
                }
            })
        });

        const result = await response.json();
        
        if (result.errors) return null;

        const nilais = result.data?.nilaiByMahasiswaAndMataKuliah || [];
        
        if (nilais.length > 0) {
            const lastNilai = nilais[nilais.length - 1];
            return {
                id: lastNilai.id,
                nilai_huruf: lastNilai.nilai_huruf,
                semester: lastNilai.krsDetail?.krs?.semester
            };
        }
        
        return null;

    } catch (error) {
        console.error('Error checking enrollment:', error);
        return null;
    }
}

// ============================================================================
// POPULATE DROPDOWNS
// ============================================================================

function populateMataKuliahSelect() {
    const select = document.getElementById('addMkMataKuliahId');
    if (!select) return;

    select.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';

    if (!mahasiswaData) {
        showAddMkError('Data mahasiswa tidak tersedia');
        return;
    }

    const semesterMahasiswa = mahasiswaData.semester_saat_ini || 1;

    // Filter mata kuliah
    const filteredMk = availableMataKuliah.filter(mk => {
        // Cek semester rekomendasi
        if (mk.semester_rekomendasi && semesterMahasiswa < mk.semester_rekomendasi) {
            return false;
        }

        // Cek sudah diambil
        const sudahDiambil = krsDetailList.some(d => 
            d.mata_kuliah_id === mk.id
        );

        return !sudahDiambil && mk.kelas?.length > 0;
    });

    if (filteredMk.length === 0) {
        select.innerHTML = '<option value="">-- Tidak ada mata kuliah tersedia --</option>';
        showAddMkInfo('Semua mata kuliah sudah diambil atau tidak tersedia');
        return;
    }
    const grouped = {};
    filteredMk.forEach(mk => {
        const sem = mk.semester_rekomendasi || 0;
        if (!grouped[sem]) grouped[sem] = [];
        grouped[sem].push(mk);
    });
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

function populateKelasSelect(mkData) {
    const select = document.getElementById('addMkKelasId');
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

function onMataKuliahChange() {
    const select = document.getElementById('addMkMataKuliahId');
    const selectedOption = select?.options[select.selectedIndex];

    if (!selectedOption?.value) {
        document.getElementById('addMkKelasId').disabled = true;
        document.getElementById('addMkInfoSection')?.classList.add('hidden');
        return;
    }

    const mkData = JSON.parse(selectedOption.dataset.mk);

    // Tampilkan info mata kuliah
    const kodeEl = document.getElementById('addMkKode');
    const sksEl = document.getElementById('addMkSks');
    
    if (kodeEl) kodeEl.textContent = mkData.kode_mk;
    if (sksEl) sksEl.textContent = `${mkData.sks} SKS`;

    populateKelasSelect(mkData);
    document.getElementById('addMkInfoSection')?.classList.remove('hidden');
}

function onKelasChange() {
    const select = document.getElementById('addMkKelasId');
    
    if (!select?.value) {
        clearKelasInfo();
        return;
    }

    const kelasData = JSON.parse(select.options[select.selectedIndex].dataset.kelas);
    selectedKelasData = kelasData;
    
    displayKelasInfo(kelasData);
    validateKelasSelection(kelasData);
}

function displayKelasInfo(kelasData) {
    // Dosen
    const dosenEl = document.getElementById('addMkDosen');
    if (dosenEl) {
        dosenEl.textContent = kelasData.dosen?.nama_lengkap || 'Belum ditentukan';
    }

    // Jadwal
    const jadwalEl = document.getElementById('addMkJadwal');
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
    const kuotaEl = document.getElementById('addMkKuota');
    if (kuotaEl) {
        const sisaKuota = (kelasData.kapasitas ?? 0) - (kelasData.kuota_terisi ?? 0);
        kuotaEl.textContent = `${sisaKuota} / ${kelasData.kapasitas ?? 0}`;
        kuotaEl.className = sisaKuota > 5 ? 'font-semibold text-green-600' :
            sisaKuota > 0 ? 'font-semibold text-yellow-600' : 
            'font-semibold text-red-600';
    }
}

function clearKelasInfo() {
    selectedKelasData = null;
    
    const dosenEl = document.getElementById('addMkDosen');
    const jadwalEl = document.getElementById('addMkJadwal');
    const kuotaEl = document.getElementById('addMkKuota');

    if (dosenEl) dosenEl.textContent = '-';
    if (jadwalEl) jadwalEl.textContent = '-';
    if (kuotaEl) kuotaEl.textContent = '-';

    hideAddMkError();
}

// ============================================================================
// VALIDATION
// ============================================================================

async function validateKelasSelection(kelasData) {
    hideAddMkError();

    // Cek konflik jadwal
    if (checkJadwalConflict(kelasData)) {
        showAddMkError('⚠️ KONFLIK JADWAL: Waktu kuliah bentrok dengan mata kuliah lain!');
        return false;
    }

    // Cek total SKS
    const currentTotalSks = krsDetailList.reduce((sum, d) => sum + (d.sks || 0), 0);
    const newTotalSks = currentTotalSks + kelasData.mkData.sks;
    const ipk = mahasiswaData?.ipk || 0;
    const maxSks = await getMaxSks(ipk);

    if (newTotalSks > maxSks) {
        showAddMkError(`⚠️ Total SKS akan menjadi ${newTotalSks}, maksimal ${maxSks} SKS (IPK: ${ipk.toFixed(2)})`);
        return false;
    }

    showAddMkInfo(`✓ Total SKS setelah ditambah: ${newTotalSks}/${maxSks} SKS`);
    return true;
}

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

async function submitAddMk(event) {
    event?.preventDefault();

    const submitBtn = document.getElementById('btnSubmitAddMk');
    if (submitBtn) submitBtn.disabled = true;

    try {
        if (!selectedKelasData) {
            throw new Error('Silakan pilih mata kuliah dan kelas');
        }

        const isValid = await validateKelasSelection(selectedKelasData);
        if (!isValid) {
            throw new Error('Validasi gagal');
        }

        // Cek riwayat nilai
        const previousNilai = await checkPreviousEnrollment(selectedKelasData.mkData.id);
        const statusAmbil = previousNilai && ['D', 'E'].includes(previousNilai.nilai_huruf)
            ? 'MENGULANG' : 'BARU';

        if (statusAmbil === 'MENGULANG') {
            showAddMkInfo(`ℹ️ Status: MENGULANG (Nilai sebelumnya: ${previousNilai.nilai_huruf})`);
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
        await updateKuotaKelas(selectedKelasData.id, 1);

        // Reload KRS
        await loadCurrentKrs();

        closeAddMkModal();
        showNotification(`${selectedKelasData.mkData.nama_mk} berhasil ditambahkan!`, 'success');

    } catch (error) {
        console.error('Error:', error);
        showAddMkError('Gagal menambah: ' + error.message);
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
}

// ============================================================================
// UI HELPERS
// ============================================================================

function showAddMkError(msg) {
    const el = document.getElementById('addMkError');
    if (el) {
        el.textContent = msg;
        el.classList.remove('hidden');
    }
    hideAddMkInfo();
}

function hideAddMkError() {
    const el = document.getElementById('addMkError');
    if (el) {
        el.classList.add('hidden');
        el.textContent = '';
    }
}

function showAddMkInfo(msg) {
    const el = document.getElementById('addMkInfo');
    if (el) {
        el.textContent = msg;
        el.classList.remove('hidden');
    }
}

function hideAddMkInfo() {
    const el = document.getElementById('addMkInfo');
    if (el) {
        el.classList.add('hidden');
        el.textContent = '';
    }
}

// ============================================================================
// EDIT MATA KULIAH
// ============================================================================

let editMkDetailData = null;
let editAvailableKelas = [];

async function openEditMkModal(detailId) {
    const modal = document.getElementById('modalEditMk');
    if (!modal) {
        console.error('Modal edit tidak ditemukan');
        return;
    }

    // Cari data detail
    const detail = krsDetailList.find(d => d.id === detailId);
    if (!detail) {
        showNotification('Data tidak ditemukan', 'error');
        return;
    }

    editMkDetailData = detail;
    
    await populateEditMkForm(detail);
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditMkModal() {
    document.getElementById('modalEditMk')?.classList.add('hidden');
    document.body.style.overflow = 'auto';
    editMkDetailData = null;
    editAvailableKelas = [];
}

async function populateEditMkForm(detail) {
    // Info mata kuliah (read-only)
    const namaMkEl = document.getElementById('editMkNamaMk');
    const kodeMkEl = document.getElementById('editMkKodeMk');
    const sksMkEl = document.getElementById('editMkSksMk');
    
    if (namaMkEl) namaMkEl.textContent = detail.mataKuliah?.nama_mk || '-';
    if (kodeMkEl) kodeMkEl.textContent = detail.mataKuliah?.kode_mk || '-';
    if (sksMkEl) sksMkEl.textContent = `${detail.sks || 0} SKS`;
    
    // Current kelas info
    const currentKelasEl = document.getElementById('editMkCurrentKelas');
    const currentDosenEl = document.getElementById('editMkCurrentDosen');
    const currentJadwalEl = document.getElementById('editMkCurrentJadwal');
    
    if (currentKelasEl) currentKelasEl.textContent = detail.kelas?.nama_kelas || '-';
    if (currentDosenEl) currentDosenEl.textContent = detail.kelas?.dosen?.nama_lengkap || '-';
    
    if (currentJadwalEl) {
        if (detail.kelas?.jadwalKuliah?.length > 0) {
            const jadwal = detail.kelas.jadwalKuliah.map(j =>
                `${j.hari}, ${j.jam_mulai}-${j.jam_selesai}`
            ).join(', ');
            currentJadwalEl.textContent = jadwal;
        } else {
            currentJadwalEl.textContent = 'Belum ada jadwal';
        }
    }
    
    // Status ambil
    const statusSelect = document.getElementById('editMkStatusAmbil');
    if (statusSelect) {
        statusSelect.value = detail.status_ambil || 'BARU';
    }
    
    // Load kelas lain
    await loadEditAvailableKelas(detail.mata_kuliah_id, detail.kelas_id);
}

async function loadEditAvailableKelas(mataKuliahId, currentKelasId) {
    const query = `query($id: ID!) {
        matakuliah(id: $id) {
            id
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
            body: JSON.stringify({ query, variables: { id: mataKuliahId } })
        });

        const result = await response.json();
        if (result.errors) throw new Error(result.errors[0].message);

        const allKelas = result.data.matakuliah?.kelas || [];

        // Filter kelas saat ini & normalize kuota
        editAvailableKelas = allKelas
            .filter(k => k.id !== currentKelasId)
            .map(k => ({ ...k, kuota_terisi: k.kuota_terisi ?? 0 }));

        populateEditMkKelasSelect();

    } catch (error) {
        console.error('Error loading kelas:', error);
        showEditMkError('Gagal memuat kelas: ' + error.message);
    }
}

function populateEditMkKelasSelect() {
    const select = document.getElementById('editMkKelasId');
    if (!select) return;

    select.innerHTML = '<option value="">-- Tetap di kelas saat ini --</option>';

    if (editAvailableKelas.length === 0) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = '-- Tidak ada kelas lain --';
        option.disabled = true;
        select.appendChild(option);
        return;
    }

    editAvailableKelas.forEach(kelas => {
        const sisaKuota = (kelas.kapasitas ?? 0) - (kelas.kuota_terisi ?? 0);
        const isFull = sisaKuota <= 0;

        const option = document.createElement('option');
        option.value = kelas.id;
        option.textContent = `${kelas.nama_kelas} - ${kelas.dosen?.nama_lengkap || '-'} - Kuota: ${sisaKuota}/${kelas.kapasitas}${isFull ? ' (PENUH)' : ''}`;
        option.dataset.kelas = JSON.stringify(kelas);
        option.disabled = isFull;

        select.appendChild(option);
    });
}

function onEditMkKelasChange() {
    const select = document.getElementById('editMkKelasId');
    if (!select?.value) {
        hideEditMkKelasInfo();
        return;
    }

    const kelasData = JSON.parse(select.options[select.selectedIndex].dataset.kelas);
    
    displayEditMkKelasInfo(kelasData);
    validateEditMkKelasSelection(kelasData);
}

function displayEditMkKelasInfo(kelasData) {
    const infoSection = document.getElementById('editMkNewKelasInfo');
    if (!infoSection) return;

    // Dosen
    const dosenEl = document.getElementById('editMkNewDosen');
    if (dosenEl) dosenEl.textContent = kelasData.dosen?.nama_lengkap || '-';
    
    // Jadwal
    const jadwalEl = document.getElementById('editMkNewJadwal');
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
    const kuotaEl = document.getElementById('editMkNewKuota');
    if (kuotaEl) {
        const sisaKuota = (kelasData.kapasitas ?? 0) - (kelasData.kuota_terisi ?? 0);
        kuotaEl.textContent = `${sisaKuota} / ${kelasData.kapasitas ?? 0}`;
        kuotaEl.className = sisaKuota > 5 ? 'font-semibold text-green-600' :
            sisaKuota > 0 ? 'font-semibold text-yellow-600' : 
            'font-semibold text-red-600';
    }
    
    infoSection.classList.remove('hidden');
}

function hideEditMkKelasInfo() {
    const infoSection = document.getElementById('editMkNewKelasInfo');
    if (infoSection) infoSection.classList.add('hidden');
    hideEditMkError();
}

function validateEditMkKelasSelection(kelasData) {
    hideEditMkError();
    
    // Cek konflik jadwal (exclude mata kuliah yang sedang diedit)
    if (checkEditMkJadwalConflict(kelasData)) {
        showEditMkError('⚠️ KONFLIK JADWAL: Waktu kuliah bentrok dengan mata kuliah lain!');
        return false;
    }
    
    showEditMkInfo('✓ Tidak ada konflik jadwal');
    return true;
}

function checkEditMkJadwalConflict(kelasData) {
    if (!kelasData.jadwalKuliah?.length) return false;
    
    for (const detail of krsDetailList) {
        // Skip mata kuliah yang sedang diedit
        if (detail.id === editMkDetailData.id) continue;
        
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

async function submitEditMk(event) {
    event?.preventDefault();
    
    const submitBtn = document.getElementById('btnSubmitEditMk');
    if (submitBtn) submitBtn.disabled = true;

    try {
        if (!editMkDetailData) throw new Error('Data tidak ditemukan');

        const kelasSelect = document.getElementById('editMkKelasId');
        const statusSelect = document.getElementById('editMkStatusAmbil');
        
        const newKelasId = kelasSelect?.value;
        const newStatus = statusSelect?.value;
        
        // Cek perubahan
        const hasKelasChange = newKelasId && newKelasId !== editMkDetailData.kelas_id;
        const hasStatusChange = newStatus !== editMkDetailData.status_ambil;
        
        if (!hasKelasChange && !hasStatusChange) {
            throw new Error('Tidak ada perubahan');
        }

        // Validasi jika ganti kelas
        if (hasKelasChange) {
            const selectedOption = kelasSelect.options[kelasSelect.selectedIndex];
            const kelasData = JSON.parse(selectedOption.dataset.kelas);
            
            if (!validateEditMkKelasSelection(kelasData)) {
                throw new Error('Validasi gagal');
            }
        }

        // Mutation
        const mutation = `mutation($id: ID!, $input: UpdateKrsDetailInput!) {
            updateKrsDetail(id: $id, input: $input) {
                id kelas_id status_ambil
            }
        }`;

        const input = {};
        if (hasKelasChange) input.kelas_id = parseInt(newKelasId);
        if (hasStatusChange) input.status_ambil = newStatus;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: {
                    id: parseInt(editMkDetailData.id),
                    input: input
                }
            })
        });

        const result = await response.json();
        if (result.errors) throw new Error(result.errors[0].message);

        // Update kuota jika ganti kelas
        if (hasKelasChange) {
            await updateKuotaKelas(editMkDetailData.kelas_id, -1); // Kurangi kelas lama
            await updateKuotaKelas(newKelasId, 1); // Tambah kelas baru
        }

        // Reload data
        await loadCurrentKrs();
        closeEditMkModal();
        
        // Success message
        let message = 'Data berhasil diupdate!';
        if (hasKelasChange) message = 'Kelas berhasil dipindah!';
        if (hasStatusChange && !hasKelasChange) message = 'Status berhasil diubah!';
        
        showNotification(message, 'success');
        
    } catch (error) {
        console.error('Error:', error);
        showEditMkError('Gagal update: ' + error.message);
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
}

// UI Helpers for Edit Modal
function showEditMkError(msg) {
    const el = document.getElementById('editMkError');
    if (el) {
        el.textContent = msg;
        el.classList.remove('hidden');
    }
    hideEditMkInfo();
}

function hideEditMkError() {
    const el = document.getElementById('editMkError');
    if (el) {
        el.classList.add('hidden');
        el.textContent = '';
    }
}

function showEditMkInfo(msg) {
    const el = document.getElementById('editMkInfo');
    if (el) {
        el.textContent = msg;
        el.classList.remove('hidden');
    }
}

function hideEditMkInfo() {
    const el = document.getElementById('editMkInfo');
    if (el) {
        el.classList.add('hidden');
        el.textContent = '';
    }
}