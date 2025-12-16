let currentEditData = null;

async function openEditModal(itemData) {
    currentEditData = itemData;
    
    document.getElementById('editId').value = itemData.id;
    document.getElementById('editKrsDetailId').value = itemData.krsDetail.id;
    
    // Display mahasiswa dan mata kuliah (read-only)
    document.getElementById('editMahasiswaDisplay').textContent = 
        `${itemData.krsDetail.krs.mahasiswa.nim} - ${itemData.krsDetail.krs.mahasiswa.nama_lengkap}`;
    document.getElementById('editMataKuliahDisplay').textContent = 
        `${itemData.krsDetail.mataKuliah.kode_mk} - ${itemData.krsDetail.mataKuliah.nama_mk}`;
    
    // Set nilai fields
    document.getElementById('editTugas').value = itemData.tugas || '';
    document.getElementById('editQuiz').value = itemData.quiz || '';
    document.getElementById('editUts').value = itemData.uts || '';
    document.getElementById('editUas').value = itemData.uas || '';
    document.getElementById('editNilaiAkhir').value = itemData.nilai_akhir || '';
    document.getElementById('editNilaiHuruf').value = itemData.nilai_huruf || '';
    document.getElementById('editNilaiMutu').value = itemData.nilai_mutu || '';
    document.getElementById('editStatus').value = itemData.status || '';
    
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
    currentEditData = null;
}

async function updateNilai() {
    const id = document.getElementById('editId').value;
    const krsDetailId = document.getElementById('editKrsDetailId').value;
    const newTugas = document.getElementById('editTugas').value;
    const newQuiz = document.getElementById('editQuiz').value;
    const newUts = document.getElementById('editUts').value;
    const newUas = document.getElementById('editUas').value;
    const newNilaiAkhir = document.getElementById('editNilaiAkhir').value;
    const newNilaiHuruf = document.getElementById('editNilaiHuruf').value;
    const newNilaiMutu = document.getElementById('editNilaiMutu').value;
    const newStatus = document.getElementById('editStatus').value;
    
    if (!newStatus) return alert("Status nilai harus diisi!");
    
    // Validasi: pastikan krsDetailId tidak berubah (untuk keamanan)
    if (currentEditData && currentEditData.krsDetail.id != krsDetailId) {
        alert("Error: KRS Detail ID tidak boleh berubah!");
        return;
    }

    try {
        const mutation = `
        mutation {
            updateNilai(id: ${id}, input: {
                krs_detail_id: ${krsDetailId}
                tugas: ${newTugas || 'null'}
                quiz: ${newQuiz || 'null'}
                uts: ${newUts || 'null'}
                uas: ${newUas || 'null'}
                nilai_akhir: ${newNilaiAkhir || 'null'}
                nilai_huruf: ${newNilaiHuruf ? `"${newNilaiHuruf}"` : 'null'}
                nilai_mutu: ${newNilaiMutu || 'null'}
                status: "${newStatus}"
            }) {
                id
                krsDetail {
                    id
                    krs {
                        mahasiswa {
                            nama_lengkap
                        }
                    }
                    mataKuliah {
                        nama_mk
                    }
                }
                tugas
                quiz
                uts
                uas
                nilai_akhir
                nilai_huruf
                nilai_mutu
                status
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
            alert('Gagal update data: ' + (result.errors[0]?.message || 'Unknown error'));
            return;
        }

        alert('Data nilai berhasil diupdate!');
        closeEditModal();
        loadNilaiData();
    } catch (error) {
        console.error('Error updating nilai:', error);
        alert('Terjadi kesalahan saat update data');
    }
}