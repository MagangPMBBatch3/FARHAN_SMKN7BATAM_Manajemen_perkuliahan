// Fungsi untuk memuat data KRS berdasarkan ID
async function loadKrsById(id) {
    const query = `
    query($id: ID!) {
        krs(id: $id) {
            id
            mahasiswa {
                id
                nama_lengkap
            }
            semester {
                id
                nama_semester
            }
            tanggal_pengisian
            tanggal_persetujuan
            status
            total_sks
            catatan
            dosenPa {
                id
                nama_lengkap
            }
        }
    }`;
    
    const variables = { id: id };
    
    try {
        const response = await fetch('/graphql', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query, variables })
        });
        
        const result = await response.json();
        return result.data.krs;
    } catch (error) {
        console.error('Error loading KRS data:', error);
        alert('Gagal memuat data KRS');
        return null;
    }
}

// Fungsi untuk membuka modal edit dengan data yang sudah dimuat
async function openEditModal(id) {
    // Load data KRS berdasarkan ID
    const krsData = await loadKrsById(id);
    
    if (!krsData) return;
    
    // Load options untuk dropdown
    await loadMahasiswaOptions();
    await loadSemesterOptions(); 
    await loadDosenOptions();
    
    // Isi form dengan data yang sudah dimuat
    document.getElementById('editId').value = krsData.id;
    document.getElementById('editMahasiswaId').value = krsData.mahasiswa?.id || '';
    document.getElementById('editSemesterId').value = krsData.semester?.id || '';
    document.getElementById('editPengisian').value = krsData.tanggal_pengisian || '';
    document.getElementById('editPersetujuan').value = krsData.tanggal_persetujuan || '';
    document.getElementById('editStatus').value = krsData.status || '';
    document.getElementById('editTotalSks').value = krsData.total_sks || '';
    document.getElementById('editCatatan').value = krsData.catatan || '';
    document.getElementById('editDosenId').value = krsData.dosenPa?.id || '';
    
    // Tampilkan modal
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}

async function updateKrs() {
    const id = document.getElementById('editId').value;
    const NewMahasiswa = document.getElementById('editMahasiswaId').value;
    const NewSemester = document.getElementById('editSemesterId').value;
    const NewPengisian = document.getElementById('editPengisian').value;
    const NewPersetujuan = document.getElementById('editPersetujuan').value;
    const NewStatus = document.getElementById('editStatus').value;
    const NewSks = document.getElementById('editTotalSks').value;
    const NewCatatan = document.getElementById('editCatatan').value;
    const NewDosen = document.getElementById('editDosenId').value;
    
    // Validasi
    if (!NewMahasiswa) return alert("Mahasiswa tidak boleh kosong");
    if (!NewSemester) return alert("Semester tidak boleh kosong");
    if (!NewPengisian) return alert("Tanggal pengisian tidak boleh kosong");
    if (!NewStatus) return alert("Status tidak boleh kosong");
    if (!NewSks) return alert("Total SKS tidak boleh kosong");
    if (!NewDosen) return alert("Dosen PA tidak boleh kosong");
    
    const mutation = `
    mutation {
        updateKrs(id: ${id}, input: {
            mahasiswa_id: ${NewMahasiswa}, 
            semester_id: ${NewSemester}, 
            tanggal_pengisian: "${NewPengisian}", 
            tanggal_persetujuan: "${NewPersetujuan}", 
            status: "${NewStatus}", 
            total_sks: ${NewSks}, 
            catatan: "${NewCatatan}", 
            dosen_pa_id: ${NewDosen}
        }) {
            id
            mahasiswa_id
            semester_id
        }
    }`;
    
    try {
        await fetch('/graphql', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });
        
        alert('Data KRS berhasil diupdate!');
        closeEditModal();
        loadKrsData(currentPageAktif, currentPageArsip);
    } catch (error) {
        console.error('Error updating KRS:', error);
        alert('Gagal mengupdate data KRS');
    }
}