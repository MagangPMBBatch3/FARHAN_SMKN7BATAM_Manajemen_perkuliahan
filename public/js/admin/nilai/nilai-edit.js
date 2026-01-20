let currentEditBobotNilai = null;

async function openEditModal(itemData) {
    console.log('Edit data:', itemData);
    
    document.getElementById('editId').value = itemData.id;
    document.getElementById('editKrsDetailId').value = itemData.krsDetail.id;
    
    // Display info mahasiswa, mata kuliah, kelas
    document.getElementById('editMahasiswaDisplay').textContent = 
        `${itemData.krsDetail.krs.mahasiswa.nim} - ${itemData.krsDetail.krs.mahasiswa.nama_lengkap}`;
    document.getElementById('editMataKuliahDisplay').textContent = 
        `${itemData.krsDetail.mataKuliah.kode_mk} - ${itemData.krsDetail.mataKuliah.nama_mk}`;
    
    // Get bobot nilai jika ada
    if (itemData.bobotNilai) {
        currentEditBobotNilai = itemData.bobotNilai;
        document.getElementById('editBobotNilaiId').value = itemData.bobotNilai.id;
        displayEditBobotInfo();
    } else {
        // Jika tidak ada bobot nilai di data, ambil dari server
        await loadBobotNilaiForEdit(itemData);
    }
    
    // Set nilai fields
    document.getElementById('editTugas').value = itemData.tugas || '';
    document.getElementById('editQuiz').value = itemData.quiz || '';
    document.getElementById('editUts').value = itemData.uts || '';
    document.getElementById('editUas').value = itemData.uas || '';
    document.getElementById('editKehadiran').value = itemData.kehadiran || '';
    document.getElementById('editPraktikum').value = itemData.praktikum || '';
    document.getElementById('editNilaiAkhir').value = itemData.nilai_akhir || '';
    document.getElementById('editNilaiHuruf').value = itemData.nilai_huruf || '';
    document.getElementById('editNilaiMutu').value = itemData.nilai_mutu || '';
    document.getElementById('editStatus').value = itemData.status || '';
    
    document.getElementById('modalEdit').classList.remove('hidden');
}

async function loadBobotNilaiForEdit(itemData) {
    try {
        // Query untuk mendapatkan detail kelas dan bobot nilai
        const query = `
        query($kelasId: ID!, $mataKuliahId: ID!, $semesterId: ID!) {
            kelas(id: $kelasId) {
                id
                kode_kelas
                nama_kelas
                semester {
                    nama_semester
                }
            }
            bobotNilaiByMataKuliahSemester(mata_kuliah_id: $mataKuliahId, semester_id: $semesterId) {
                id
                tugas
                quiz
                uts
                uas
                kehadiran
                praktikum
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { 
                    kelasId: itemData.krsDetail.kelas.id,
                    mataKuliahId: itemData.krsDetail.mataKuliah.id,
                    semesterId: itemData.krsDetail.kelas.semester.id
                }
            })
        });

        const result = await response.json();
        console.log(result);
        
        if (result.data.kelas) {
            document.getElementById('editKelasDisplay').textContent = 
                `${result.data.kelas.kode_kelas} - ${result.data.kelas.nama_kelas}`;
            document.getElementById('editSemesterDisplay').textContent = 
                result.data.kelas.semester.nama_semester;
        }
        
        if (result.data.bobotNilaiByMataKuliahSemester) {
            currentEditBobotNilai = result.data.bobotNilaiByMataKuliahSemester;
            document.getElementById('editBobotNilaiId').value = currentEditBobotNilai.id;
            displayEditBobotInfo();
        } else {
            // Jika tidak ada bobot nilai, tampilkan warning
            alert('Peringatan: Bobot nilai belum diatur untuk mata kuliah ini!');
        }

    } catch (error) {
        console.error('Error loading bobot nilai:', error);
    }
}

function displayEditBobotInfo() {
    if (!currentEditBobotNilai) return;

    // Display bobot
    document.getElementById('editBobotTugas').textContent = currentEditBobotNilai.tugas + '%';
    document.getElementById('editBobotQuiz').textContent = currentEditBobotNilai.quiz + '%';
    document.getElementById('editBobotUTS').textContent = currentEditBobotNilai.uts + '%';
    document.getElementById('editBobotUAS').textContent = currentEditBobotNilai.uas + '%';
    document.getElementById('editBobotKehadiran').textContent = currentEditBobotNilai.kehadiran + '%';
    document.getElementById('editBobotPraktikum').textContent = currentEditBobotNilai.praktikum + '%';

    // Label bobot di input
    document.getElementById('editLabelBobotTugas').textContent = `(${currentEditBobotNilai.tugas}%)`;
    document.getElementById('editLabelBobotQuiz').textContent = `(${currentEditBobotNilai.quiz}%)`;
    document.getElementById('editLabelBobotUTS').textContent = `(${currentEditBobotNilai.uts}%)`;
    document.getElementById('editLabelBobotUAS').textContent = `(${currentEditBobotNilai.uas}%)`;
    document.getElementById('editLabelBobotKehadiran').textContent = `(${currentEditBobotNilai.kehadiran}%)`;
    document.getElementById('editLabelBobotPraktikum').textContent = `(${currentEditBobotNilai.praktikum}%)`;
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
    currentEditBobotNilai = null;
}

// Fungsi untuk ADD modal
function hitungNilaiAkhir() {
    if (!currentBobotNilai) {
        console.warn('Bobot nilai belum tersedia');
        return;
    }

    const tugas = parseFloat(document.getElementById('addTugas').value) || 0;
    const quiz = parseFloat(document.getElementById('addQuiz').value) || 0;
    const uts = parseFloat(document.getElementById('addUts').value) || 0;
    const uas = parseFloat(document.getElementById('addUas').value) || 0;
    const kehadiran = parseFloat(document.getElementById('addKehadiran').value) || 0;
    const praktikum = parseFloat(document.getElementById('addPraktikum').value) || 0;

    // Hitung nilai akhir berdasarkan bobot
    const nilaiAkhir = (
        (tugas * currentBobotNilai.tugas / 100) +
        (quiz * currentBobotNilai.quiz / 100) +
        (uts * currentBobotNilai.uts / 100) +
        (uas * currentBobotNilai.uas / 100) +
        (kehadiran * currentBobotNilai.kehadiran / 100) +
        (praktikum * currentBobotNilai.praktikum / 100)
    );

    // Update nilai akhir
    document.getElementById('addNilaiAkhir').value = nilaiAkhir.toFixed(2);

    // Konversi ke grade
    const gradeInfo = convertToGrade(nilaiAkhir);
    
    if (gradeInfo) {
        document.getElementById('addNilaiHuruf').value = gradeInfo.grade;
        document.getElementById('addNilaiMutu').value = gradeInfo.grade_point;
        
        console.log('Grade Info:', {
            nilaiAkhir: nilaiAkhir.toFixed(2),
            grade: gradeInfo.grade,
            gradePoint: gradeInfo.grade_point
        });
    } else {
        // Jika tidak ada grade yang cocok, kosongkan
        document.getElementById('addNilaiHuruf').value = '';
        document.getElementById('addNilaiMutu').value = '';
        console.warn('Tidak ada grade yang cocok untuk nilai:', nilaiAkhir);
    }
}

// Fungsi untuk EDIT modal - BARU
function hitungNilaiAkhirEdit() {
    if (!currentEditBobotNilai) {
        console.warn('Bobot nilai belum tersedia untuk edit');
        return;
    }

    const tugas = parseFloat(document.getElementById('editTugas').value) || 0;
    const quiz = parseFloat(document.getElementById('editQuiz').value) || 0;
    const uts = parseFloat(document.getElementById('editUts').value) || 0;
    const uas = parseFloat(document.getElementById('editUas').value) || 0;
    const kehadiran = parseFloat(document.getElementById('editKehadiran').value) || 0;
    const praktikum = parseFloat(document.getElementById('editPraktikum').value) || 0;

    // Hitung nilai akhir berdasarkan bobot
    const nilaiAkhir = (
        (tugas * currentEditBobotNilai.tugas / 100) +
        (quiz * currentEditBobotNilai.quiz / 100) +
        (uts * currentEditBobotNilai.uts / 100) +
        (uas * currentEditBobotNilai.uas / 100) +
        (kehadiran * currentEditBobotNilai.kehadiran / 100) +
        (praktikum * currentEditBobotNilai.praktikum / 100)
    );

    // Update nilai akhir
    document.getElementById('editNilaiAkhir').value = nilaiAkhir.toFixed(2);

    // Konversi ke grade
    const gradeInfo = convertToGrade(nilaiAkhir);
    
    if (gradeInfo) {
        document.getElementById('editNilaiHuruf').value = gradeInfo.grade;
        document.getElementById('editNilaiMutu').value = gradeInfo.grade_point;
        
        console.log('Edit Grade Info:', {
            nilaiAkhir: nilaiAkhir.toFixed(2),
            grade: gradeInfo.grade,
            gradePoint: gradeInfo.grade_point
        });
    } else {
        // Jika tidak ada grade yang cocok, kosongkan
        document.getElementById('editNilaiHuruf').value = '';
        document.getElementById('editNilaiMutu').value = '';
        console.warn('Tidak ada grade yang cocok untuk nilai:', nilaiAkhir);
    }
}

// Convert nilai to grade - FIXED VERSION with proper sorting
function convertToGrade(nilai) {
    if (!currentGradeSystem || currentGradeSystem.length === 0) {
        console.warn('Grade system belum dimuat');
        return null;
    }
    
    // Round nilai to 2 decimal places untuk consistency
    const roundedNilai = Math.round(nilai * 100) / 100;
    
    // Sort grade system by min_score descending (dari A ke E)
    // Ini penting agar nilai tinggi dicek duluan
    const sortedGrades = [...currentGradeSystem].sort((a, b) => 
        parseFloat(b.min_score) - parseFloat(a.min_score)
    );
    
    // Cari grade yang sesuai dengan range
    for (let grade of sortedGrades) {
        const minScore = parseFloat(grade.min_score);
        const maxScore = parseFloat(grade.max_score);
        
        // Gunakan >= dan <= untuk range inclusive
        if (roundedNilai >= minScore && roundedNilai <= maxScore) {
            console.log('Grade found:', {
                nilai: roundedNilai,
                range: `${minScore}-${maxScore}`,
                grade: grade.grade,
                gradePoint: grade.grade_point
            });
            return {
                grade: grade.grade,
                grade_point: parseFloat(grade.grade_point),
                status_kelulusan: grade.status_kelulusan
            };
        }
    }
    
    console.warn('No matching grade found for nilai:', roundedNilai);
    console.log('Available grade ranges:', sortedGrades.map(g => ({
        grade: g.grade,
        range: `${g.min_score}-${g.max_score}`
    })));
    
    return null;
}

async function updateNilai() {
    const id = document.getElementById('editId').value;
    const krsDetailId = document.getElementById('editKrsDetailId').value;
    const bobotNilaiId = document.getElementById('editBobotNilaiId').value;
    const tugas = document.getElementById('editTugas').value;
    const quiz = document.getElementById('editQuiz').value;
    const uts = document.getElementById('editUts').value;
    const uas = document.getElementById('editUas').value;
    const kehadiran = document.getElementById('editKehadiran').value;
    const praktikum = document.getElementById('editPraktikum').value;
    const nilai_akhir = document.getElementById('editNilaiAkhir').value;
    const nilai_huruf = document.getElementById('editNilaiHuruf').value;
    const nilai_mutu = document.getElementById('editNilaiMutu').value;
    const status = document.getElementById('editStatus').value;
    
    if (!status) return alert("Status nilai harus diisi!");
    
    // Validasi nilai akhir sudah dihitung
    if (!nilai_akhir || !nilai_huruf || !nilai_mutu) {
        return alert("Nilai akhir, nilai huruf, dan nilai mutu harus terisi. Pastikan Anda sudah mengisi komponen nilai.");
    }

    try {
        const mutation = `
        mutation {
            updateNilai(id: ${id}, input: {
                krs_detail_id: ${krsDetailId}
                bobot_nilai_id: ${bobotNilaiId || 'null'}
                tugas: ${tugas || 'null'}
                quiz: ${quiz || 'null'}
                uts: ${uts || 'null'}
                uas: ${uas || 'null'}
                kehadiran: ${kehadiran || 'null'}
                praktikum: ${praktikum || 'null'}
                nilai_akhir: ${nilai_akhir || 'null'}
                nilai_huruf: ${nilai_huruf ? `"${nilai_huruf}"` : 'null'}
                nilai_mutu: ${nilai_mutu || 'null'}
                status: "${status}"
            }) {
                id
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