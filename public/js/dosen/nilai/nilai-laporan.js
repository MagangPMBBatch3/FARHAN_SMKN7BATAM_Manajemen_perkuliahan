let laporanData = null;

async function openLaporanModal() {
    document.getElementById('modalLaporan').classList.remove('hidden');
    await loadLaporanSemesterOptions();
    resetLaporanForm();
}

function closeLaporanModal() {
    document.getElementById('modalLaporan').classList.add('hidden');
    resetLaporanForm();
}

function resetLaporanForm() {
    document.getElementById('laporanSemester').value = '';
    document.getElementById('laporanKelas').value = '';
    document.getElementById('laporanKelas').disabled = true;
    document.getElementById('btnGenerateLaporan').disabled = true;
    
    document.getElementById('laporanInfoKelas').classList.add('hidden');
    document.getElementById('laporanStatistik').classList.add('hidden');
    document.getElementById('laporanDistribusi').classList.add('hidden');
    document.getElementById('laporanTabel').classList.add('hidden');
    document.getElementById('laporanEmptyState').classList.remove('hidden');
    
    laporanData = null;
}

async function loadLaporanSemesterOptions() {
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
        
        const select = document.getElementById('laporanSemester');
        select.innerHTML = '<option value="">Pilih Semester</option>';
        semesterList.forEach(s => {
            select.innerHTML += `<option value="${s.id}">${s.nama_semester} (${s.tahun_ajaran})</option>`;
        });
    } catch (error) {
        console.error('Error loading semester:', error);
    }
}

async function onLaporanSemesterChange() {
    const semesterId = document.getElementById('laporanSemester').value;
    const selectKelas = document.getElementById('laporanKelas');
    
    if (!semesterId) {
        selectKelas.disabled = true;
        selectKelas.innerHTML = '<option value="">Pilih semester terlebih dahulu</option>';
        document.getElementById('btnGenerateLaporan').disabled = true;
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
                    nama_mk
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
        
        // Enable button ketika kelas dipilih
        selectKelas.addEventListener('change', function() {
            document.getElementById('btnGenerateLaporan').disabled = !this.value;
        });

    } catch (error) {
        console.error('Error loading kelas:', error);
    }
}

async function generateLaporan() {
    const kelasId = document.getElementById('laporanKelas').value;
    
    if (!kelasId) {
        alert('Pilih kelas terlebih dahulu!');
        return;
    }

    document.getElementById('btnGenerateLaporan').disabled = true;
    document.getElementById('btnGenerateLaporan').innerHTML = '<svg class="animate-spin h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';

    try {
        const query = `
        query($kelasId: ID!) {
            kelas(id: $kelasId) {
                id
                kode_kelas
                nama_kelas
                mataKuliah {
                    kode_mk
                    nama_mk
                }
                dosen {
                    nama_lengkap
                }
            }
            nilaiByKelas(kelas_id: $kelasId) {
                id
                krsDetail {
                    krs {
                        mahasiswa {
                            nim
                            nama_lengkap
                        }
                    }
                }
                tugas
                quiz
                uts
                uas
                kehadiran
                praktikum
                nilai_akhir
                nilai_huruf
                nilai_mutu
                status
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { kelasId: parseInt(kelasId) }
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            alert('Gagal memuat data laporan');
            return;
        }

        laporanData = {
            kelas: result.data.kelas,
            nilaiList: result.data.nilaiByKelas || []
        };

        if (laporanData.nilaiList.length === 0) {
            alert('Belum ada data nilai untuk kelas ini');
            return;
        }

        renderLaporan();

    } catch (error) {
        console.error('Error generating laporan:', error);
        alert('Terjadi kesalahan saat generate laporan');
    } finally {
        document.getElementById('btnGenerateLaporan').disabled = false;
        document.getElementById('btnGenerateLaporan').innerHTML = '<svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>Generate Laporan';
    }
}

function renderLaporan() {
    if (!laporanData) return;

    // Hide empty state
    document.getElementById('laporanEmptyState').classList.add('hidden');

    // Show sections
    document.getElementById('laporanInfoKelas').classList.remove('hidden');
    document.getElementById('laporanStatistik').classList.remove('hidden');
    document.getElementById('laporanDistribusi').classList.remove('hidden');
    document.getElementById('laporanTabel').classList.remove('hidden');

    // Info Kelas
    document.getElementById('laporanNamaMK').textContent = laporanData.kelas.mataKuliah.nama_mk;
    document.getElementById('laporanKodeMK').textContent = laporanData.kelas.mataKuliah.kode_mk;
    document.getElementById('laporanDosen').textContent = laporanData.kelas.dosen.nama_lengkap;
    document.getElementById('laporanTotalMhs').textContent = laporanData.nilaiList.length + ' Mahasiswa';

    // Hitung Statistik
    const nilaiAkhirList = laporanData.nilaiList.map(n => parseFloat(n.nilai_akhir || 0));
    const nilaiMax = Math.max(...nilaiAkhirList);
    const nilaiMin = Math.min(...nilaiAkhirList);
    const nilaiAvg = (nilaiAkhirList.reduce((a, b) => a + b, 0) / nilaiAkhirList.length).toFixed(2);
    
    const lulus = laporanData.nilaiList.filter(n => 
        n.nilai_huruf && ['A', 'B', 'C'].includes(n.nilai_huruf)
    ).length;
    const tidakLulus = laporanData.nilaiList.length - lulus;

    document.getElementById('statNilaiMax').textContent = nilaiMax.toFixed(2);
    document.getElementById('statNilaiAvg').textContent = nilaiAvg;
    document.getElementById('statNilaiMin').textContent = nilaiMin.toFixed(2);
    document.getElementById('statLulus').textContent = lulus;
    document.getElementById('statTidakLulus').textContent = tidakLulus;

    // Distribusi Grade
    const gradeCount = {};
    laporanData.nilaiList.forEach(n => {
        const grade = n.nilai_huruf || '-';
        gradeCount[grade] = (gradeCount[grade] || 0) + 1;
    });

    const gradeDistDiv = document.getElementById('gradeDistribution');
    gradeDistDiv.innerHTML = '';
    
    const gradeColors = {
        'A': 'bg-green-100 border-green-300 text-green-800',
        'B': 'bg-blue-100 border-blue-300 text-blue-800',
        'C': 'bg-yellow-100 border-yellow-300 text-yellow-800',
        'D': 'bg-orange-100 border-orange-300 text-orange-800',
        'E': 'bg-red-100 border-red-300 text-red-800',
        '-': 'bg-gray-100 border-gray-300 text-gray-800'
    };

    Object.entries(gradeCount).sort().forEach(([grade, count]) => {
        const percentage = ((count / laporanData.nilaiList.length) * 100).toFixed(1);
        gradeDistDiv.innerHTML += `
            <div class="border-2 rounded-lg p-3 ${gradeColors[grade] || 'bg-gray-100 border-gray-300'}">
                <div class="text-center">
                    <p class="text-2xl font-bold">${grade}</p>
                    <p class="text-sm font-medium">${count} mahasiswa</p>
                    <p class="text-xs opacity-75">(${percentage}%)</p>
                </div>
            </div>
        `;
    });

    // Render Tabel
    const tbody = document.getElementById('laporanTableBody');
    tbody.innerHTML = '';

    laporanData.nilaiList.forEach((nilai, index) => {
        tbody.innerHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm text-gray-900">${index + 1}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900">${nilai.krsDetail.krs.mahasiswa.nim}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${nilai.krsDetail.krs.mahasiswa.nama_lengkap}</td>
                <td class="px-4 py-3 text-sm text-center text-gray-900">${nilai.tugas || '-'}</td>
                <td class="px-4 py-3 text-sm text-center text-gray-900">${nilai.quiz || '-'}</td>
                <td class="px-4 py-3 text-sm text-center text-gray-900">${nilai.uts || '-'}</td>
                <td class="px-4 py-3 text-sm text-center text-gray-900">${nilai.uas || '-'}</td>
                <td class="px-4 py-3 text-sm text-center text-gray-900">${nilai.kehadiran || '-'}</td>
                <td class="px-4 py-3 text-sm text-center text-gray-900">${nilai.praktikum || '-'}</td>
                <td class="px-4 py-3 text-sm text-center font-bold text-blue-700 bg-yellow-50">${nilai.nilai_akhir || '-'}</td>
                <td class="px-4 py-3 text-sm text-center bg-green-50">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${getNilaiHurufColor(nilai.nilai_huruf)}">
                        ${nilai.nilai_huruf || '-'}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-center font-semibold text-purple-700 bg-blue-50">${nilai.nilai_mutu || '-'}</td>
                <td class="px-4 py-3 text-sm text-center">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(nilai.status)}">
                        ${nilai.status}
                    </span>
                </td>
            </tr>
        `;
    });
}

function exportToExcel() {
    if (!laporanData) {
        alert('Tidak ada data untuk diexport');
        return;
    }

    // Prepare data for export
    let csvContent = "data:text/csv;charset=utf-8,";
    
    // Header info
    csvContent += `Laporan Nilai\n`;
    csvContent += `Mata Kuliah,${laporanData.kelas.mataKuliah.kode_mk} - ${laporanData.kelas.mataKuliah.nama_mk}\n`;
    csvContent += `Kelas,${laporanData.kelas.kode_kelas} - ${laporanData.kelas.nama_kelas}\n`;
    csvContent += `Dosen,${laporanData.kelas.dosen.nama_lengkap}\n`;
    csvContent += `\n`;
    
    // Table headers
    csvContent += "No,NIM,Nama,Tugas,Quiz,UTS,UAS,Kehadiran,Praktikum,Nilai Akhir,Grade,Mutu,Status\n";
    
    // Table data
    laporanData.nilaiList.forEach((nilai, index) => {
        csvContent += [
            index + 1,
            nilai.krsDetail.krs.mahasiswa.nim,
            `"${nilai.krsDetail.krs.mahasiswa.nama_lengkap}"`,
            nilai.tugas || '',
            nilai.quiz || '',
            nilai.uts || '',
            nilai.uas || '',
            nilai.kehadiran || '',
            nilai.praktikum || '',
            nilai.nilai_akhir || '',
            nilai.nilai_huruf || '',
            nilai.nilai_mutu || '',
            nilai.status
        ].join(',') + '\n';
    });

    // Create download link
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `Laporan_Nilai_${laporanData.kelas.kode_kelas}_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}