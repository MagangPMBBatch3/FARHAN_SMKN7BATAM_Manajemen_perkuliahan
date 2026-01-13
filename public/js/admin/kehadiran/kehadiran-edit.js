function openEditModal(item) {
    document.getElementById('editId').value = item.id;
    document.getElementById('editPertemuan').value = item.pertemuan_id;
    document.getElementById('editMahasiswa').value = item.mahasiswa_id;
    document.getElementById('editKrsDetail').value = item.krs_detail_id;
    document.getElementById('editStatusKehadiran').value = item.status_kehadiran;
    document.getElementById('editKeterangan').value = item.keterangan || '';
    
    // Load KRS Detail for edit
    loadKrsDetailByMahasiswaEdit(item.mahasiswa_id, item.krs_detail_id);
    
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}

async function updateKehadiran() {
    const id = document.getElementById('editId').value;
    const pertemuanId = parseInt(document.getElementById('editPertemuan').value);
    const mahasiswaId = parseInt(document.getElementById('editMahasiswa').value);
    const krsDetailId = parseInt(document.getElementById('editKrsDetail').value);
    
    // Get radio button value
    const statusKehadiranRadio = document.querySelector('input[name="status_kehadiran_edit"]:checked');
    if (!statusKehadiranRadio) return alert("Status kehadiran harus dipilih!");
    const statusKehadiran = statusKehadiranRadio.value;
    
    const keterangan = document.getElementById('editKeterangan').value.trim();

    if (!pertemuanId) return alert("Pertemuan harus dipilih!");
    if (!mahasiswaId) return alert("Mahasiswa harus dipilih!");
    if (!krsDetailId) return alert("KRS Detail harus dipilih!");

    const mutation = `
    mutation {
        updateKehadiran(id: ${id}, input: {
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
            alert('Gagal mengupdate data: ' + result.errors[0].message);
            return;
        }

        alert('Data berhasil diupdate!');
        closeEditModal();
        loadKehadiranData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate data');
    }
}

async function loadKrsDetailByMahasiswaEdit(mahasiswaId, selectedKrsDetailId) {
    if (!mahasiswaId) {
        document.getElementById('editKrsDetail').innerHTML = '<option value="">Pilih KRS Detail</option>';
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
        
        const select = document.getElementById('editKrsDetail');
        select.innerHTML = '<option value="">Pilih KRS Detail</option>' + 
            krsDetails.map(kd => 
                `<option value="${kd.id}" ${kd.id === selectedKrsDetailId ? 'selected' : ''}>
                    ${kd.kelas?.kode_kelas} - ${kd.mataKuliah?.nama_mk}
                </option>`
            ).join('');
    } catch (error) {
        console.error('Error:', error);
    }
}