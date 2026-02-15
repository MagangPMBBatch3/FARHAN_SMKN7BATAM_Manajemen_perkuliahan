let bulkBobotNilai = null;
let bulkKelasData = null;
let bulkMahasiswaList = [];

async function openBulkModal() {
    document.getElementById('modalBulk').classList.remove('hidden');
    await loadBulkSemesterOptions();
    await loadGradeSystem();
    resetBulkForm();
}

function closeBulkModal() {
    document.getElementById('modalBulk').classList.add('hidden');
    resetBulkForm();
}

function resetBulkForm() {
    document.getElementById('bulkSemester').value = '';
    document.getElementById('bulkKelas').value = '';
    document.getElementById('bulkKelas').disabled = true;
    document.getElementById('bulkInfoBobot').classList.add('hidden');
    document.getElementById('bulkTableContainer').classList.add('hidden');
    document.getElementById('bulkEmptyState').classList.remove('hidden');
    document.getElementById('btnSaveBulk').disabled = true;
    bulkBobotNilai = null;
    bulkKelasData = null;
    bulkMahasiswaList = [];
}

async function loadBulkSemesterOptions() {
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
        
        const select = document.getElementById('bulkSemester');
        select.innerHTML = '<option value="">Pilih Semester</option>';
        semesterList.forEach(s => {
            select.innerHTML += `<option value="${s.id}">${s.nama_semester} (${s.tahun_ajaran})</option>`;
        });
    } catch (error) {
        console.error('Error loading semester:', error);
        alert('Gagal memuat data semester');
    }
}

async function onBulkSemesterChange() {
    const semesterId = document.getElementById('bulkSemester').value;
    const selectKelas = document.getElementById('bulkKelas');
    
    if (!semesterId) {
        selectKelas.disabled = true;
        selectKelas.innerHTML = '<option value="">Pilih semester terlebih dahulu</option>';
        return;
    }

    selectKelas.disabled = true;
    selectKelas.innerHTML = '<option value="">Loading...</option>';

    try {
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
                variables: { semesterId: parseInt(semesterId) }
            })
        });

        const result = await response.json();
        const kelasList = result.data.kelasBySemester || [];

        if (kelasList.length === 0) {
            selectKelas.innerHTML = '<option value="">Tidak ada kelas tersedia</option>';
            return;
        }

        selectKelas.innerHTML = '<option value="">Pilih Kelas</option>';
        kelasList.forEach(k => {
            selectKelas.innerHTML += `<option value="${k.id}">${k.kode_kelas} - ${k.nama_kelas}</option>`;
        });
        selectKelas.disabled = false;

    } catch (error) {
        console.error('Error loading kelas:', error);
        alert('Gagal memuat data kelas');
    }
}

async function loadBulkMahasiswaList() {
    const semesterId = document.getElementById('bulkSemester').value;
    const kelasId = document.getElementById('bulkKelas').value;
    
    if (!kelasId) {
        document.getElementById('bulkInfoBobot').classList.add('hidden');
        document.getElementById('bulkTableContainer').classList.add('hidden');
        document.getElementById('bulkEmptyState').classList.remove('hidden');
        return;
    }

    try {
        // Get kelas details
        const kelasQuery = `
        query($kelasId: ID!) {
            kelas(id: $kelasId) {
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

        const kelasResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: kelasQuery,
                variables: { kelasId: parseInt(kelasId) }
            })
        });

        const kelasResult = await kelasResponse.json();
        bulkKelasData = kelasResult.data.kelas;

        // Get bobot nilai
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
                    mataKuliahId: parseInt(bulkKelasData.mataKuliah.id),
                    semesterId: parseInt(semesterId)
                }
            })
        });

        const bobotResult = await bobotResponse.json();
        bulkBobotNilai = bobotResult.data.bobotNilaiByMataKuliahSemester;

        if (!bulkBobotNilai) {
            alert('Bobot nilai belum diatur untuk mata kuliah ini!');
            return;
        }

        // Display info
        displayBulkInfo();

        // Get mahasiswa
        const mahasiswaQuery = `
        query($kelasId: ID!) {
            krsDetailByKelas(kelas_id: $kelasId) {
                id
                krs {
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

        // Filter: KRS disetujui dan belum ada nilai
        bulkMahasiswaList = krsDetailList.filter(kd => 
            kd.krs.status === 'Disetujui' && !kd.nilai
        );

        if (bulkMahasiswaList.length === 0) {
            alert('Semua mahasiswa di kelas ini sudah dinilai atau tidak ada mahasiswa dengan KRS yang disetujui');
            return;
        }

        renderBulkTable();

    } catch (error) {
        console.error('Error loading data:', error);
        alert('Gagal memuat data');
    }
}

function displayBulkInfo() {
    document.getElementById('bulkInfoBobot').classList.remove('hidden');
    
    // Info Mata Kuliah
    document.getElementById('bulkKodeMK').textContent = bulkKelasData.mataKuliah.kode_mk;
    document.getElementById('bulkNamaMK').textContent = bulkKelasData.mataKuliah.nama_mk;
    document.getElementById('bulkSKS').textContent = bulkKelasData.mataKuliah.sks;
    document.getElementById('bulkDosen').textContent = bulkKelasData.dosen.nama_lengkap;

    // Bobot
    document.getElementById('bulkBobotTugas').textContent = bulkBobotNilai.tugas + '%';
    document.getElementById('bulkBobotQuiz').textContent = bulkBobotNilai.quiz + '%';
    document.getElementById('bulkBobotUTS').textContent = bulkBobotNilai.uts + '%';
    document.getElementById('bulkBobotUAS').textContent = bulkBobotNilai.uas + '%';
    document.getElementById('bulkBobotKehadiran').textContent = bulkBobotNilai.kehadiran + '%';
    document.getElementById('bulkBobotPraktikum').textContent = bulkBobotNilai.praktikum + '%';
}

function renderBulkTable() {
    const tbody = document.getElementById('bulkTableBody');
    tbody.innerHTML = '';

    bulkMahasiswaList.forEach((kd, index) => {
        const rowId = `row_${kd.id}`;
        tbody.innerHTML += `
            <tr id="${rowId}" class="hover:bg-gray-50">
                <td class="px-3 py-2 text-sm text-gray-900">${index + 1}</td>
                <td class="px-3 py-2 text-sm text-gray-900 font-medium">${kd.krs.mahasiswa.nim}</td>
                <td class="px-3 py-2 text-sm text-gray-900">${kd.krs.mahasiswa.nama_lengkap}</td>
                <td class="px-3 py-2">
                    <input type="number" 
                        id="tugas_${kd.id}" 
                        min="0" max="100" step="0.01"
                        oninput="calculateBulkRow(${kd.id})"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </td>
                <td class="px-3 py-2">
                    <input type="number" 
                        id="quiz_${kd.id}" 
                        min="0" max="100" step="0.01"
                        oninput="calculateBulkRow(${kd.id})"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </td>
                <td class="px-3 py-2">
                    <input type="number" 
                        id="uts_${kd.id}" 
                        min="0" max="100" step="0.01"
                        oninput="calculateBulkRow(${kd.id})"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </td>
                <td class="px-3 py-2">
                    <input type="number" 
                        id="uas_${kd.id}" 
                        min="0" max="100" step="0.01"
                        oninput="calculateBulkRow(${kd.id})"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </td>
                <td class="px-3 py-2">
                    <input type="number" 
                        id="kehadiran_${kd.id}" 
                        min="0" max="100" step="0.01"
                        oninput="calculateBulkRow(${kd.id})"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </td>
                <td class="px-3 py-2">
                    <input type="number" 
                        id="praktikum_${kd.id}" 
                        min="0" max="100" step="0.01"
                        oninput="calculateBulkRow(${kd.id})"
                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="0">
                </td>
                <td class="px-3 py-2 bg-yellow-50">
                    <input type="number" 
                        id="nilai_akhir_${kd.id}" 
                        readonly
                        class="w-full px-2 py-1 bg-yellow-100 border border-yellow-300 rounded text-sm text-center font-bold text-blue-700"
                        placeholder="0.00">
                </td>
                <td class="px-3 py-2 bg-green-50">
                    <input type="text" 
                        id="nilai_huruf_${kd.id}" 
                        readonly
                        class="w-full px-2 py-1 bg-green-100 border border-green-300 rounded text-sm text-center font-bold text-green-700"
                        placeholder="-">
                </td>
                <td class="px-3 py-2 bg-blue-50">
                    <input type="number" 
                        id="nilai_mutu_${kd.id}" 
                        readonly
                        class="w-full px-2 py-1 bg-blue-100 border border-blue-300 rounded text-sm text-center font-bold text-purple-700"
                        placeholder="0.00">
                </td>
            </tr>
        `;
    });

    document.getElementById('bulkTableContainer').classList.remove('hidden');
    document.getElementById('bulkEmptyState').classList.add('hidden');
    document.getElementById('bulkTotalMahasiswa').textContent = bulkMahasiswaList.length;
    document.getElementById('btnSaveBulk').disabled = false;
}

function calculateBulkRow(krsDetailId) {
    if (!bulkBobotNilai) return;

    const tugas = parseFloat(document.getElementById(`tugas_${krsDetailId}`).value) || 0;
    const quiz = parseFloat(document.getElementById(`quiz_${krsDetailId}`).value) || 0;
    const uts = parseFloat(document.getElementById(`uts_${krsDetailId}`).value) || 0;
    const uas = parseFloat(document.getElementById(`uas_${krsDetailId}`).value) || 0;
    const kehadiran = parseFloat(document.getElementById(`kehadiran_${krsDetailId}`).value) || 0;
    const praktikum = parseFloat(document.getElementById(`praktikum_${krsDetailId}`).value) || 0;

    const nilaiAkhir = (
        (tugas * bulkBobotNilai.tugas / 100) +
        (quiz * bulkBobotNilai.quiz / 100) +
        (uts * bulkBobotNilai.uts / 100) +
        (uas * bulkBobotNilai.uas / 100) +
        (kehadiran * bulkBobotNilai.kehadiran / 100) +
        (praktikum * bulkBobotNilai.praktikum / 100)
    );

    document.getElementById(`nilai_akhir_${krsDetailId}`).value = nilaiAkhir.toFixed(2);

    const gradeInfo = convertToGrade(nilaiAkhir);
    if (gradeInfo) {
        document.getElementById(`nilai_huruf_${krsDetailId}`).value = gradeInfo.grade;
        document.getElementById(`nilai_mutu_${krsDetailId}`).value = gradeInfo.grade_point;
    }
}

async function saveBulkNilai() {
    if (!confirm(`Simpan nilai untuk ${bulkMahasiswaList.length} mahasiswa?`)) return;

    const mutations = [];
    let successCount = 0;
    let errorCount = 0;

    document.getElementById('btnSaveBulk').disabled = true;
    document.getElementById('btnSaveBulk').innerHTML = '<svg class="animate-spin h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Menyimpan...';

    for (const kd of bulkMahasiswaList) {
        const tugas = document.getElementById(`tugas_${kd.id}`).value || null;
        const quiz = document.getElementById(`quiz_${kd.id}`).value || null;
        const uts = document.getElementById(`uts_${kd.id}`).value || null;
        const uas = document.getElementById(`uas_${kd.id}`).value || null;
        const kehadiran = document.getElementById(`kehadiran_${kd.id}`).value || null;
        const praktikum = document.getElementById(`praktikum_${kd.id}`).value || null;
        const nilai_akhir = document.getElementById(`nilai_akhir_${kd.id}`).value || null;
        const nilai_huruf = document.getElementById(`nilai_huruf_${kd.id}`).value || null;
        const nilai_mutu = document.getElementById(`nilai_mutu_${kd.id}`).value || null;

        try {
            const mutation = `
            mutation {
                createNilai(input: {
                    krs_detail_id: ${kd.id}
                    bobot_nilai_id: ${bulkBobotNilai.id}
                    tugas: ${tugas}
                    quiz: ${quiz}
                    uts: ${uts}
                    uas: ${uas}
                    kehadiran: ${kehadiran}
                    praktikum: ${praktikum}
                    nilai_akhir: ${nilai_akhir}
                    nilai_huruf: ${nilai_huruf ? `"${nilai_huruf}"` : 'null'}
                    nilai_mutu: ${nilai_mutu}
                    status: "Draft"
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
                console.error(`Error for ${kd.krs.mahasiswa.nim}:`, result.errors);
                errorCount++;
            } else {
                successCount++;
            }
        } catch (error) {
            console.error(`Error saving nilai for ${kd.krs.mahasiswa.nim}:`, error);
            errorCount++;
        }
    }

    document.getElementById('btnSaveBulk').disabled = false;
    document.getElementById('btnSaveBulk').innerHTML = '<svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Simpan Semua Nilai';

    alert(`Berhasil: ${successCount} mahasiswa\nGagal: ${errorCount} mahasiswa`);
    
    if (successCount > 0) {
        closeBulkModal();
        loadNilaiData();
    }
}