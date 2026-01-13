function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.getElementById('formAddKehadiran').reset();
}

async function createKehadiran() {
    const pertemuanId = parseInt(document.getElementById('addPertemuan').value);
    const mahasiswaId = parseInt(document.getElementById('addMahasiswa').value);
    const krsDetailId = parseInt(document.getElementById('addKrsDetail').value);
    const statusKehadiran = document.getElementById('addStatusKehadiran').value;
    const keterangan = document.getElementById('addKeterangan').value.trim();

    if (!pertemuanId) return alert("Pertemuan harus dipilih!");
    if (!mahasiswaId) return alert("Mahasiswa harus dipilih!");
    if (!krsDetailId) return alert("KRS Detail harus dipilih!");
    if (!statusKehadiran) return alert("Status kehadiran harus dipilih!");

    const mutation = `
    mutation {
        createKehadiran(input: {
            pertemuan_id: ${pertemuanId},
            mahasiswa_id: ${mahasiswaId},
            krs_detail_id: ${krsDetailId},
            status_kehadiran: ${statusKehadiran},
            keterangan: "${keterangan.replace(/"/g, '\\"')}"
        }) {
            id pertemuan_id mahasiswa_id status_kehadiran
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
            console.error('GraphQL Error:', result.errors);
            alert('Gagal menyimpan data: ' + result.errors[0].message);
            return;
        }

        alert('Data berhasil disimpan!');
        closeAddModal();
        loadKehadiranData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
}

// Load KRS Detail berdasarkan mahasiswa yang dipilih
async function loadKrsDetailByMahasiswa(mahasiswaId) {
    if (!mahasiswaId) {
        document.getElementById('addKrsDetail').innerHTML = '<option value="">Pilih KRS Detail</option>';
        return;
    }

    const query = `
    query {
        krsDetailByMahasiswa(mahasiswa_id: ${mahasiswaId}) {
            id
            kelas { kode_kelas nama_kelas }
            mataKuliah { nama_mk }
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const krsDetails = result?.data?.krsDetailByMahasiswa || [];
        
        const select = document.getElementById('addKrsDetail');
        select.innerHTML = '<option value="">Pilih KRS Detail</option>' + 
            krsDetails.map(kd => 
                `<option value="${kd.id}">${kd.kelas?.kode_kelas} - ${kd.mataKuliah?.nama_mk}</option>`
            ).join('');
    } catch (error) {
        console.error('Error:', error);
    }
}