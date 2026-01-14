// Open Add Modal
function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
}

// Close Add Modal
function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.getElementById('formAddKehadiran').reset();
    document.getElementById('addKrsDetail').innerHTML = '<option value="">Pilih KRS Detail</option>';
}

// Create Kehadiran
async function createKehadiran() {
    const pertemuanId = parseInt(document.getElementById('addPertemuan').value);
    const mahasiswaId = parseInt(document.getElementById('addMahasiswa').value);
    const krsDetailId = parseInt(document.getElementById('addKrsDetail').value);
    const statusKehadiran = document.querySelector('input[name="status_kehadiran"]:checked')?.value;
    const keterangan = document.getElementById('addKeterangan').value.trim();

    if (!pertemuanId) return alert("Pertemuan harus dipilih!");
    if (!mahasiswaId) return alert("Mahasiswa harus dipilih!");
    if (!krsDetailId) return alert("KRS Detail harus dipilih!");
    if (!statusKehadiran) return alert("Status kehadiran harus dipilih!");

    const mutation = `
mutation CreateKehadiran($input: CreateKehadiranInput!) {
    createKehadiran(input: $input) {
        id
        pertemuan_id
        mahasiswa_id
        krs_detail_id
        status_kehadiran
        keterangan
    }
}`;

 
    const variables = {
        input: {
            pertemuan_id: pertemuanId,
            mahasiswa_id: mahasiswaId,
            krs_detail_id: krsDetailId,
            status_kehadiran: statusKehadiran,
            keterangan: keterangan || null
        }
    };

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mutation,
                variables: variables
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Error:', result.errors);
            alert('Gagal menyimpan data: ' + result.errors[0].message);
            return;
        }

        alert('Data berhasil disimpan!');
        closeAddModal();
        loadKehadiranData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
}

// Load KRS Detail by Mahasiswa (for Add Modal)
async function loadKrsDetailByMahasiswa(mahasiswaId) {
    if (!mahasiswaId) {
        document.getElementById('addKrsDetail').innerHTML = '<option value="">Pilih KRS Detail</option>';
        return;
    }

    const query = `
    query KrsDetailByMahasiswa($mahasiswa_id: Int!) {
        krsDetailByMahasiswa(mahasiswa_id: $mahasiswa_id) {
            id
            kelas {
                kode_kelas
                nama_kelas
                mataKuliah {
                    nama_mk
                }
            }
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { mahasiswa_id: parseInt(mahasiswaId) }
            })
        });

        const result = await response.json();
        const krsDetails = result?.data?.krsDetailByMahasiswa || [];
        
        const select = document.getElementById('addKrsDetail');
        select.innerHTML = '<option value="">Pilih KRS Detail</option>' + 
            krsDetails.map(kd => 
                `<option value="${kd.id}">${kd.kelas?.kode_kelas} - ${kd.kelas?.mataKuliah?.nama_mk}</option>`
            ).join('');
    } catch (error) {
        console.error('Error loading KRS Detail:', error);
        alert('Gagal memuat data KRS');
    }
}