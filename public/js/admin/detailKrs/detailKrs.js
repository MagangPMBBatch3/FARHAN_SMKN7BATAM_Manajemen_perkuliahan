// detailKrs-edit.js - Sistem Edit Mata Kuliah KRS

let editKrsDetailData = null;
let editAvailableKelas = [];

// Buka modal edit
async function openEditKrsDetailModal(detailId) {
    const modal = document.getElementById('modalEditKrsDetail');
    if (!modal) {
        console.error('Modal edit KRS Detail tidak ditemukan');
        return;
    }

    // Cari data detail
    const detail = krsDetailList.find(d => d.id === detailId);
    if (!detail) {
        alert('Data tidak ditemukan');
        return;
    }

    editKrsDetailData = detail;
    
    // Populate form
    await populateEditForm(detail);
    
    // Tampilkan modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Tutup modal edit
function closeEditKrsDetailModal() {
    const modal = document.getElementById('modalEditKrsDetail');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    editKrsDetailData = null;
    editAvailableKelas = [];
}

// Populate form edit
async function populateEditForm(detail) {
    // Info mata kuliah (read-only)
    document.getElementById('editKrsDetailNamaMk').textContent = detail.mataKuliah?.nama_mk || '-';
    document.getElementById('editKrsDetailKodeMk').textContent = detail.mataKuliah?.kode_mk || '-';
    document.getElementById('editKrsDetailSksMk').textContent = `${detail.sks || 0} SKS`;
    
    // Current kelas info
    document.getElementById('editKrsDetailCurrentKelas').textContent = detail.kelas?.nama_kelas || '-';
    document.getElementById('editKrsDetailCurrentDosen').textContent = detail.kelas?.dosen?.nama_lengkap || '-';
    
    // Status ambil
    const statusSelect = document.getElementById('editKrsDetailStatusAmbil');
    if (statusSelect) {
        statusSelect.value = detail.status_ambil || 'BARU';
    }
    
    // Load kelas lain untuk mata kuliah ini
    await loadEditAvailableKelas(detail.mata_kuliah_id, detail.kelas_id);
}

// Load kelas lain yang tersedia
async function loadEditAvailableKelas(mataKuliahId, currentKelasId) {
    const query = `
    query($id: ID!) {
        matakuliah(id: $id) {
            id
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
            body: JSON.stringify({ 
                query,
                variables: { id: mataKuliahId }
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }

        const allKelas = result.data.matakuliah?.kelas || [];
        
        // Normalize data: set default kuota_terisi = 0
        editAvailableKelas = allKelas
            .filter(k => k.id !== currentKelasId)
            .map(k => ({
                ...k,
                kuota_terisi: k.kuota_terisi ?? 0
            }));
        
        populateEditKelasSelect();
        
    } catch (error) {
        console.error('Error loading kelas:', error);
        showEditKrsDetailError('Gagal memuat data kelas: ' + error.message);
    }
}

// Populate select kelas untuk edit
function populateEditKelasSelect() {
    const select = document.getElementById('editKrsDetailKelasId');
    if (!select) return;

    select.innerHTML = '<option value="">-- Tetap di kelas saat ini --</option>';

    if (editAvailableKelas.length === 0) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = '-- Tidak ada kelas lain tersedia --';
        option.disabled = true;
        select.appendChild(option);
        return;
    }

    editAvailableKelas.forEach(kelas => {
        const kuotaTerisi = kelas.kuota_terisi ?? 0;
        const kapasitas = kelas.kapasitas ?? 0;
        const sisaKuota = kapasitas - kuotaTerisi;
        const isFull = sisaKuota <= 0;
        
        const option = document.createElement('option');
        option.value = kelas.id;
        option.textContent = `${kelas.nama_kelas} - Dosen: ${kelas.dosen?.nama_lengkap || '-'} - Kuota: ${sisaKuota}/${kapasitas}${isFull ? ' (PENUH)' : ''}`;
        option.dataset.kelas = JSON.stringify(kelas);
        option.disabled = isFull;
        
        select.appendChild(option);
    });
}

// Handle perubahan kelas pada edit
function onEditKelasChange() {
    const select = document.getElementById('editKrsDetailKelasId');
    if (!select || !select.value) {
        hideEditKelasInfo();
        return;
    }

    const selectedOption = select.options[select.selectedIndex];
    const kelasData = JSON.parse(selectedOption.dataset.kelas);
    
    displayEditKelasInfo(kelasData);
    validateEditKelasSelection(kelasData);
}

// Tampilkan info kelas baru
function displayEditKelasInfo(kelasData) {
    const infoSection = document.getElementById('editKrsDetailNewKelasInfo');
    if (!infoSection) return;

    // Dosen
    document.getElementById('editKrsDetailNewDosen').textContent = kelasData.dosen?.nama_lengkap || '-';
    
    // Jadwal
    if (kelasData.jadwalKuliah && kelasData.jadwalKuliah.length > 0) {
        const jadwalTexts = kelasData.jadwalKuliah.map(j => {
            const ruangan = j.ruangan?.nama_ruangan || '-';
            return `${j.hari}, ${j.jam_mulai}-${j.jam_selesai} (${ruangan})`;
        });
        document.getElementById('editKrsDetailNewJadwal').innerHTML = jadwalTexts.join('<br>');
    } else {
        document.getElementById('editKrsDetailNewJadwal').textContent = 'Belum ada jadwal';
    }
    
    // Kuota
    const kuotaTerisi = kelasData.kuota_terisi ?? 0;
    const kapasitas = kelasData.kapasitas ?? 0;
    const sisaKuota = kapasitas - kuotaTerisi;
    document.getElementById('editKrsDetailNewKuota').textContent = `${sisaKuota} / ${kapasitas}`;
    
    infoSection.classList.remove('hidden');
}

// Hide info kelas baru
function hideEditKelasInfo() {
    const infoSection = document.getElementById('editKrsDetailNewKelasInfo');
    if (infoSection) {
        infoSection.classList.add('hidden');
    }
    hideEditKrsDetailError();
}

// Validasi pemilihan kelas baru
function validateEditKelasSelection(kelasData) {
    hideEditKrsDetailError();
    
    // Cek konflik jadwal (exclude mata kuliah yang sedang diedit)
    const hasConflict = checkEditJadwalConflict(kelasData);
    if (hasConflict) {
        showEditKrsDetailError('⚠️ KONFLIK JADWAL: Waktu kuliah bentrok dengan mata kuliah lain!');
        return false;
    }
    
    showEditKrsDetailInfo('✓ Tidak ada konflik jadwal');
    return true;
}

// Cek konflik jadwal untuk edit (exclude current mata kuliah)
function checkEditJadwalConflict(kelasData) {
    if (!kelasData.jadwalKuliah || kelasData.jadwalKuliah.length === 0) {
        return false;
    }
    
    for (const detail of krsDetailList) {
        // Skip mata kuliah yang sedang diedit
        if (detail.id === editKrsDetailData.id) continue;
        
        if (!detail.kelas?.jadwalKuliah) continue;
        
        for (const jadwalBaru of kelasData.jadwalKuliah) {
            for (const jadwalLama of detail.kelas.jadwalKuliah) {
                if (jadwalBaru.hari === jadwalLama.hari) {
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

// Submit edit KRS Detail
async function submitEditKrsDetail(event) {
    if (event) event.preventDefault();
    
    const submitBtn = document.getElementById('btnSubmitEditKrsDetail');
    if (submitBtn) submitBtn.disabled = true;

    try {
        if (!editKrsDetailData) {
            throw new Error('Data tidak ditemukan');
        }

        const kelasSelect = document.getElementById('editKrsDetailKelasId');
        const statusSelect = document.getElementById('editKrsDetailStatusAmbil');
        
        const newKelasId = kelasSelect.value;
        const newStatus = statusSelect.value;
        
        // Cek apakah ada perubahan
        const hasKelasChange = newKelasId && newKelasId !== editKrsDetailData.kelas_id;
        const hasStatusChange = newStatus !== editKrsDetailData.status_ambil;
        
        if (!hasKelasChange && !hasStatusChange) {
            throw new Error('Tidak ada perubahan yang dilakukan');
        }

        // Validasi jika ganti kelas
        if (hasKelasChange) {
            const selectedOption = kelasSelect.options[kelasSelect.selectedIndex];
            const kelasData = JSON.parse(selectedOption.dataset.kelas);
            
            if (!validateEditKelasSelection(kelasData)) {
                throw new Error('Validasi gagal. Periksa pesan error di atas.');
            }
        }

        // Mutation GraphQL
        const mutation = `
        mutation($id: ID!, $input: UpdateKrsDetailInput!) {
            updateKrsDetail(id: $id, input: $input) {
                id
                kelas_id
                status_ambil
                kelas {
                    id
                    nama_kelas
                    dosen {
                        id
                        nama_lengkap
                    }
                }
            }
        }`;

        const input = {};
        if (hasKelasChange) input.kelas_id = parseInt(newKelasId);  // Convert to Int
        if (hasStatusChange) input.status_ambil = newStatus;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: {
                    id: parseInt(editKrsDetailData.id),  // Convert to Int
                    input: input
                }
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }

        // Update kuota jika ganti kelas
        if (hasKelasChange) {
            // Kurangi kuota kelas lama
            await updateKuotaKelasById(editKrsDetailData.kelas_id, -1);
            // Tambah kuota kelas baru
            await updateKuotaKelasById(newKelasId, 1);
        }

        // Reload data KRS
        await loadKrsDetail();
        
        // Close modal
        closeEditKrsDetailModal();
        
        // Show success message
        let message = 'Data mata kuliah berhasil diupdate!';
        if (hasKelasChange) message = 'Kelas berhasil dipindah!';
        if (hasStatusChange && !hasKelasChange) message = 'Status pengambilan berhasil diubah!';
        
        showSuccessNotification(message);
        
    } catch (error) {
        console.error('Error updating KRS Detail:', error);
        showEditKrsDetailError('Gagal mengupdate data: ' + error.message);
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
}

// Update kuota by kelas ID
async function updateKuotaKelasById(kelasId, increment) {
    const query = `
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
        // Get current kuota
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { id: parseInt(kelasId) }  // Convert to Int
            })
        });

        const result = await response.json();
        const currentKuota = result.data?.kelas?.kuota_terisi ?? 0;
        const newKuota = Math.max(0, currentKuota + increment);

        // Update kuota
        await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: {
                    id: parseInt(kelasId),  // Convert to Int
                    input: { kuota_terisi: newKuota }
                }
            })
        });
    } catch (error) {
        console.error('Error updating kuota:', error);
    }
}

// Show/Hide error & info untuk edit
function showEditKrsDetailError(message) {
    const errorEl = document.getElementById('editKrsDetailError');
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }
    hideEditKrsDetailInfo();
}

function hideEditKrsDetailError() {
    const errorEl = document.getElementById('editKrsDetailError');
    if (errorEl) {
        errorEl.classList.add('hidden');
        errorEl.textContent = '';
    }
}

function showEditKrsDetailInfo(message) {
    const infoEl = document.getElementById('editKrsDetailInfo');
    if (infoEl) {
        infoEl.textContent = message;
        infoEl.classList.remove('hidden');
    }
}

function hideEditKrsDetailInfo() {
    const infoEl = document.getElementById('editKrsDetailInfo');
    if (infoEl) {
        infoEl.classList.add('hidden');
        infoEl.textContent = '';
    }
}