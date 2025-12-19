
async function loadJurusanOptions() {
    const query = `
    query {
        allJurusan {
            id
            nama_jurusan
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
                selectEdit.innerHTML += `<option value="${jurusan.id}">${jurusan.nama_jurusan}</option>`;
            });
        }

        // Isi dropdown Add (jika ada)
        const selectAdd = document.getElementById('addJurusanId');
        if (selectAdd) {
            selectAdd.innerHTML = '<option value="">Pilih Jurusan</option>';
            jurusanList.forEach(jurusan => {
                selectAdd.innerHTML += `<option value="${jurusan.id}">${jurusan.nama_jurusan}</option>`;
            });
        }

    } catch (error) {
        console.error('Error loading jurusan:', error);
    }
}
async function openEditModal(id, kode, matakuliah, jurusan, sks, rekomendasi, jenis, deskripsi){
    await loadJurusanOptions();
    document.getElementById('editId').value = id;
    document.getElementById('editKode').value = kode;
    document.getElementById('editMataKuliah').value = matakuliah;
    document.getElementById('editJurusanId').value = jurusan;
    document.getElementById('editSks').value = sks;
    document.getElementById('editRekomendasi').value = rekomendasi;
    document.getElementById('editJenis').value = jenis;
    document.getElementById('editDeskripsi').value = deskripsi;
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal(){
    document.getElementById('modalEdit').classList.add('hidden');
}

async function updateMataKuliah(){
    const id = document.getElementById('editId').value;
    const NewKode = document.getElementById('editKode').value;
    const NewMataKuliah = document.getElementById('editMataKuliah').value;
    const NewJurusan = document.getElementById('editJurusanId').value;
    const NewSks = document.getElementById('editSks').value;
    const NewRekomendasi = document.getElementById('editRekomendasi').value;
    const NewJenis = document.getElementById('editJenis').value;
    const NewDeskripsi = document.getElementById('editDeskripsi').value;
    if(!NewMataKuliah){return alert("Nama Mata Kuliah Tidak Boleh Kosong")};
    if(!NewJurusan){return alert("Jurusan Tidak Boleh Kosong")};
    if(!NewSks){return alert("Jumlah SKS Tidak Boleh Kosong")};
    if(!NewRekomendasi){return alert("Semester Rekomendasi Tidak Boleh Kosong")};
    if(!NewDeskripsi){return alert("Deskripsi Tidak Boleh Kosong")};
    if(!NewJenis){return alert("Jenis Mata Kuliah Tidak Boleh Kosong")};
    if(!NewKode) return alert("Kode Mata Kuliah Tidak Boleh Kosong"); {
        const mutation = `
        mutation {
            updateMataKuliah(id: ${id}, input:{kode_mk: "${NewKode}", nama_mk: "${NewMataKuliah}", jurusan_id: ${NewJurusan}, sks: ${NewSks}, semester_rekomendasi: ${NewRekomendasi}, jenis: "${NewJenis}", deskripsi: "${NewDeskripsi}"}){
                id
                kode_mk
                nama_mk
            }
        }`;
        await fetch('/graphql', {
            method: 'POST',
            headers: {'Content-Type' : 'application/json'},
            body: JSON.stringify({query: mutation})
        });
        alert('Data Mata Kuliah berhasil diupdate!');
        closeEditModal();
        loadMataKuliahData();

    }
}