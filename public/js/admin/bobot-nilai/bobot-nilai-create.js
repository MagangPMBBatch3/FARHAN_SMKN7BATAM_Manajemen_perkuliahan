function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
    calculateTotalBobot('add');
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.getElementById('formAddBobotNilai').reset();
}

function calculateTotalBobot(type) {
    const prefix = type === 'add' ? 'add' : 'edit';
    const tugas = parseFloat(document.getElementById(`${prefix}Tugas`).value) || 0;
    const quiz = parseFloat(document.getElementById(`${prefix}Quiz`).value) || 0;
    const uts = parseFloat(document.getElementById(`${prefix}UTS`).value) || 0;
    const uas = parseFloat(document.getElementById(`${prefix}UAS`).value) || 0;
    const kehadiran = parseFloat(document.getElementById(`${prefix}Kehadiran`).value) || 0;
    const praktikum = parseFloat(document.getElementById(`${prefix}Praktikum`).value) || 0;
    
    const total = tugas + quiz + uts + uas + kehadiran + praktikum;
    const totalElement = document.getElementById(`${prefix}TotalBobot`);
    totalElement.textContent = total.toFixed(2) + '%';
    
    if (total === 100) {
        totalElement.classList.remove('text-red-600');
        totalElement.classList.add('text-green-600');
    } else {
        totalElement.classList.remove('text-green-600');
        totalElement.classList.add('text-red-600');
    }
}

async function createBobotNilai() {
    const mataKuliahId = parseInt(document.getElementById('addMataKuliah').value);
    const semesterId = parseInt(document.getElementById('addSemester').value);
    const tugas = parseFloat(document.getElementById('addTugas').value) || 0;
    const quiz = parseFloat(document.getElementById('addQuiz').value) || 0;
    const uts = parseFloat(document.getElementById('addUTS').value) || 0;
    const uas = parseFloat(document.getElementById('addUAS').value) || 0;
    const kehadiran = parseFloat(document.getElementById('addKehadiran').value) || 0;
    const praktikum = parseFloat(document.getElementById('addPraktikum').value) || 0;
    const keterangan = document.getElementById('addKeterangan').value.trim();

    if (!mataKuliahId) return alert("Mata kuliah harus dipilih!");
    if (!semesterId) return alert("Semester harus dipilih!");
    
    const total = tugas + quiz + uts + uas + kehadiran + praktikum;
    if (total !== 100) return alert("Total bobot harus 100%!");

    const mutation = `
    mutation {
        createBobotNilai(input: {
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
            alert('Gagal menyimpan data: ' + result.errors[0].message);
            return;
        }

        alert('Data berhasil disimpan!');
        closeAddModal();
        loadBobotNilaiData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
}