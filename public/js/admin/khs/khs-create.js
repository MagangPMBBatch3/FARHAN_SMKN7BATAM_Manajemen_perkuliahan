let currentMahasiswaData = null;
let currentNilaiData = [];

async function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
    await loadAngkatanOptions();
    resetAddForm();
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    resetAddForm();
}

function resetAddForm() {
    document.getElementById('formAddKhs').reset();
    document.getElementById('addMahasiswaId').disabled = true;
    document.getElementById('addSemesterId').disabled = true;
    document.getElementById('infoMahasiswa').classList.add('hidden');
    document.getElementById('hasilKHS').classList.add('hidden');
    document.getElementById('loadingKHS').classList.add('hidden');
    document.getElementById('btnSaveKHS').disabled = true;
    currentMahasiswaData = null;
    currentNilaiData = [];
}

// Load Angkatan Options
async function loadAngkatanOptions() {
    const query = `
    query {
        allMahasiswa {
            angkatan
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const mahasiswaList = result.data.allMahasiswa || [];
        
        // Get unique angkatan
        const angkatanSet = new Set(mahasiswaList.map(m => m.angkatan));
        const angkatanList = Array.from(angkatanSet).sort((a, b) => b - a);

        const select = document.getElementById('addAngkatan');
        select.innerHTML = '<option value="">Pilih Angkatan</option>';
        angkatanList.forEach(angkatan => {
            select.innerHTML += `<option value="${angkatan}">${angkatan}</option>`;
        });
    } catch (error) {
        console.error('Error loading angkatan:', error);
    }
}

// Load Mahasiswa by Angkatan
async function loadMahasiswaByAngkatan() {
    const angkatan = document.getElementById('addAngkatan').value;
    const selectMahasiswa = document.getElementById('addMahasiswaId');
    
    if (!angkatan) {
        selectMahasiswa.disabled = true;
        selectMahasiswa.innerHTML = '<option value="">Pilih angkatan terlebih dahulu</option>';
        document.getElementById('addSemesterId').disabled = true;
        return;
    }

    selectMahasiswa.disabled = true;
    selectMahasiswa.innerHTML = '<option value="">Loading...</option>';

    try {
        const query = `
        query($angkatan: String!) {
            mahasiswaByAngkatan(angkatan: $angkatan) {
                id
                nim
                nama_lengkap
                angkatan
                jurusan {
                    nama_jurusan
                }
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { angkatan: angkatan }
            })
        });

        const result = await response.json();
        const mahasiswaList = result.data.mahasiswaByAngkatan || [];

        if (mahasiswaList.length === 0) {
            selectMahasiswa.innerHTML = '<option value="">Tidak ada mahasiswa</option>';
            return;
        }

        selectMahasiswa.innerHTML = '<option value="">Pilih Mahasiswa</option>';
        mahasiswaList.forEach(m => {
            selectMahasiswa.innerHTML += `<option value="${m.id}" data-mahasiswa='${JSON.stringify(m)}'>${m.nim} - ${m.nama_lengkap}</option>`;
        });
        selectMahasiswa.disabled = false;

    } catch (error) {
        console.error('Error loading mahasiswa:', error);
        selectMahasiswa.innerHTML = '<option value="">Error loading data</option>';
    }
}

// On Mahasiswa Selected
async function onMahasiswaSelected() {
    const select = document.getElementById('addMahasiswaId');
    const selectedOption = select.options[select.selectedIndex];
    
    if (!selectedOption.value) {
        document.getElementById('infoMahasiswa').classList.add('hidden');
        document.getElementById('addSemesterId').disabled = true;
        return;
    }
    document.getElementById("addSemesterId").disabled = false;

    currentMahasiswaData = JSON.parse(selectedOption.getAttribute('data-mahasiswa'));
    
    // Display info mahasiswa
    document.getElementById('infoNIM').textContent = currentMahasiswaData.nim;
    document.getElementById('infoNama').textContent = currentMahasiswaData.nama_lengkap;
    document.getElementById('infoJurusan').textContent = currentMahasiswaData.jurusan.nama_jurusan;
    document.getElementById('infoAngkatan').textContent = currentMahasiswaData.angkatan;
    document.getElementById('infoMahasiswa').classList.remove('hidden');

    // Load semester options
    await loadSemesterOptions();
}

// Load Semester Options
async function loadSemesterOptions() {
    const query = `
    query {
        allSemester {
            id
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

        const select = document.getElementById('addSemesterId');
        select.innerHTML = '<option value="">Pilih Semester</option>';
        semesterList.forEach(s => {
            select.innerHTML += `<option value="${s.id}">${s.nama_semester} (${s.tahun_ajaran})</option>`;
        });
        select.disabled = false;

    } catch (error) {
        console.error('Error loading semester:', error);
    }
}

// Calculate KHS
async function calculateKHS() {
    const mahasiswaId = document.getElementById('addMahasiswaId').value;
    const semesterId = document.getElementById('addSemesterId').value;

    if (!mahasiswaId || !semesterId) {
        document.getElementById('hasilKHS').classList.add('hidden');
        return;
    }

    // Show loading
    document.getElementById('loadingKHS').classList.remove('hidden');
    document.getElementById('hasilKHS').classList.add('hidden');

    try {
        // Get nilai mahasiswa untuk semester ini dan sebelumnya
        const query = `
        query($mahasiswaId: ID!, $semesterId: ID!) {
            nilaiMahasiswaBySemester(mahasiswa_id: $mahasiswaId, semester_id: $semesterId) {
                id
                krsDetail {
                    mataKuliah {
                        nama_mk
                        kode_mk
                        sks
                    }
                    kelas {
                        semester {
                            id
                        }
                    }
                }
                nilai_akhir
                nilai_huruf
                nilai_mutu
                status
            }
            nilaiMahasiswaKumulatif(mahasiswa_id: $mahasiswaId, semester_id: $semesterId) {
                id
                krsDetail {
                    mataKuliah {
                        sks
                    }
                }
                nilai_mutu
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { 
                    mahasiswaId: mahasiswaId.toString(),
                    semesterId: semesterId.toString()
                }
            })
        });

        const result = await response.json();

        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            alert('Gagal menghitung KHS: ' + result.errors[0].message);
            return;
        }

        const nilaiSemester = result.data.nilaiMahasiswaBySemester || [];
        const nilaiKumulatif = result.data.nilaiMahasiswaKumulatif || [];

        if (nilaiSemester.length === 0) {
            alert('Belum ada nilai untuk semester ini. Silakan input nilai terlebih dahulu.');
            document.getElementById('loadingKHS').classList.add('hidden');
            return;
        }

        // Calculate IP Semester
        let totalBobotSemester = 0;
        let totalSksSemester = 0;

        const tableBody = document.getElementById('tableDetailMK');
        tableBody.innerHTML = '';

        nilaiSemester.forEach(nilai => {
            const sks = nilai.krsDetail.mataKuliah.sks;
            const mutu = parseFloat(nilai.nilai_mutu || 0);
            
            totalSksSemester += sks;
            totalBobotSemester += (mutu * sks);

            tableBody.innerHTML += `
                <tr>
                    <td class="px-2 py-1 text-left">${nilai.krsDetail.mataKuliah.kode_mk} - ${nilai.krsDetail.mataKuliah.nama_mk}</td>
                    <td class="px-2 py-1 text-center">${sks}</td>
                    <td class="px-2 py-1 text-center">
                        <span class="px-2 py-0.5 text-xs font-semibold rounded ${getGradeColor(nilai.nilai_huruf)}">
                            ${nilai.nilai_huruf || '-'}
                        </span>
                    </td>
                    <td class="px-2 py-1 text-center font-semibold">${mutu.toFixed(2)}</td>
                </tr>
            `;
        });

        const ipSemester = totalSksSemester > 0 ? (totalBobotSemester / totalSksSemester) : 0;

        // Calculate IPK (Kumulatif)
        let totalBobotKumulatif = 0;
        let totalSksKumulatif = 0;

        nilaiKumulatif.forEach(nilai => {
            const sks = nilai.krsDetail.mataKuliah.sks;
            const mutu = parseFloat(nilai.nilai_mutu || 0);
            
            totalSksKumulatif += sks;
            totalBobotKumulatif += (mutu * sks);
        });

        const ipk = totalSksKumulatif > 0 ? (totalBobotKumulatif / totalSksKumulatif) : 0;

        // Display results
        document.getElementById('resultSksSemester').textContent = totalSksSemester;
        document.getElementById('resultIpSemester').textContent = ipSemester.toFixed(2);
        document.getElementById('resultSksKumulatif').textContent = totalSksKumulatif;
        document.getElementById('resultIPK').textContent = ipk.toFixed(2);

        // Set hidden inputs
        document.getElementById('calculatedSksSemester').value = totalSksSemester;
        document.getElementById('calculatedSksKumulatif').value = totalSksKumulatif;
        document.getElementById('calculatedIpSemester').value = ipSemester.toFixed(2);
        document.getElementById('calculatedIPK').value = ipk.toFixed(2);

        // Hide loading, show results
        document.getElementById('loadingKHS').classList.add('hidden');
        document.getElementById('hasilKHS').classList.remove('hidden');
        document.getElementById('btnSaveKHS').disabled = false;

        currentNilaiData = nilaiSemester;

    } catch (error) {
        console.error('Error calculating KHS:', error);
        alert('Terjadi kesalahan saat menghitung KHS');
        document.getElementById('loadingKHS').classList.add('hidden');
    }
}

function getGradeColor(grade) {
    const colors = {
        'A': 'bg-green-100 text-green-800',
        'B': 'bg-blue-100 text-blue-800',
        'C': 'bg-yellow-100 text-yellow-800',
        'D': 'bg-orange-100 text-orange-800',
        'E': 'bg-red-100 text-red-800'
    };
    return colors[grade] || 'bg-gray-100 text-gray-800';
}

// Generate/Save KHS
async function generateKhs() {
    const mahasiswaId = document.getElementById('addMahasiswaId').value;
    const semesterId = document.getElementById('addSemesterId').value;
    const sksSemester = document.getElementById('calculatedSksSemester').value;
    const sksKumulatif = document.getElementById('calculatedSksKumulatif').value;
    const ipSemester = document.getElementById('calculatedIpSemester').value;
    const ipk = document.getElementById('calculatedIPK').value;

    if (!mahasiswaId || !semesterId) {
        alert('Pilih mahasiswa dan semester terlebih dahulu!');
        return;
    }

    const mutation = `
    mutation {
        createKhs(input: {
            mahasiswa_id: ${mahasiswaId}
            semester_id: ${semesterId}
            sks_semester: ${sksSemester}
            sks_kumulatif: ${sksKumulatif}
            ip_semester: ${ipSemester}
            ipk: ${ipk}
        }) {
            id
            mahasiswa_id
            semester_id
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
            console.error('GraphQL Errors:', result.errors);
            alert('Gagal menyimpan KHS: ' + result.errors[0].message);
            return;
        }

        alert('KHS berhasil disimpan!');
        closeAddModal();
        loadKhsData(currentPageAktif, currentPageArsip);

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan KHS');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Initial load can be added here if needed
});