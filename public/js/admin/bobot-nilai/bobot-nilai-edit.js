function openEditModal(item) {
    document.getElementById('editId').value = item.id;
    document.getElementById('editMataKuliah').value = item.mata_kuliah_id;
    document.getElementById('editSemester').value = item.semester_id;
    document.getElementById('editTugas').value = item.tugas || 0;
    document.getElementById('editQuiz').value = item.quiz || 0;
    document.getElementById('editUTS').value = item.uts || 0;
    document.getElementById('editUAS').value = item.uas || 0;
    document.getElementById('editKehadiran').value = item.kehadiran || 0;
    document.getElementById('editPraktikum').value = item.praktikum || 0;
    document.getElementById('editKeterangan').value = item.keterangan || '';
    
    calculateTotalBobot('edit');
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}

async function updateBobotNilai() {
    const id = document.getElementById('editId').value;
    const mataKuliahId = parseInt(document.getElementById('editMataKuliah').value);
    const semesterId = parseInt(document.getElementById('editSemester').value);
    const tugas = parseFloat(document.getElementById('editTugas').value) || 0;
    const quiz = parseFloat(document.getElementById('editQuiz').value) || 0;
    const uts = parseFloat(document.getElementById('editUTS').value) || 0;
    const uas = parseFloat(document.getElementById('editUAS').value) || 0;
    const kehadiran = parseFloat(document.getElementById('editKehadiran').value) || 0;
    const praktikum = parseFloat(document.getElementById('editPraktikum').value) || 0;
    const keterangan = document.getElementById('editKeterangan').value.trim();

    if (!mataKuliahId) return alert("Mata kuliah harus dipilih!");
    if (!semesterId) return alert("Semester harus dipilih!");
    
    const total = tugas + quiz + uts + uas + kehadiran + praktikum;
    if (total !== 100) return alert("Total bobot harus 100%!");

    const mutation = `
    mutation {
        updateBobotNilai(id: ${id}, input: {
            mata_kuliah_id: ${mataKuliahId},
            semester_id: ${semesterId},
            tugas: ${tugas},
            quiz: ${quiz},
            uts: ${uts},
            uas: ${uas},
            kehadiran: ${kehadiran},
            praktikum: ${praktikum},
            keterangan: "${keterangan}"
        }) {
            id mata_kuliah_id semester_id tugas quiz uts uas kehadiran praktikum
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
        loadBobotNilaiData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate data');
    }
}