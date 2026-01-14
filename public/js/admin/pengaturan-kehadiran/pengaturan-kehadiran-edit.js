function openEditModal(item) {
    document.getElementById('editId').value = item.id;
    document.getElementById('editKelas').value = item.kelas_id;
    document.getElementById('editMinimalKehadiran').value = item.minimal_kehadiran;
    document.getElementById('editAutoGenerate').checked = item.auto_generate_pertemuan;
    document.getElementById('editAktif').checked = item.aktif;
    document.getElementById('editKeterangan').value = item.keterangan || '';
    
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}

async function updatePengaturanKehadiran() {
    const id = document.getElementById('editId').value;
    const kelasId = parseInt(document.getElementById('editKelas').value);
    const minimalKehadiran = parseFloat(document.getElementById('editMinimalKehadiran').value);
    const autoGenerate = document.getElementById('editAutoGenerate').checked; // Boolean langsung
    const aktif = document.getElementById('editAktif').checked; // Boolean langsung
    const keterangan = document.getElementById('editKeterangan').value.trim();

    if (!kelasId) return alert("Kelas harus dipilih!");
    if (isNaN(minimalKehadiran)) return alert("Minimal kehadiran harus diisi!");
    if (minimalKehadiran < 0 || minimalKehadiran > 100) return alert("Minimal kehadiran harus antara 0-100!");

    const mutation = `
    mutation {
        updatePengaturanKehadiran(id: ${id}, input: {
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
            alert('Gagal mengupdate data: ' + result.errors[0].message);
            return;
        }

        alert('Data berhasil diupdate!');
        closeEditModal();
        loadPengaturanKehadiranData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate data');
    }
}