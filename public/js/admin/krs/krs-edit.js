// ==================== OPTIMIZED EDIT MODAL ====================
// File: krs-edit.js

let editMahasiswaData = [];
let currentEditingKrsId = null;

// ==================== LOAD OPTIONS FOR EDIT ====================

async function loadFakultasOptionsEdit() {
    const select = document.getElementById('editFakultasId');
    if (!select) return;

    // Gunakan cache jika ada
    if (dataCache.fakultas && isCacheValid('fakultas')) {
        renderFakultasOptions(dataCache.fakultas, select);
        return;
    }

    select.innerHTML = getSelectLoadingOption();

    try {
        const query = `
        query {
            allFakultas {
                id
                nama_fakultas
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const fakultasList = result.data.allFakultas || [];

        dataCache.fakultas = fakultasList;
        dataCache.lastFetch.fakultas = Date.now();

        renderFakultasOptions(fakultasList, select);

    } catch (error) {
        console.error('Error loading fakultas:', error);
        select.innerHTML = '<option value="">Error loading data</option>';
    }
}

async function loadJurusanByFakultasEdit(fakultasId) {
    const select = document.getElementById('editJurusanId');
    const mahasiswaSelect = document.getElementById('editMahasiswaId');
    const searchInput = document.getElementById('editMahasiswaSearch');
    
    if (!fakultasId) {
        resetJurusanAndMahasiswaEdit(select, mahasiswaSelect, searchInput);
        return;
    }

    select.disabled = true;
    select.innerHTML = getSelectLoadingOption();

    try {
        const query = `
        query($fakultasId: ID!) {
            jurusanByFakultas(fakultas_id: $fakultasId) {
                id
                nama_jurusan
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { fakultasId: parseInt(fakultasId) }
            })
        });

        const result = await response.json();
        const jurusanList = result.data.jurusanByFakultas || [];

        if (jurusanList.length === 0) {
            select.innerHTML = '<option value="">Tidak ada jurusan tersedia</option>';
            select.disabled = true;
            return;
        }

        select.innerHTML = '<option value="">Pilih Jurusan</option>';
        jurusanList.forEach(jurusan => {
            select.innerHTML += `<option value="${jurusan.id}">${jurusan.nama_jurusan}</option>`;
        });
        select.disabled = false;

    } catch (error) {
        console.error('Error loading jurusan:', error);
        select.innerHTML = '<option value="">Error loading data</option>';
    }
}

function resetJurusanAndMahasiswaEdit(select, mahasiswaSelect, searchInput) {
    select.disabled = true;
    select.innerHTML = '<option value="">Pilih fakultas terlebih dahulu</option>';
    
    mahasiswaSelect.disabled = true;
    mahasiswaSelect.innerHTML = '<option value="">Pilih jurusan terlebih dahulu</option>';
    searchInput.disabled = true;
    searchInput.value = '';
    editMahasiswaData = [];
}

async function loadMahasiswaByJurusanEdit(jurusanId) {
    const select = document.getElementById('editMahasiswaId');
    const searchInput = document.getElementById('editMahasiswaSearch');
    
    if (!jurusanId) {
        select.disabled = true;
        select.innerHTML = '<option value="">Pilih jurusan terlebih dahulu</option>';
        searchInput.disabled = true;
        searchInput.value = '';
        editMahasiswaData = [];
        return;
    }

    select.disabled = true;
    select.innerHTML = getSelectLoadingOption();
    searchInput.disabled = true;

    try {
        const query = `
        query($jurusanId: ID!) {
            mahasiswaByJurusan(jurusan_id: $jurusanId) {
                id
                nim
                nama_lengkap
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { jurusanId: parseInt(jurusanId) }
            })
        });

        const result = await response.json();
        const mahasiswaList = result.data.mahasiswaByJurusan || [];

        editMahasiswaData = mahasiswaList;

        if (mahasiswaList.length === 0) {
            select.innerHTML = '<option value="">Tidak ada mahasiswa tersedia</option>';
            select.disabled = true;
            searchInput.disabled = true;
            return;
        }

        renderMahasiswaOptionsEdit(mahasiswaList);
        select.disabled = false;
        searchInput.disabled = false;

    } catch (error) {
        console.error('Error loading mahasiswa:', error);
        select.innerHTML = '<option value="">Error loading data</option>';
    }
}

function renderMahasiswaOptionsEdit(mahasiswaList) {
    const select = document.getElementById('editMahasiswaId');
    select.innerHTML = '<option value="">Pilih Mahasiswa</option>';
    mahasiswaList.forEach(mhs => {
        select.innerHTML += `<option value="${mhs.id}">${mhs.nim} - ${mhs.nama_lengkap}</option>`;
    });
}

const searchMahasiswaEdit = debounce((searchTerm) => {
    if (!searchTerm || searchTerm.trim() === '') {
        renderMahasiswaOptionsEdit(editMahasiswaData);
        return;
    }

    const searchLower = searchTerm.toLowerCase();
    const filtered = editMahasiswaData.filter(mhs => 
        mhs.nim.toLowerCase().includes(searchLower) ||
        mhs.nama_lengkap.toLowerCase().includes(searchLower)
    );

    const select = document.getElementById('editMahasiswaId');
    if (filtered.length === 0) {
        select.innerHTML = '<option value="">Tidak ada hasil pencarian</option>';
    } else {
        renderMahasiswaOptionsEdit(filtered);
    }
}, 300);

async function loadSemesterOptionsEdit() {
    const select = document.getElementById('editSemesterId');
    if (!select) return;

    if (dataCache.semester && isCacheValid('semester')) {
        renderSemesterOptions(dataCache.semester, select);
        return;
    }

    select.innerHTML = getSelectLoadingOption();

    try {
        const query = `
        query {
            allSemester {
                id
                nama_semester
                tahun_ajaran
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const semesterList = result.data.allSemester || [];

        dataCache.semester = semesterList;
        dataCache.lastFetch.semester = Date.now();

        renderSemesterOptions(semesterList, select);

    } catch (error) {
        console.error('Error loading semester:', error);
        select.innerHTML = '<option value="">Error loading data</option>';
    }
}

async function loadDosenOptionsEdit() {
    const select = document.getElementById('editDosenId');
    if (!select) return;

    if (dataCache.dosen && isCacheValid('dosen')) {
        renderDosenOptions(dataCache.dosen, select);
        return;
    }

    select.innerHTML = getSelectLoadingOption();

    try {
        const query = `
        query {
            allDosen {
                id
                nama_lengkap
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const dosenList = result.data.allDosen || [];

        dataCache.dosen = dosenList;
        dataCache.lastFetch.dosen = Date.now();

        renderDosenOptions(dosenList, select);

    } catch (error) {
        console.error('Error loading dosen:', error);
        select.innerHTML = '<option value="">Error loading data</option>';
    }
}

// ==================== EDIT MODAL FUNCTIONS ====================

/**
 * Load KRS by ID dengan optimasi
 */
async function loadKrsById(id) {
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
                    fakultas {
                        id
                        nama_fakultas
                    }
                }
            }
            semester { id nama_semester }
            tanggal_pengisian
            tanggal_persetujuan
            status
            total_sks
            catatan
            dosenPa { id nama_lengkap }
        }
    }`;
    
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query, variables: { id: parseInt(id) } })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            return null;
        }
        
        return result.data.krs;
    } catch (error) {
        console.error('Error loading KRS data:', error);
        return null;
    }
}

/**
 * Open edit modal dengan loading state
 */
async function openEditModal(id) {
    currentEditingKrsId = id;
    
    // Show modal terlebih dahulu
    const modal = document.getElementById('modalEdit');
    if (!modal) {
        console.error('Modal edit tidak ditemukan');
        alert('Modal edit tidak ditemukan di halaman');
        return;
    }
    
    modal.classList.remove('hidden');
    
    // Tunggu sebentar agar DOM modal ter-render
    await new Promise(resolve => setTimeout(resolve, 50));
    
    // Disable form sementara
    const form = document.getElementById('formEditKrs');
    if (form) {
        const inputs = form.querySelectorAll('input, select, textarea, button');
        inputs.forEach(input => input.disabled = true);
    }
    
    try {
        // Load options dan data KRS secara paralel
        const [krsData] = await Promise.all([
            loadKrsById(id),
            loadFakultasOptionsEdit(),
            loadSemesterOptionsEdit(),
            loadDosenOptionsEdit()
        ]);
        
        if (!krsData) {
            alert('Gagal memuat data KRS');
            closeEditModal();
            return;
        }
        
        // Fill basic form fields dengan null checking
        const setFieldValue = (fieldId, value) => {
            const field = document.getElementById(fieldId);
            if (field) field.value = value || '';
        };
        
        setFieldValue('editId', krsData.id);
        setFieldValue('editPengisian', krsData.tanggal_pengisian);
        setFieldValue('editPersetujuan', krsData.tanggal_persetujuan);
        setFieldValue('editStatus', krsData.status);
        setFieldValue('editTotalSks', krsData.total_sks);
        setFieldValue('editCatatan', krsData.catatan);
        setFieldValue('editSemesterId', krsData.semester?.id);
        setFieldValue('editDosenId', krsData.dosenPa?.id);
        
        // Load cascading data jika mahasiswa exists
        if (krsData.mahasiswa?.jurusan?.fakultas?.id) {
            setFieldValue('editFakultasId', krsData.mahasiswa.jurusan.fakultas.id);
            
            await loadJurusanByFakultasEdit(krsData.mahasiswa.jurusan.fakultas.id);
            setFieldValue('editJurusanId', krsData.mahasiswa.jurusan.id);
            
            await loadMahasiswaByJurusanEdit(krsData.mahasiswa.jurusan.id);
            setFieldValue('editMahasiswaId', krsData.mahasiswa.id);
        }
        
        // Enable form kembali
        if (form) {
            const inputs = form.querySelectorAll('input, select, textarea, button');
            inputs.forEach(input => input.disabled = false);
        }
        
    } catch (error) {
        console.error('Error opening edit modal:', error);
        alert('Terjadi kesalahan saat membuka form edit: ' + error.message);
        closeEditModal();
    }
}

function closeEditModal() {
    const modal = document.getElementById('modalEdit');
    const form = document.getElementById('formEditKrs');
    
    if (modal) {
        modal.classList.add('hidden');
    }
    
    if (form) {
        form.reset();
    }
    
    // Reset dropdowns dengan null checking
    const editJurusan = document.getElementById('editJurusanId');
    const editMahasiswa = document.getElementById('editMahasiswaId');
    const editSearch = document.getElementById('editMahasiswaSearch');
    
    if (editJurusan) {
        editJurusan.disabled = true;
        editJurusan.innerHTML = '<option value="">Pilih fakultas dulu</option>';
    }
    
    if (editMahasiswa) {
        editMahasiswa.disabled = true;
        editMahasiswa.innerHTML = '<option value="">Pilih jurusan dulu</option>';
    }
    
    if (editSearch) {
        editSearch.disabled = true;
        editSearch.value = '';
    }
    
    editMahasiswaData = [];
    currentEditingKrsId = null;
}

/**
 * Update KRS dengan validasi
 */
async function updateKrs() {
    const id = document.getElementById('editId').value;
    const mahasiswa = document.getElementById('editMahasiswaId').value;
    const semester = document.getElementById('editSemesterId').value;
    const pengisian = document.getElementById('editPengisian').value;
    const persetujuan = document.getElementById('editPersetujuan').value;
    const status = document.getElementById('editStatus').value;
    const totalSks = document.getElementById('editTotalSks').value;
    const catatan = document.getElementById('editCatatan').value || '';
    const dosen = document.getElementById('editDosenId').value;
    
    // Validasi
    if (!mahasiswa) return alert("Mahasiswa tidak boleh kosong");
    if (!semester) return alert("Semester tidak boleh kosong");
    if (!pengisian) return alert("Tanggal pengisian tidak boleh kosong");
    if (!status) return alert("Status tidak boleh kosong");
    if (!totalSks) return alert("Total SKS tidak boleh kosong");
    if (!dosen) return alert("Dosen PA tidak boleh kosong");
    
    // Cari submit button
    const submitBtn = document.querySelector('#modalEdit button[type="submit"]');
    
    let originalText = '';
    if (submitBtn) {
        originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        if (typeof getInlineSpinner === 'function') {
            submitBtn.innerHTML = getInlineSpinner();
        } else {
            submitBtn.innerHTML = '<span>Loading...</span>';
        }
    }
    
    try {
        const mutation = `
        mutation {
            updateKrs(id: ${id}, input: {
                mahasiswa_id: ${mahasiswa}
                semester_id: ${semester}
                tanggal_pengisian: "${pengisian}"
                tanggal_persetujuan: ${persetujuan ? `"${persetujuan}"` : 'null'}
                status: "${status}"
                total_sks: ${totalSks}
                catatan: "${catatan.replace(/"/g, '\\"')}"
                dosen_pa_id: ${dosen}
            }) {
                id
                mahasiswa_id
                semester_id
            }
        }`;
        
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            alert('Gagal update data: ' + result.errors[0].message);
            return;
        }
        
        alert('Data KRS berhasil diupdate!');
        closeEditModal();
        
        // Reload data jika fungsi tersedia
        if (typeof loadKrsData === 'function') {
            loadKrsData(currentPageAktif, currentPageArsip);
        }
        
    } catch (error) {
        console.error('Error updating KRS:', error);
        alert('Gagal mengupdate data KRS');
    } finally {
        if (submitBtn && originalText) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
}

// ==================== INITIALIZATION ====================

document.addEventListener('DOMContentLoaded', () => {
    // Pre-load data untuk edit modal
    loadFakultasOptionsEdit();
    loadSemesterOptionsEdit();
    loadDosenOptionsEdit();
    
    // Setup search listener
    const searchInput = document.getElementById('editMahasiswaSearch');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchMahasiswaEdit(e.target.value);
        });
    }
});