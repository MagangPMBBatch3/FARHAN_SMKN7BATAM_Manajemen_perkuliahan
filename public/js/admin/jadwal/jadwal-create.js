// Load data jurusan untuk dropdown
async function loadKelasOptions() {
    const query = `
    query {
        allKelas {
            id
            nama_kelas
            kode_kelas
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const kelasList = result.data.allKelas || [];

        // Isi dropdown Add
        const selectAdd = document.getElementById('addKelasId');
        selectAdd.innerHTML = '<option value="">Pilih Kelas</option>';
        kelasList.forEach(kelas => {
            selectAdd.innerHTML += `<option value="${kelas.id}">${kelas.nama_kelas}</option>`;
        });

        // Isi dropdown Edit
        const selectEdit = document.getElementById('editKelasId');
        selectEdit.innerHTML = '<option value="">Pilih Kelas</option>';
        kelasList.forEach(kelas => {
            selectEdit.innerHTML += `<option value="${kelas.id}">${kelas.nama_kelas}</option>`;
        });

    } catch (error) {   
        console.error('Error loading kelas:', error);
    }
}
async function loadRuanganOptions() {
    const query = `
    query {
        allRuangan {
            id
            nama_ruangan
            kode_ruangan
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const ruanganList = result.data.allRuangan || [];

        // Isi dropdown Add
        const selectAdd = document.getElementById('addRuanganId');
        selectAdd.innerHTML = '<option value="">Pilih Ruangan</option>';
        ruanganList.forEach(ruangan => {
            selectAdd.innerHTML += `<option value="${ruangan.id}">${ruangan.nama_ruangan}</option>`;
        });

        // Isi dropdown Edit
        const selectEdit = document.getElementById('editRuanganId');
        selectEdit.innerHTML = '<option value="">Pilih Ruangan</option>';
        ruanganList.forEach(ruangan  => {
            selectEdit.innerHTML += `<option value="${ruangan   .id}">${ruangan    .nama_ruangan   }</option>`;
        });

    } catch (error) {   
        console.error('Error loading ruangan    :', error);
    }
}

function openAddModal() {
    loadKelasOptions();
    loadRuanganOptions();
    document.getElementById('modalAdd').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.getElementById('addKelasId').value = '';
    document.getElementById('addRuanganId').value = '';
    document.getElementById('addHari').value = '';
    document.getElementById('addMulai').value = '';
    document.getElementById('addSelesai').value = '';
    document.getElementById('addKeterangan').value = '';
}

async function createJadwal() {
    // Ambil data dari form
    const kelas = document.getElementById('addKelasId').value;
    const ruangan = document.getElementById('addRuanganId').value;
    const hari = document.getElementById('addHari').value;
    const mulai = document.getElementById('addMulai').value;
    const selesai = document.getElementById('addSelesai').value;
    const keterangan = document.getElementById('addKeterangan').value;

    // Validasi field required
    if (!kelas) return alert("Kelas harus diisi!");
    if (!ruangan) return alert("Ruangan harus diisi!");
    if (!hari) return alert("Hari harus dipilih!");
    if (!mulai) return alert("Jam Mulai harus diisi!");
    if (!selesai) return alert("Jam Selesai Harus diisi!");
    const mutation = `
    mutation {
        createJadwalKuliah(input: {
            kelas_id: ${kelas}
            ruangan_id: ${ruangan}
            hari: "${hari}"
            jam_mulai: "${mulai}"
            jam_selesai: "${selesai}"
            keterangan: "${keterangan}"
        }) {
            id
            kelas_id
            ruangan_id
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
            alert('Gagal menambahkan Jadwal: ' + result.errors[0].message);
            return;
        }

        alert('Jadwal berhasil ditambahkan!');
        closeAddModal();
        loadJadwalData(currentPageAktif, currentPageArsip);

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan mata kuliah');
    }
}
document.addEventListener('DOMContentLoaded', () => {
    loadKelasOptions();
    loadRuanganOptions();
});