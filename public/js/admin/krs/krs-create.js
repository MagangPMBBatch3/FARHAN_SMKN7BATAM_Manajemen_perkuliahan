async function loadMahasiswaOptions() {
    const query = `
    query {
        allMahasiswa {
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
        const mahasiswaList = result.data.allMahasiswa || [];

        const selectAdd = document.getElementById('addMahasiswaId');
        selectAdd.innerHTML = '<option value="">Pilih Mahasiswa</option>';
        mahasiswaList.forEach(mahasiswa => {
            selectAdd.innerHTML += `<option value="${mahasiswa.id}">${mahasiswa.nama_lengkap}</option>`;
        });

        const selectEdit = document.getElementById('editMahasiswaId');
        selectEdit.innerHTML = '<option value="">Pilih Mahasiswa</option>';
        mahasiswaList.forEach(mahasiswa => {
            selectEdit.innerHTML += `<option value="${mahasiswa.id}">${mahasiswa.nama_lengkap}</option>`;
        });

    } catch (error) {
        console.error('Error loading mahasiswa:', error);
    }
}
async function loadSemesterOptions() {
    const query = `
    query {
        allSemester {
            id
            nama_semester
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

        // Isi dropdown Add
        const selectAdd = document.getElementById('addSemesterId');
        selectAdd.innerHTML = '<option value="">Pilih Semester</option>';
        semesterList.forEach(Semester => {
            selectAdd.innerHTML += `<option value="${Semester.id}">${Semester.nama_semester}</option>`;
        });

        // Isi dropdown Edit
        const selectEdit = document.getElementById('editSemesterId');
        selectEdit.innerHTML = '<option value="">Pilih Semester</option>';
        semesterList.forEach(Semester => {
            selectEdit.innerHTML += `<option value="${Semester.id}">${Semester.nama_semester}</option>`;
        });

    } catch (error) {
        console.error('Error loading Semester:', error);
    }
}
async function loadDosenOptions() {
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


        // Isi dropdown Add
        const selectAdd = document.getElementById('addDosenId');
        selectAdd.innerHTML = '<option value="">Pilih Dosen</option>';
        dosenList.forEach(dosen => {
            selectAdd.innerHTML += `<option value="${dosen.id}">${dosen.nama_lengkap}</option>`;
        });

        // Isi dropdown Edit
        const selectEdit = document.getElementById('editDosenId');
        selectEdit.innerHTML = '<option value="">Pilih Dosen</option>';
        dosenList.forEach(dosen => {
            selectEdit.innerHTML += `<option value="${dosen.id}">${dosen.nama_lengkap}</option>`;
        });

    } catch (error) {
        console.error('Error loading dosen:', error);
    }
}

function openAddModal() {
    loadSemesterOptions();
    loadMahasiswaOptions();
    loadDosenOptions();
    document.getElementById('modalAdd').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.getElementById('addMahasiswaId').value = '';
    document.getElementById('addSemesterId').value = '';
    document.getElementById('addPengisian').value = '';
    document.getElementById('addStatus').value = '';
    document.getElementById('addTotalSks').value = '';
    document.getElementById('addCatatan').value = '';
    document.getElementById('addDosenId').value = '';
}

async function createKrs() {
    // Ambil data dari form
    const mahasiswa = document.getElementById('addMahasiswaId').value;
    const semester = document.getElementById('addSemesterId').value;
    const pengisian = document.getElementById('addPengisian').value;
    const status = document.getElementById('addStatus').value;
    const total_sks = document.getElementById('addTotalSks').value;
    const catatan = document.getElementById('addCatatan').value;
    const dosen = document.getElementById('addDosenId').value;

    // Validasi field required
    if (!mahasiswa) return alert("mahasiswa harus diisi!");
    if (!semester) return alert("semester harus diisi!");
    if (!pengisian) return alert("sks semester harus dipilih!");
    if (!status) return alert("ip semester dipilih!");
    if (!total_sks) return alert("ipk harus dipilih!");
    if (!dosen) return alert("ipk harus dipilih!");
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

        alert('Kelas berhasil ditambahkan!');
        closeAddModal();
        loadKrsData(currentPageAktif, currentPageArsip);

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan KRS');
    }
}
document.addEventListener('DOMContentLoaded', () => {
    loadMahasiswaOptions();
    loadSemesterOptions();
    loadDosenOptions();
});