// ==================== OPTIMIZED ADD MODAL ====================
// File: krs-add.js

let addMahasiswaData = [];

// ==================== CACHED DATA LOADING ====================

/**
 * Load fakultas dengan caching
 */
async function loadFakultasOptionsAdd() {
    const select = document.getElementById('addFakultasId');
    if (!select) return;

    // Cek cache
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

        // Simpan ke cache
        dataCache.fakultas = fakultasList;
        dataCache.lastFetch.fakultas = Date.now();

        renderFakultasOptions(fakultasList, select);

    } catch (error) {
        console.error('Error loading fakultas:', error);
        select.innerHTML = '<option value="">Error loading data</option>';
    }
}

function renderFakultasOptions(fakultasList, select) {
    select.innerHTML = '<option value="">Pilih Fakultas</option>';
    fakultasList.forEach(fakultas => {
        select.innerHTML += `<option value="${fakultas.id}">${fakultas.nama_fakultas}</option>`;
    });
}

/**
 * Load jurusan by fakultas dengan optimasi
 */
async function loadJurusanByFakultasAdd(fakultasId) {
    const select = document.getElementById('addJurusanId');
    const mahasiswaSelect = document.getElementById('addMahasiswaId');
    const searchInput = document.getElementById('addMahasiswaSearch');
    
    if (!fakultasId) {
        resetJurusanAndMahasiswaAdd(select, mahasiswaSelect, searchInput);
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

function resetJurusanAndMahasiswaAdd(select, mahasiswaSelect, searchInput) {
    select.disabled = true;
    select.innerHTML = '<option value="">Pilih fakultas terlebih dahulu</option>';
    
    mahasiswaSelect.disabled = true;
    mahasiswaSelect.innerHTML = '<option value="">Pilih jurusan terlebih dahulu</option>';
    searchInput.disabled = true;
    searchInput.value = '';
    addMahasiswaData = [];
}

/**
 * Load mahasiswa by jurusan dengan optimasi
 */
async function loadMahasiswaByJurusanAdd(jurusanId) {
    const select = document.getElementById('addMahasiswaId');
    const searchInput = document.getElementById('addMahasiswaSearch');
    
    if (!jurusanId) {
        select.disabled = true;
        select.innerHTML = '<option value="">Pilih jurusan terlebih dahulu</option>';
        searchInput.disabled = true;
        searchInput.value = '';
        addMahasiswaData = [];
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

        addMahasiswaData = mahasiswaList;

        if (mahasiswaList.length === 0) {
            select.innerHTML = '<option value="">Tidak ada mahasiswa tersedia</option>';
            select.disabled = true;
            searchInput.disabled = true;
            return;
        }

        renderMahasiswaOptionsAdd(mahasiswaList);
        select.disabled = false;
        searchInput.disabled = false;

    } catch (error) {
        console.error('Error loading mahasiswa:', error);
        select.innerHTML = '<option value="">Error loading data</option>';
    }
}

function renderMahasiswaOptionsAdd(mahasiswaList) {
    const select = document.getElementById('addMahasiswaId');
    select.innerHTML = '<option value="">Pilih Mahasiswa</option>';
    
    // Gunakan DocumentFragment untuk performa lebih baik
    const fragment = document.createDocumentFragment();
    const tempDiv = document.createElement('div');
    
    const options = mahasiswaList.map(mhs => 
        `<option value="${mhs.id}">${mhs.nim} - ${mhs.nama_lengkap}</option>`
    ).join('');
    
    tempDiv.innerHTML = options;
    while (tempDiv.firstChild) {
        fragment.appendChild(tempDiv.firstChild);
    }
    
    select.appendChild(fragment);
}

/**
 * Search mahasiswa dengan debounce
 */
const searchMahasiswaAdd = debounce((searchTerm) => {
    if (!searchTerm || searchTerm.trim() === '') {
        renderMahasiswaOptionsAdd(addMahasiswaData);
        return;
    }

    const searchLower = searchTerm.toLowerCase();
    const filtered = addMahasiswaData.filter(mhs => 
        mhs.nim.toLowerCase().includes(searchLower) ||
        mhs.nama_lengkap.toLowerCase().includes(searchLower)
    );

    const select = document.getElementById('addMahasiswaId');
    if (filtered.length === 0) {
        select.innerHTML = '<option value="">Tidak ada hasil pencarian</option>';
    } else {
        renderMahasiswaOptionsAdd(filtered);
    }
}, 300);

/**
 * Load semester dengan caching
 */
async function loadSemesterOptionsAdd() {
    const select = document.getElementById('addSemesterId');
    if (!select) return;

    // Cek cache
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

function renderSemesterOptions(semesterList, select) {
    select.innerHTML = '<option value="">Pilih Semester</option>';
    semesterList.forEach(semester => {
        select.innerHTML += `<option value="${semester.id}">${semester.nama_semester} (${semester.tahun_ajaran})</option>`;
    });
}

/**
 * Load dosen dengan caching
 */
async function loadDosenOptionsAdd() {
    const select = document.getElementById('addDosenId');
    if (!select) return;

    // Cek cache
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

function renderDosenOptions(dosenList, select) {
    select.innerHTML = '<option value="">Pilih Dosen</option>';
    dosenList.forEach(dosen => {
        select.innerHTML += `<option value="${dosen.id}">${dosen.nama_lengkap}</option>`;
    });
}

/**
 * Cek validitas cache (5 menit)
 */
function isCacheValid(key, maxAge = 5 * 60 * 1000) {
    const lastFetch = dataCache.lastFetch[key];
    if (!lastFetch) return false;
    return (Date.now() - lastFetch) < maxAge;
}

// ==================== MODAL FUNCTIONS ====================

function openAddModal() {
    const modal = document.getElementById('modalAdd');
    if (!modal) {
        console.error('Modal add tidak ditemukan');
        alert('Modal add tidak ditemukan di halaman');
        return;
    }
    
    // Load data terlebih dahulu sebelum membuka modal
    loadFakultasOptionsAdd();
    loadSemesterOptionsAdd();
    loadDosenOptionsAdd();
    
    // Buka modal
    modal.classList.remove('hidden');
}

function closeAddModal() {
    const modal = document.getElementById('modalAdd');
    const form = document.getElementById('formAddKrs');
    
    if (modal) {
        modal.classList.add('hidden');
    }
    
    if (form) {
        form.reset();
    }
    
    // Reset dropdowns dengan null checking
    const addJurusan = document.getElementById('addJurusanId');
    const addMahasiswa = document.getElementById('addMahasiswaId');
    const addSearch = document.getElementById('addMahasiswaSearch');
    
    if (addJurusan) {
        addJurusan.disabled = true;
        addJurusan.innerHTML = '<option value="">Pilih fakultas dulu</option>';
    }
    
    if (addMahasiswa) {
        addMahasiswa.disabled = true;
        addMahasiswa.innerHTML = '<option value="">Pilih jurusan dulu</option>';
    }
    
    if (addSearch) {
        addSearch.disabled = true;
        addSearch.value = '';
    }
    
    addMahasiswaData = [];
}

/**
 * Create KRS dengan validasi dan error handling
 */
async function createKrs() {
    const mahasiswa = document.getElementById('addMahasiswaId').value;
    const semester = document.getElementById('addSemesterId').value;
    const pengisian = document.getElementById('addPengisian').value;
    const status = document.getElementById('addStatus').value;
    const catatan = document.getElementById('addCatatan').value || '';
    const dosen = document.getElementById('addDosenId').value;

    // Validasi
    if (!mahasiswa) return alert("Mahasiswa harus dipilih!");
    if (!semester) return alert("Semester harus dipilih!");
    if (!pengisian) return alert("Tanggal pengisian harus diisi!");
    if (!status) return alert("Status harus dipilih!");
    if (!dosen) return alert("Dosen PA harus dipilih!");

    // Cari submit button
    const submitBtn = document.querySelector('#modalAdd button[type="submit"]');
    
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
            createKrs(input: {
                mahasiswa_id: ${mahasiswa}
                semester_id: ${semester}
                tanggal_pengisian: "${pengisian}"
                status: "${status}"
                total_sks: 0
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
            alert('Gagal menambahkan KRS: ' + result.errors[0].message);
            return;
        }

        alert('KRS berhasil ditambahkan!');
        closeAddModal();
        
        // Reload data jika fungsi tersedia
        if (typeof loadKrsData === 'function') {
            loadKrsData(currentPageAktif, currentPageArsip);
        }

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan KRS');
    } finally {
        if (submitBtn && originalText) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
}

// ==================== INITIALIZATION ====================

document.addEventListener('DOMContentLoaded', () => {
    // Pre-load data ke cache
    loadFakultasOptionsAdd();
    loadSemesterOptionsAdd();
    loadDosenOptionsAdd();
    
    // Setup search listener
    const searchInput = document.getElementById('addMahasiswaSearch');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchMahasiswaAdd(e.target.value);
        });
    }
});