// ==================== EDIT MODAL - LOAD OPTIONS ====================

let editMahasiswaData = []; // Store mahasiswa data for search in EDIT modal

async function loadFakultasOptionsEdit() {
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

        const selectEdit = document.getElementById('editFakultasId');
        selectEdit.innerHTML = '<option value="">Pilih Fakultas</option>';
        fakultasList.forEach(fakultas => {
            selectEdit.innerHTML += `<option value="${fakultas.id}">${fakultas.nama_fakultas}</option>`;
        });

    } catch (error) {
        console.error('Error loading fakultas:', error);
    }
}

async function loadJurusanByFakultasEdit(fakultasId) {
    const select = document.getElementById('editJurusanId');
    const mahasiswaSelect = document.getElementById('editMahasiswaId');
    const searchInput = document.getElementById('editMahasiswaSearch');
    
    if (!fakultasId) {
        select.disabled = true;
        select.innerHTML = '<option value="">Pilih fakultas terlebih dahulu</option>';
        
        mahasiswaSelect.disabled = true;
        mahasiswaSelect.innerHTML = '<option value="">Pilih jurusan terlebih dahulu</option>';
        searchInput.disabled = true;
        searchInput.value = '';
        editMahasiswaData = [];
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
        editMahasiswaData = mahasiswaList;

        if (mahasiswaList.length === 0) {
            select.innerHTML = '<option value="">Tidak ada mahasiswa tersedia</option>';
            select.disabled = true;
            searchInput.disabled = true;
            return;
        }

        // Render all mahasiswa
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

function searchMahasiswaEdit(searchTerm) {
    if (!searchTerm || searchTerm.trim() === '') {
        // Show all if search is empty
        renderMahasiswaOptionsEdit(editMahasiswaData);
        return;
    }

    // Filter mahasiswa based on search term
    const searchLower = searchTerm.toLowerCase();
    const filtered = editMahasiswaData.filter(mhs => 
        mhs.nim.toLowerCase().includes(searchLower) ||
        mhs.nama_lengkap.toLowerCase().includes(searchLower)
    );

    // Render filtered results
    const select = document.getElementById('editMahasiswaId');
    if (filtered.length === 0) {
        select.innerHTML = '<option value="">Tidak ada hasil pencarian</option>';
    } else {
        renderMahasiswaOptionsEdit(filtered);
    }
}

async function loadSemesterOptionsEdit() {
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

        const selectEdit = document.getElementById('editSemesterId');
        selectEdit.innerHTML = '<option value="">Pilih Semester</option>';
        semesterList.forEach(semester => {
            selectEdit.innerHTML += `<option value="${semester.id}">${semester.nama_semester} (${semester.tahun_ajaran})</option>`;
        });

    } catch (error) {
        console.error('Error loading semester:', error);
    }
}

async function loadDosenOptionsEdit() {
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

        const selectEdit = document.getElementById('editDosenId');
        selectEdit.innerHTML = '<option value="">Pilih Dosen</option>';
        dosenList.forEach(dosen => {
            selectEdit.innerHTML += `<option value="${dosen.id}">${dosen.nama_lengkap}</option>`;
        });

    } catch (error) {
        console.error('Error loading dosen:', error);
    }
}

// ==================== EDIT MODAL FUNCTIONS ====================

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
            semester {
                id
                nama_semester
            }
            tanggal_pengisian
            tanggal_persetujuan
            status
            total_sks
            catatan
            dosen_pa_id {
                id
                nama_lengkap
            }
        }
    }`;
    
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query, variables: { id: parseInt(id) } })
        });
        
        const result = await response.json();
        return result.data.krs;
    } catch (error) {
        console.error('Error loading KRS data:', error);
        alert('Gagal memuat data KRS');
        return null;
    }
}

async function openEditModal(id) {
    const krsData = await loadKrsById(id);
    
    if (!krsData) return;
    
    // Load all options
    await loadFakultasOptionsEdit();
    await loadSemesterOptionsEdit();
    await loadDosenOptionsEdit();
    
    // Fill basic form fields
    document.getElementById('editId').value = krsData.id;
    document.getElementById('editPengisian').value = krsData.tanggal_pengisian || '';
    document.getElementById('editPersetujuan').value = krsData.tanggal_persetujuan || '';
    document.getElementById('editStatus').value = krsData.status || '';
    document.getElementById('editTotalSks').value = krsData.total_sks || '';
    document.getElementById('editCatatan').value = krsData.catatan || '';
    document.getElementById('editSemesterId').value = krsData.semester?.id || '';
    document.getElementById('editDosenId').value = krsData.dosen_pa_id?.id || '';
    
    // Load cascading data if mahasiswa exists
    if (krsData.mahasiswa?.jurusan?.fakultas?.id) {
        // Set fakultas
        document.getElementById('editFakultasId').value = krsData.mahasiswa.jurusan.fakultas.id;
        
        // Load and set jurusan
        await loadJurusanByFakultasEdit(krsData.mahasiswa.jurusan.fakultas.id);
        document.getElementById('editJurusanId').value = krsData.mahasiswa.jurusan.id;
        
        // Load and set mahasiswa
        await loadMahasiswaByJurusanEdit(krsData.mahasiswa.jurusan.id);
        document.getElementById('editMahasiswaId').value = krsData.mahasiswa.id;
    }
    
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
    document.getElementById('formEditKrs').reset();
    
    // Reset dropdowns
    document.getElementById('editJurusanId').disabled = true;
    document.getElementById('editJurusanId').innerHTML = '<option value="">Pilih fakultas dulu</option>';
    
    document.getElementById('editMahasiswaId').disabled = true;
    document.getElementById('editMahasiswaId').innerHTML = '<option value="">Pilih jurusan dulu</option>';
    
    document.getElementById('editMahasiswaSearch').disabled = true;
    document.getElementById('editMahasiswaSearch').value = '';
    
    editMahasiswaData = [];
}

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
    
    const mutation = `
    mutation {
        updateKrs(id: ${id}, input: {
            mahasiswa_id: ${mahasiswa}
            semester_id: ${semester}
            tanggal_pengisian: "${pengisian}"
            tanggal_persetujuan: ${persetujuan ? `"${persetujuan}"` : 'null'}
            status: "${status}"
            total_sks: ${totalSks}
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
            alert('Gagal update data: ' + result.errors[0].message);
            return;
        }
        
        alert('Data KRS berhasil diupdate!');
        closeEditModal();
        loadKrsData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error updating KRS:', error);
        alert('Gagal mengupdate data KRS');
    }
}

// ==================== INIT ====================

document.addEventListener('DOMContentLoaded', () => {
    loadFakultasOptionsEdit();
    loadSemesterOptionsEdit();
    loadDosenOptionsEdit();
});