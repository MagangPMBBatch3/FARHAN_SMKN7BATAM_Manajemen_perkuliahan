// ============================================================================
// STATE MANAGEMENT
// ============================================================================

let editKrsDetailData = null;
let editAvailableKelas = [];

// ============================================================================
// MODAL MANAGEMENT
// ============================================================================

/**
 * Buka modal edit mata kuliah
 */
async function openEditKrsDetailModal(detailId) {
    const modal = document.getElementById('modalEditKrsDetail');
    if (!modal) {
        console.error('Modal edit tidak ditemukan');
        return;
    }

    // Cari data detail
    const detail = krsDetailList.find(d => d.id === detailId);
    if (!detail) {
        alert('Data tidak ditemukan');
        return;
    }

    editKrsDetailData = detail;
    
    await populateEditForm(detail);
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

/**
 * Tutup modal edit
 */
function closeEditKrsDetailModal() {
    document.getElementById('modalEditKrsDetail')?.classList.add('hidden');
    document.body.style.overflow = 'auto';
    editKrsDetailData = null;
    editAvailableKelas = [];
}

// ============================================================================
// POPULATE FORM
// ============================================================================

/**
 * Populate form edit dengan data detail
 */
async function populateEditForm(detail) {
    // Info mata kuliah (read-only)
    const namaMkEl = document.getElementById('editKrsDetailNamaMk');
    const kodeMkEl = document.getElementById('editKrsDetailKodeMk');
    const sksMkEl = document.getElementById('editKrsDetailSksMk');
    
    if (namaMkEl) namaMkEl.textContent = detail.mataKuliah?.nama_mk || '-';
    if (kodeMkEl) kodeMkEl.textContent = detail.mataKuliah?.kode_mk || '-';
    if (sksMkEl) sksMkEl.textContent = `${detail.sks || 0} SKS`;
    
    // Current kelas info
    const currentKelasEl = document.getElementById('editKrsDetailCurrentKelas');
    const currentDosenEl = document.getElementById('editKrsDetailCurrentDosen');
    
    if (currentKelasEl) currentKelasEl.textContent = detail.kelas?.nama_kelas || '-';
    if (currentDosenEl) currentDosenEl.textContent = detail.kelas?.dosen?.nama_lengkap || '-';
    
    // Status ambil
    const statusSelect = document.getElementById('editKrsDetailStatusAmbil');
    if (statusSelect) {
        statusSelect.value = detail.status_ambil || 'BARU';
    }
    
    // Load kelas lain
    await loadEditAvailableKelas(detail.mata_kuliah_id, detail.kelas_id);
}

// ============================================================================
// DATA LOADING
// ============================================================================

/**
 * Mengambil kelas lain untuk mata kuliah
 */
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

        populateEditKelasSelect();

    } catch (error) {
        console.error('Error loading kelas:', error);
        showEditKrsDetailError('Gagal memuat kelas: ' + error.message);
    }
}

/**
 * Populate dropdown kelas untuk edit
 */
function populateEditKelasSelect() {
    const select = document.getElementById('editKrsDetailKelasId');
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

// ============================================================================
// EVENT HANDLERS
// ============================================================================

/**
 * Handler ketika kelas baru dipilih
 */
function onEditKelasChange() {
    const select = document.getElementById('editKrsDetailKelasId');
    if (!select?.value) {
        hideEditKelasInfo();
        return;
    }

    const kelasData = JSON.parse(select.options[select.selectedIndex].dataset.kelas);
    
    displayEditKelasInfo(kelasData);
    validateEditKelasSelection(kelasData);
}

/**
 * Tampilkan info kelas baru
 */
function displayEditKelasInfo(kelasData) {
    const infoSection = document.getElementById('editKrsDetailNewKelasInfo');
    if (!infoSection) return;

    // Dosen
    const dosenEl = document.getElementById('editKrsDetailNewDosen');
    if (dosenEl) dosenEl.textContent = kelasData.dosen?.nama_lengkap || '-';
    
    // Jadwal
    const jadwalEl = document.getElementById('editKrsDetailNewJadwal');
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
    const kuotaEl = document.getElementById('editKrsDetailNewKuota');
    if (kuotaEl) {
        const sisaKuota = (kelasData.kapasitas ?? 0) - (kelasData.kuota_terisi ?? 0);
        kuotaEl.textContent = `${sisaKuota} / ${kelasData.kapasitas ?? 0}`;
    }
    
    infoSection.classList.remove('hidden');
}

/**
 * Sembunyikan info kelas baru
 */
function hideEditKelasInfo() {
    const infoSection = document.getElementById('editKrsDetailNewKelasInfo');
    if (infoSection) infoSection.classList.add('hidden');
    hideEditKrsDetailError();
}

// ============================================================================
// VALIDATION
// ============================================================================

/**
 * Validasi kelas baru yang dipilih
 */
function validateEditKelasSelection(kelasData) {
    hideEditKrsDetailError();
    
    // Cek konflik jadwal (exclude mata kuliah yang sedang diedit)
    if (checkEditJadwalConflict(kelasData)) {
        showEditKrsDetailError('⚠️ KONFLIK JADWAL: Waktu kuliah bentrok!');
        return false;
    }
    
    showEditKrsDetailInfo('✓ Tidak ada konflik jadwal');
    return true;
}

/**
 * Cek konflik jadwal untuk edit (skip current detail)
 */
function checkEditJadwalConflict(kelasData) {
    if (!kelasData.jadwalKuliah?.length) return false;
    
    for (const detail of krsDetailList) {
        // Skip mata kuliah yang sedang diedit
        if (detail.id === editKrsDetailData.id) continue;
        
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
 * Submit form edit mata kuliah
 */
async function submitEditKrsDetail(event) {
    event?.preventDefault();
    
    const submitBtn = document.getElementById('btnSubmitEditKrsDetail');
    if (submitBtn) submitBtn.disabled = true;

    try {
        if (!editKrsDetailData) throw new Error('Data tidak ditemukan');

        const kelasSelect = document.getElementById('editKrsDetailKelasId');
        const statusSelect = document.getElementById('editKrsDetailStatusAmbil');
        
        const newKelasId = kelasSelect?.value;
        const newStatus = statusSelect?.value;
        
        // Cek perubahan
        const hasKelasChange = newKelasId && newKelasId !== editKrsDetailData.kelas_id;
        const hasStatusChange = newStatus !== editKrsDetailData.status_ambil;
        
        if (!hasKelasChange && !hasStatusChange) {
            throw new Error('Tidak ada perubahan');
        }

        // Validasi jika ganti kelas
        if (hasKelasChange) {
            const selectedOption = kelasSelect.options[kelasSelect.selectedIndex];
            const kelasData = JSON.parse(selectedOption.dataset.kelas);
            
            if (!validateEditKelasSelection(kelasData)) {
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
                    id: parseInt(editKrsDetailData.id),
                    input: input
                }
            })
        });

        const result = await response.json();
        if (result.errors) throw new Error(result.errors[0].message);

        // Update kuota jika ganti kelas
        if (hasKelasChange) {
            await updateKuotaKelas(editKrsDetailData.kelas_id, -1); // Kurangi kelas lama
            await updateKuotaKelas(newKelasId, 1); // Tambah kelas baru
        }

        // Reload data
        await loadKrsDetail();
        closeEditKrsDetailModal();
        
        // Success message
        let message = 'Data berhasil diupdate!';
        if (hasKelasChange) message = 'Kelas berhasil dipindah!';
        if (hasStatusChange && !hasKelasChange) message = 'Status berhasil diubah!';
        
        showSuccessNotification(message);
        
    } catch (error) {
        console.error('Error:', error);
        showEditKrsDetailError('Gagal update: ' + error.message);
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
}

// ============================================================================
// UI HELPERS
// ============================================================================

function showEditKrsDetailError(msg) {
    const el = document.getElementById('editKrsDetailError');
    if (el) {
        el.textContent = msg;
        el.classList.remove('hidden');
    }
    hideEditKrsDetailInfo();
}

function hideEditKrsDetailError() {
    const el = document.getElementById('editKrsDetailError');
    if (el) {
        el.classList.add('hidden');
        el.textContent = '';
    }
}

function showEditKrsDetailInfo(msg) {
    const el = document.getElementById('editKrsDetailInfo');
    if (el) {
        el.textContent = msg;
        el.classList.remove('hidden');
    }
}

function hideEditKrsDetailInfo() {
    const el = document.getElementById('editKrsDetailInfo');
    if (el) {
        el.classList.add('hidden');
        el.textContent = '';
    }
}