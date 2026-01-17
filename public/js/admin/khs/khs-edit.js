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
        /* console.log(mahasiswaList); */

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

async function openEditModal(id, mahasiswa, semester, sks_semester, sks_kumulatif, ip_semester, ipk) {
    await loadMahasiswaOptions();
    await loadSemesterOptions();
    document.getElementById('editId').value = id;
    document.getElementById('editMahasiswaId').value = mahasiswa;
    document.getElementById('editSemesterId').value = semester;
    document.getElementById('editSksSemester').value = sks_semester;
    document.getElementById('editSksKumulatif').value = sks_kumulatif;
    document.getElementById('editIpSemester').value = ip_semester;
    document.getElementById('editIPK').value = ipk;
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}

async function updateKhs() {
    const id = document.getElementById('editId').value;
    const NewMahasiswa = document.getElementById('editMahasiswaId').value;
    const NewSemester = document.getElementById('editSemesterId').value;
    const NewSksSemester = document.getElementById('editSksSemester').value;
    const NewSksKumulatif = document.getElementById('editSksKumulatif').value;
    const Newipk = document.getElementById('editIPK').value;
    const NewIpSemester = document.getElementById('editIpSemester').value;
    if (!NewSemester) { return alert("Semester Tidak Boleh Kosong") };
    if (!NewSksSemester) { return alert("sks semester Tidak Boleh Kosong") };
    if (!NewSksKumulatif) { return alert("sks kumulatif Tidak Boleh Kosong") };
    if (!NewSemester) { return alert("Semester Tidak Boleh Kosong") };
    if (!Newipk) { return alert("ipk Tidak Boleh Kosong") };
    if (!NewIpSemester) { return alert("ip semester Tidak Boleh Kosong") };
    if (!NewMahasiswa) return alert("mahasiswa Tidak Boleh Kosong"); {
        const mutation = `
        mutation {
            updateKhs(id: ${id}, input:{mahasiswa_id: ${NewMahasiswa}, semester_id: ${NewSemester}, sks_semester: ${NewSksSemester}, sks_kumulatif: ${NewSksKumulatif}, ipk: ${Newipk}, ip_semester: ${NewIpSemester}}){
                mahasiswa_id
                semester_id
            }
        }`;
        await fetch('/graphql', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });
        alert('Data KHS berhasil diupdate!');
        closeEditModal();
        loadKhsData();

    }
}