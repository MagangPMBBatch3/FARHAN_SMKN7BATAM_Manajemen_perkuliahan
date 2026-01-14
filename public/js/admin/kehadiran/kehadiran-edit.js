function openEditModal(item) {
    document.getElementById('editId').value = item.id;
    document.getElementById('editPertemuan').value = item.pertemuan_id;
    document.getElementById('editMahasiswa').value = item.mahasiswa_id;
    document.getElementById('editKeterangan').value = item.keterangan || '';
    
    setEditStatusKehadiran(item.status_kehadiran);
    loadKrsDetailByMahasiswaEdit(item.mahasiswa_id, item.krs_detail_id);
    
    document.getElementById('modalEdit').classList.remove('hidden');
}

// Close Edit Modal
function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
    document.getElementById('formEditKehadiran').reset();
}

// Load KRS Detail by Mahasiswa (for Edit Modal)
async function loadKrsDetailByMahasiswaEdit(mahasiswaId, selectedKrsDetailId = null) {
    if (!mahasiswaId) {
        document.getElementById('editKrsDetail').innerHTML = '<option value="">Pilih KRS Detail</option>';
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
        const res = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query, 
                variables: { mahasiswa_id: parseInt(mahasiswaId) }
            })
        });

        const data = await res.json();
        const krsDetails = data?.data?.krsDetailByMahasiswa || [];
        
        const select = document.getElementById('editKrsDetail');
        select.innerHTML = '<option value="">Pilih KRS Detail</option>' + 
            krsDetails.map(krs => 
                `<option value="${krs.id}" ${krs.id == selectedKrsDetailId ? 'selected' : ''}>
                    ${krs.kelas?.kode_kelas || ''} - ${krs.kelas?.mataKuliah?.nama_mk || ''}
                </option>`
            ).join('');
    } catch (error) {
        console.error('Error loading KRS Detail:', error);
    }
}

// Update Kehadiran
async function updateKehadiran() {
    const id = parseInt(document.getElementById('editId').value);
    const pertemuan_id = parseInt(document.getElementById('editPertemuan').value);
    const mahasiswa_id = parseInt(document.getElementById('editMahasiswa').value);
    const krs_detail_id = parseInt(document.getElementById('editKrsDetail').value);
    const status_kehadiran = document.querySelector('input[name="status_kehadiran_edit"]:checked')?.value;
    const keterangan = document.getElementById('editKeterangan').value.trim();

    if (!pertemuan_id || !mahasiswa_id || !krs_detail_id || !status_kehadiran) {
        alert('Mohon lengkapi semua field yang wajib diisi!');
        return;
    }

    const mutation = `
    mutation UpdateKehadiran($id: ID!, $input: UpdateKehadiranInput!) {
        updateKehadiran(id: $id, input: $input) {
            id
            pertemuan_id
            mahasiswa_id
            krs_detail_id
            status_kehadiran
            keterangan
        }
    }`;

    const variables = {
        id: id.toString(),
        input: {
            pertemuan_id: pertemuan_id,
            mahasiswa_id: mahasiswa_id,
            krs_detail_id: krs_detail_id,
            status_kehadiran: status_kehadiran,
            keterangan: keterangan || null
        }
    };

    try {
        const res = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query: mutation,
                variables: variables
            })
        });

        const data = await res.json();
        
        if (data.errors) {
            console.error('GraphQL Error:', data.errors);
            alert('Error: ' + data.errors[0].message);
            return;
        }

        alert('Data kehadiran berhasil diupdate!');
        closeEditModal();
        loadKehadiranData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error updating kehadiran:', error);
        alert('Terjadi kesalahan saat mengupdate data');
    }
}

// Set Edit Status Kehadiran Radio Button
function setEditStatusKehadiran(value) {
    const radios = document.getElementsByName('status_kehadiran_edit');
    radios.forEach(radio => {
        radio.checked = (radio.value === value);
    });
}