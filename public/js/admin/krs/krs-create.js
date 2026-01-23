// ==================== ADD MODAL - LOAD OPTIONS ====================

let addMahasiswaData = []; // Store mahasiswa data for search in ADD modal

async function loadFakultasOptionsAdd() {
    const query = `
    query {
        allFakultas {
            id
            nama_fakultas
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const fakultasList = result.data.allFakultas || [];

        const selectAdd = document.getElementById('addFakultasId');
        selectAdd.innerHTML = '<option value="">Pilih Fakultas</option>';
        fakultasList.forEach(fakultas => {
            selectAdd.innerHTML += `<option value="${fakultas.id}">${fakultas.nama_fakultas}</option>`;
        });

    } catch (error) {
        console.error('Error loading fakultas:', error);
    }
}

async function loadJurusanByFakultasAdd(fakultasId) {
    const select = document.getElementById('addJurusanId');
    const mahasiswaSelect = document.getElementById('addMahasiswaId');
    const searchInput = document.getElementById('addMahasiswaSearch');
    
    if (!fakultasId) {
        select.disabled = true;
        select.innerHTML = '<option value="">Pilih fakultas terlebih dahulu</option>';
        
        mahasiswaSelect.disabled = true;
        mahasiswaSelect.innerHTML = '<option value="">Pilih jurusan terlebih dahulu</option>';
        searchInput.disabled = true;
        searchInput.value = '';
        addMahasiswaData = [];
        return;
    }

    select.disabled = true;
    select.innerHTML = '<option value="">Loading...</option>';

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
    select.innerHTML = '<option value="">Loading...</option>';
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

        // Store data for search
        addMahasiswaData = mahasiswaList;

        if (mahasiswaList.length === 0) {
            select.innerHTML = '<option value="">Tidak ada mahasiswa tersedia</option>';
            select.disabled = true;
            searchInput.disabled = true;
            return;
        }

        // Render all mahasiswa
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
    mahasiswaList.forEach(mhs => {
        select.innerHTML += `<option value="${mhs.id}">${mhs.nim} - ${mhs.nama_lengkap}</option>`;
    });
}

function searchMahasiswaAdd(searchTerm) {
    if (!searchTerm || searchTerm.trim() === '') {
        // Show all if search is empty
        renderMahasiswaOptionsAdd(addMahasiswaData);
        return;
    }

    // Filter mahasiswa based on search term
    const searchLower = searchTerm.toLowerCase();
    const filtered = addMahasiswaData.filter(mhs => 
        mhs.nim.toLowerCase().includes(searchLower) ||
        mhs.nama_lengkap.toLowerCase().includes(searchLower)
    );

    // Render filtered results
    const select = document.getElementById('addMahasiswaId');
    if (filtered.length === 0) {
        select.innerHTML = '<option value="">Tidak ada hasil pencarian</option>';
    } else {
        renderMahasiswaOptionsAdd(filtered);
    }
}

async function loadSemesterOptionsAdd() {
    const query = `
    query {
        allSemester {
            id
            nama_semester
            tahun_ajaran
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const semesterList = result.data.allSemester || [];

        const selectAdd = document.getElementById('addSemesterId');
        selectAdd.innerHTML = '<option value="">Pilih Semester</option>';
        semesterList.forEach(semester => {
            selectAdd.innerHTML += `<option value="${semester.id}">${semester.nama_semester} (${semester.tahun_ajaran})</option>`;
        });

    } catch (error) {
        console.error('Error loading semester:', error);
    }
}

async function loadDosenOptionsAdd() {
    const query = `
    query {
        allDosen {
            id
            nama_lengkap
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const dosenList = result.data.allDosen || [];

        const selectAdd = document.getElementById('addDosenId');
        selectAdd.innerHTML = '<option value="">Pilih Dosen</option>';
        dosenList.forEach(dosen => {
            selectAdd.innerHTML += `<option value="${dosen.id}">${dosen.nama_lengkap}</option>`;
        });

    } catch (error) {
        console.error('Error loading dosen:', error);
    }
}

// ==================== ADD MODAL FUNCTIONS ====================

function openAddModal() {
    loadFakultasOptionsAdd();
    loadSemesterOptionsAdd();
    loadDosenOptionsAdd();
    document.getElementById('modalAdd').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.getElementById('formAddKrs').reset();
    
    // Reset dropdowns
    document.getElementById('addJurusanId').disabled = true;
    document.getElementById('addJurusanId').innerHTML = '<option value="">Pilih fakultas dulu</option>';
    
    document.getElementById('addMahasiswaId').disabled = true;
    document.getElementById('addMahasiswaId').innerHTML = '<option value="">Pilih jurusan dulu</option>';
    
    document.getElementById('addMahasiswaSearch').disabled = true;
    document.getElementById('addMahasiswaSearch').value = '';
    
    addMahasiswaData = [];
}

async function createKrs() {
    const mahasiswa = document.getElementById('addMahasiswaId').value;
    const semester = document.getElementById('addSemesterId').value;
    const pengisian = document.getElementById('addPengisian').value;
    const status = document.getElementById('addStatus').value;
    const total_sks = 0;
    const catatan = document.getElementById('addCatatan').value || '';
    const dosen = document.getElementById('addDosenId').value;

    // Validasi
    if (!mahasiswa) return alert("Mahasiswa harus dipilih!");
    if (!semester) return alert("Semester harus dipilih!");
    if (!pengisian) return alert("Tanggal pengisian harus diisi!");
    if (!status) return alert("Status harus dipilih!");
    if (!dosen) return alert("Dosen PA harus dipilih!");

    const mutation = `
    mutation {
        createKrs(input: {
            mahasiswa_id: ${mahasiswa}
            semester_id: ${semester}
            tanggal_pengisian: "${pengisian}"
            status: "${status}"
            total_sks: ${total_sks}
            catatan: "${catatan}"
            dosen_pa_id: ${dosen}
        }) {
            id
            mahasiswa_id
            semester_id
        }
    }`;

    try {
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
        loadKrsData(currentPageAktif, currentPageArsip);

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan KRS');
    }
}

// ==================== INIT ====================

document.addEventListener('DOMContentLoaded', () => {
    loadFakultasOptionsAdd();
    loadSemesterOptionsAdd();
    loadDosenOptionsAdd();
});