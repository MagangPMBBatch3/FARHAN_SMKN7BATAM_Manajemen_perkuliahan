function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.getElementById('formAddPengaturanKehadiran').reset();
}

async function createPengaturanKehadiran() {
    const kelasId = parseInt(document.getElementById('addKelas').value);
    const minimalKehadiran = parseFloat(document.getElementById('addMinimalKehadiran').value);
    const autoGenerate = document.getElementById('addAutoGenerate').checked; // Boolean langsung
    const aktif = document.getElementById('addAktif').checked; // Boolean langsung
    const keterangan = document.getElementById('addKeterangan').value.trim();

    if (!kelasId) return alert("Kelas harus dipilih!");
    if (isNaN(minimalKehadiran)) return alert("Minimal kehadiran harus diisi!");
    if (minimalKehadiran < 0 || minimalKehadiran > 100) return alert("Minimal kehadiran harus antara 0-100!");

    const mutation = `
    mutation {
        createPengaturanKehadiran(input: {
            kelas_id: ${kelasId},
            minimal_kehadiran: ${minimalKehadiran},
            auto_generate_pertemuan: ${autoGenerate},
            aktif: ${aktif},
            keterangan: "${keterangan.replace(/"/g, '\\"')}"
        }) {
            id kelas_id minimal_kehadiran
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
        loadPengaturanKehadiranData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
}