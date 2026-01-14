let currentBobotNilai = null;
let currentGradeSystem = [];
let currentKelasData = null;

async function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
    await loadSemesterOptions();
    await loadGradeSystem();
    resetAddForm();
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    resetAddForm();
}

function resetAddForm() {
    document.getElementById('formAddNilai').reset();
    document.getElementById('addKelas').disabled = true;
    document.getElementById('addMahasiswa').disabled = true;
    document.getElementById('infoMataKuliah').classList.add('hidden');
    document.getElementById('addKrsDetailId').value = '';
    document.getElementById('addBobotNilaiId').value = '';
    currentBobotNilai = null;
    currentKelasData = null;
}

// Load Semester Options
async function loadSemesterOptions() {
    const query = `
    query {
        allSemester {
            id
            kode_semester
            nama_semester
            tahun_ajaran
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const semesterList = result.data.allSemester || [];
        
        const selectSemester = document.getElementById('addSemester');
        selectSemester.innerHTML = '<option value="">Pilih Semester</option>';
        semesterList.forEach(s => {
            selectSemester.innerHTML += `<option value="${s.id}">${s.nama_semester} (${s.tahun_ajaran})</option>`;
        });
    } catch (error) {
        console.error('Error loading semester:', error);
        alert('Gagal memuat data semester');
    }
}

// Load Grade System
async function loadGradeSystem() {
    const query = `
    query {
        allGradeSystem {
            id
            grade
            min_score
            max_score
            grade_point
            status_kelulusan
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        currentGradeSystem = result.data.allGradeSystem || [];
    } catch (error) {
        console.error('Error loading grade system:', error);
    }
}

// On Semester Change
async function onSemesterChange() {
    const semesterId = document.getElementById('addSemester').value;
    const selectKelas = document.getElementById('addKelas');
    
    if (!semesterId) {
        selectKelas.disabled = true;
        selectKelas.innerHTML = '<option value="">Pilih semester terlebih dahulu</option>';
        document.getElementById('addMahasiswa').disabled = true;
        return;
    }

    selectKelas.disabled = true;
    selectKelas.innerHTML = '<option value="">Loading kelas...</option>';

    try {
        // ✅ Change Int! to ID!
        const query = `
        query($semesterId: ID!) {
            kelasBySemester(semester_id: $semesterId) {
                id
                kode_kelas
                nama_kelas
                mataKuliah {
                    id
                    kode_mk
                    nama_mk
                    sks
                }
                dosen {
                    nama_lengkap
                }
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { semesterId: parseInt(semesterId) } // Can still pass as integer
            })
        });

        const result = await response.json();
        const kelasList = result.data.kelasBySemester || [];

        if (kelasList.length === 0) {
            selectKelas.innerHTML = '<option value="">Tidak ada kelas tersedia</option>';
            selectKelas.disabled = true;
            return;
        }

        selectKelas.innerHTML = '<option value="">Pilih Kelas</option>';
        kelasList.forEach(k => {
            selectKelas.innerHTML += `<option value="${k.id}">${k.kode_kelas} - ${k.nama_kelas} (${k.mataKuliah.nama_mk})</option>`;
        });
        selectKelas.disabled = false;

    } catch (error) {
        console.error('Error loading kelas:', error);
        selectKelas.innerHTML = '<option value="">Error loading data</option>';
        alert('Gagal memuat data kelas');
    }
}
async function onKelasChange() {
    const semesterId = document.getElementById('addSemester').value;
    const kelasId = document.getElementById('addKelas').value;
    const selectMahasiswa = document.getElementById('addMahasiswa');
    
    if (!kelasId) {
        selectMahasiswa.disabled = true;
        selectMahasiswa.innerHTML = '<option value="">Pilih kelas terlebih dahulu</option>';
        document.getElementById('infoMataKuliah').classList.add('hidden');
        return;
    }

    selectMahasiswa.disabled = true;
    selectMahasiswa.innerHTML = '<option value="">Loading mahasiswa...</option>';

    try {
        // First, get kelas info - ✅ Change Int! to ID!
        const kelasQuery = `
        query($kelasId: ID!) {
            kelas(id: $kelasId) {
                id
                mataKuliah {
                    id
                    kode_mk
                    nama_mk
                    sks
                }
            }
        }`;

        const kelasResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: kelasQuery,
                variables: { kelasId: parseInt(kelasId) }
            })
        });

        const kelasResult = await kelasResponse.json();
        currentKelasData = kelasResult.data.kelas;

        // Get bobot nilai - ✅ Change Int! to ID!
        const bobotQuery = `
        query($mataKuliahId: ID!, $semesterId: ID!) {
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

        const bobotResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: bobotQuery,
                variables: { 
                    mataKuliahId: parseInt(currentKelasData.mataKuliah.id),
                    semesterId: parseInt(semesterId)
                }
            })
        });

        const bobotResult = await bobotResponse.json();
        currentBobotNilai = bobotResult.data.bobotNilaiByMataKuliahSemester;

        if (!currentBobotNilai) {
            alert('Bobot nilai belum diatur untuk mata kuliah ini. Silakan hubungi administrator.');
            selectMahasiswa.disabled = true;
            return;
        }

        document.getElementById('addBobotNilaiId').value = currentBobotNilai.id;

        // Display info mata kuliah & bobot
        displayMataKuliahInfo();

        // Get mahasiswa di kelas ini - ✅ Change Int! to ID!
        const mahasiswaQuery = `
        query($kelasId: ID!) {
            krsDetailByKelas(kelas_id: $kelasId) {
                id
                krs {
                    id
                    mahasiswa {
                        id
                        nim
                        nama_lengkap
                    }
                    status
                }
                nilai {
                    id
                }
            }
        }`;

        const mahasiswaResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: mahasiswaQuery,
                variables: { kelasId: parseInt(kelasId) }
            })
        });

        const mahasiswaResult = await mahasiswaResponse.json();
        const krsDetailList = mahasiswaResult.data.krsDetailByKelas || [];

        // Filter: hanya mahasiswa yang KRS-nya disetujui dan belum ada nilai
        const availableKrsDetail = krsDetailList.filter(kd => 
            kd.krs.status === 'Disetujui' && !kd.nilai
        );

        if (availableKrsDetail.length === 0) {
            selectMahasiswa.innerHTML = '<option value="">Semua mahasiswa sudah dinilai</option>';
            selectMahasiswa.disabled = true;
            return;
        }

        selectMahasiswa.innerHTML = '<option value="">Pilih Mahasiswa</option>';
        availableKrsDetail.forEach(kd => {
            selectMahasiswa.innerHTML += `<option value="${kd.id}">${kd.krs.mahasiswa.nim} - ${kd.krs.mahasiswa.nama_lengkap}</option>`;
        });
        selectMahasiswa.disabled = false;

    } catch (error) {
        console.error('Error loading data:', error);
        selectMahasiswa.innerHTML = '<option value="">Error loading data</option>';
        alert('Gagal memuat data');
    }
}

// Display Mata Kuliah Info
function displayMataKuliahInfo() {
    if (!currentKelasData || !currentBobotNilai) return;

    document.getElementById('infoMataKuliah').classList.remove('hidden');
    
    // Info Mata Kuliah
    document.getElementById('infoKodeMK').textContent = currentKelasData.mataKuliah.kode_mk;
    document.getElementById('infoNamaMK').textContent = currentKelasData.mataKuliah.nama_mk;
    document.getElementById('infoSKS').textContent = currentKelasData.mataKuliah.sks + ' SKS';

    // Bobot
    document.getElementById('bobotTugas').textContent = currentBobotNilai.tugas + '%';
    document.getElementById('bobotQuiz').textContent = currentBobotNilai.quiz + '%';
    document.getElementById('bobotUTS').textContent = currentBobotNilai.uts + '%';
    document.getElementById('bobotUAS').textContent = currentBobotNilai.uas + '%';
    document.getElementById('bobotKehadiran').textContent = currentBobotNilai.kehadiran + '%';
    document.getElementById('bobotPraktikum').textContent = currentBobotNilai.praktikum + '%';

    // Label bobot di input
    document.getElementById('labelBobotTugas').textContent = `(${currentBobotNilai.tugas}%)`;
    document.getElementById('labelBobotQuiz').textContent = `(${currentBobotNilai.quiz}%)`;
    document.getElementById('labelBobotUTS').textContent = `(${currentBobotNilai.uts}%)`;
    document.getElementById('labelBobotUAS').textContent = `(${currentBobotNilai.uas}%)`;
    document.getElementById('labelBobotKehadiran').textContent = `(${currentBobotNilai.kehadiran}%)`;
    document.getElementById('labelBobotPraktikum').textContent = `(${currentBobotNilai.praktikum}%)`;
}

// On Mahasiswa Change
function onMahasiswaChangeImproved() {
    const krsDetailId = document.getElementById('addMahasiswa').value;
    document.getElementById('addKrsDetailId').value = krsDetailId;
}

// Hitung Nilai Akhir
function hitungNilaiAkhir() {
    if (!currentBobotNilai) return;

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

    document.getElementById('addNilaiAkhir').value = nilaiAkhir.toFixed(2);

    // Konversi ke grade
    const gradeInfo = convertToGrade(nilaiAkhir);
    if (gradeInfo) {
        document.getElementById('addNilaiHuruf').value = gradeInfo.grade;
        document.getElementById('addNilaiMutu').value = gradeInfo.grade_point;
    }
}

// Convert nilai to grade
function convertToGrade(nilai) {
    if (!currentGradeSystem.length) return null;
    
    for (let grade of currentGradeSystem) {
        if (nilai >= grade.min_score && nilai <= grade.max_score) {
            return grade;
        }
    }
    return null;
}

// Create Nilai
async function createNilai() {
    const krsDetailId = document.getElementById('addKrsDetailId').value;
    const bobotNilaiId = document.getElementById('addBobotNilaiId').value;
    const tugas = document.getElementById('addTugas').value;
    const quiz = document.getElementById('addQuiz').value;
    const uts = document.getElementById('addUts').value;
    const uas = document.getElementById('addUas').value;
    const kehadiran = document.getElementById('addKehadiran').value;
    const praktikum = document.getElementById('addPraktikum').value;
    const nilai_akhir = document.getElementById('addNilaiAkhir').value;
    const nilai_huruf = document.getElementById('addNilaiHuruf').value;
    const nilai_mutu = document.getElementById('addNilaiMutu').value;
    const status = document.getElementById('addStatus').value;

    if (!krsDetailId) return alert("Pilih mahasiswa terlebih dahulu!");
    if (!status) return alert("Status nilai harus diisi!");

    try {
        const mutation = `
        mutation {
            createNilai(input: {
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
            alert('Failed Save Data.... Maybe graphql error: ' + (result.errors[0]?.message || 'Unknown error'));
            return;
        }

        alert('Data nilai berhasil disimpan!');
        closeAddModal();
        loadNilaiData();
    } catch (error) {
        console.error('Error creating nilai:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
}