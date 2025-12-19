async function loadJurusanOptions() {
    const query = `
    query {
        allJurusan {
            id
            nama_jurusan
            kode_jurusan
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const jurusanList = result.data.allJurusan || [];

        // Isi dropdown Edit
        const selectEdit = document.getElementById('editJurusanId');
        if (selectEdit) {
            selectEdit.innerHTML = '<option value="">Pilih Jurusan</option>';
            jurusanList.forEach(jurusan => {
                selectEdit.innerHTML += `<option value="${jurusan.id}">${jurusan.nama_jurusan} - ${jurusan.kode_jurusan}</option>`;
            });
        }

        // Isi dropdown Add (jika ada)
        const selectAdd = document.getElementById('addJurusanId');
        if (selectAdd) {
            selectAdd.innerHTML = '<option value="">Pilih Jurusan</option>';
            jurusanList.forEach(jurusan => {
                selectAdd.innerHTML += `<option value="${jurusan.id}">${jurusan.nama_jurusan} - ${jurusan.kode_jurusan}</option>`;
            });
        }

    } catch (error) {
        console.error('Error loading jurusan:', error);
    }
}

async function loadSemesterOptions() {
    const query = `
    query {
        allSemester {
            id
            kode_semester
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

        // Isi dropdown Edit
        const selectEdit = document.getElementById('editSemesterId');
        if (selectEdit) {
            selectEdit.innerHTML = '<option value="">Pilih Semester</option>';
            semesterList.forEach(semester => {
                selectEdit.innerHTML += `<option value="${semester.id}">${semester.nama_semester} - ${semester.kode_semester}</option>`;
            });
        }

        // Isi dropdown Add (jika ada)
        const selectAdd = document.getElementById('addSemesterId');
        if (selectAdd) {
            selectAdd.innerHTML = '<option value="">Pilih Semester</option>';
            semesterList.forEach(semester => {
                selectAdd.innerHTML += `<option value="${semester.id}">${semester.nama_semester} - ${semester.kode_semester}</option>`;
            });
        }

    } catch (error) {
        console.error('Error loading Semester:', error);
    }
}

async function loadMataKuliahOptions() {
    const query = `
    query {
        allMataKuliah {
            id
            kode_mk
            nama_mk
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const mkList = result.data.allMataKuliah || [];

        // Isi dropdown Edit
        const selectEdit = document.getElementById('editMataKuliahId');
        if (selectEdit) {
            selectEdit.innerHTML = '<option value="">Pilih Mata Kuliah</option>';
            mkList.forEach(mk => {
                selectEdit.innerHTML += `<option value="${mk.id}">${mk.nama_mk} - ${mk.kode_mk}</option>`;
            });
        }

        // Isi dropdown Add (jika ada)
        const selectAdd = document.getElementById('addMataKuliahId');
        if (selectAdd) {
            selectAdd.innerHTML = '<option value="">Pilih Mata Kuliah</option>';
            mkList.forEach(mk => {
                selectAdd.innerHTML += `<option value="${mk.id}">${mk.nama_mk} - ${mk.kode_mk}</option>`;
            });
        }

    } catch (error) {
        console.error('Error loading Mata Kuliah:', error);
    }
}

async function openEditModal(id, kode, kelas, matakuliah, dosen, semester, kapasitas, status){
    await loadDosenOptions();
    await loadMataKuliahOptions(); 
    await loadSemesterOptions(); 
    document.getElementById('editId').value = id;
    document.getElementById('editKode').value = kode;
    document.getElementById('editKelas').value = kelas;
    document.getElementById('editMataKuliahId').value = matakuliah;
    document.getElementById('editDosenId').value = dosen;
    document.getElementById('editSemesterId').value = semester;
    document.getElementById('editKapasitas').value = kapasitas;
    document.getElementById('editStatus').value = status;
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal(){
    document.getElementById('modalEdit').classList.add('hidden');
}

async function updateKelas(){
    const id = document.getElementById('editId').value;
    const NewKode = document.getElementById('editKode').value;
    const NewKelas = document.getElementById('editKelas').value;
    const NewMataKuliahId = document.getElementById('editMataKuliahId').value;
    const NewDosen = document.getElementById('editDosenId').value;
    const NewSemester = document.getElementById('editSemesterId').value;
    const NewKapasitas = document.getElementById('editKapasitas').value;
    const NewStatus = document.getElementById('editStatus').value;
    if(!NewKelas){return alert("Nama Kelas Tidak Boleh Kosong")};
    if(!NewMataKuliahId){return alert("Mata Kuliah Tidak Boleh Kosong")};
    if(!NewDosen){return alert("Dosen Tidak Boleh Kosong")};
    if(!NewSemester){return alert("Semester Tidak Boleh Kosong")};
    if(!NewKapasitas){return alert("Kapasitas Tidak Boleh Kosong")};
    if(!NewStatus){return alert("Status Tidak Boleh Kosong")};
    if(!NewKode) return alert("Kode Kelas Tidak Boleh Kosong"); {
        const mutation = `
        mutation {
            updateKelas(id: ${id}, input:{kode_kelas: "${NewKode}", nama_kelas: "${NewKelas}", mata_kuliah_id: ${NewMataKuliahId}, dosen_id: ${NewDosen}, semester_id: ${NewSemester}, kapasitas: ${NewKapasitas}, status: "${NewStatus}"}){
                kode_kelas
                nama_kelas
            }
        }`;
        await fetch('/graphql', {
            method: 'POST',
            headers: {'Content-Type' : 'application/json'},
            body: JSON.stringify({query: mutation})
        });
        alert('Data Kelas berhasil diupdate!');
        closeEditModal();
        loadKelasData();

    }
}