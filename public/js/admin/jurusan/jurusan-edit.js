async function loadFakultasOptions() {
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
        if (selectEdit) {
            selectEdit.innerHTML = '<option value="">Pilih Fakultas</option>';
            fakultasList.forEach(fakultas => {
                selectEdit.innerHTML += `<option value="${fakultas.id}">${fakultas.nama_fakultas}</option>`;
            });
        }

        const selectAdd = document.getElementById('addFakultasId');
        if (selectAdd) {
            selectAdd.innerHTML = '<option value="">Pilih Fakultas</option>';
            fakultasList.forEach(fakultas => {
                selectAdd.innerHTML += `<option value="${fakultas.id}">${fakultas.nama_fakultas}</option>`;
            });
        }

    } catch (error) {
        console.error('Error loading Fakultas:', error);
    }
}

async function openEditModal(id, kode, nama, fakultasId, jenjang, akreditasi, kaprodi) {
    await loadFakultasOptions();
    document.getElementById('editId').value = id;
    document.getElementById('editKode').value = kode;
    document.getElementById('editNama').value = nama;
    document.getElementById('editFakultasId').value = fakultasId;
    document.getElementById('editJenjang').value = jenjang;
    document.getElementById('editAkreditasi').value = akreditasi;
    document.getElementById('editKaprodi').value = kaprodi;
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}

async function updateJurusan() {
    const id = document.getElementById('editId').value;
    const kode = document.getElementById('editKode').value;
    const nama = document.getElementById('editNama').value;
    const fakultas = parseInt(document.getElementById('editFakultasId').value);
    const jenjang = document.getElementById('editJenjang').value;
    const akreditasi = document.getElementById('editAkreditasi').value;
    const kaprodi = document.getElementById('editKaprodi').value;

    // Validasi
    if (!kode) return alert("Kode Jurusan Tidak Boleh Kosong");
    if (!nama) return alert("Nama Jurusan Tidak Boleh Kosong");
    if (!fakultas) return alert("Fakultas Tidak Boleh Kosong");
    if (!jenjang) return alert("Jenjang Tidak Boleh Kosong");
    if (!akreditasi) return alert("Akreditasi Tidak Boleh Kosong");
    if (!kaprodi) return alert("Kaprodi Tidak Boleh Kosong");

    try {
        const mutation = `
        mutation {
            updateJurusan(
                id: ${id}, 
                input: {
                    kode_jurusan: "${kode}", 
                    nama_jurusan: "${nama}", 
                    fakultas_id: ${fakultas}, 
                    jenjang: "${jenjang}", 
                    akreditasi: "${akreditasi}", 
                    kaprodi: "${kaprodi}"
                }
            ){
                id
                kode_jurusan
                nama_jurusan
                fakultas_id
                jenjang
                akreditasi
                kaprodi
            }
        }`;

        const response = await fetch('/graphql', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });

        const result = await response.json();

        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            alert('Gagal mengupdate data: ' + result.errors[0].message);
            return;
        }

        alert('Data jurusan berhasil diupdate!');
        closeEditModal();
        loadJurusanData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate data');
    }
}