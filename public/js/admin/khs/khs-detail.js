async function openDetailModal(khsId) {
    document.getElementById('modalDetail').classList.remove('hidden');
    
    // Set tanggal cetak
    const now = new Date();
    document.getElementById('printDate').textContent = now.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    await loadKHSDetail(khsId);
}

function closeDetailModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}

async function loadKHSDetail(khsId) {
    try {
        // First get KHS data
        const khsQuery = `
        query($khsId: ID!) {
            khs(id: $khsId) {
                id
                mahasiswa {
                    id
                    nim
                    nama_lengkap
                    jurusan {
                        nama_jurusan
                    }
                }
                semester {
                    id
                    nama_semester
                    tahun_ajaran
                }
                sks_semester
                sks_kumulatif
                ip_semester
                ipk
            }
        }`;

        const khsResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: khsQuery,
                variables: { khsId: khsId.toString() }
            })
        });

        const khsResult = await khsResponse.json();
        
        if (khsResult.errors) {
            console.error('GraphQL Errors:', khsResult.errors);
            alert('Gagal memuat data KHS');
            closeDetailModal();
            return;
        }
        
        const khs = khsResult.data.khs;

        if (!khs) {
            alert('Data KHS tidak ditemukan');
            closeDetailModal();
            return;
        }

        // Display header info
        document.getElementById('detailNIM').textContent = khs.mahasiswa.nim;
        document.getElementById('detailNama').textContent = khs.mahasiswa.nama_lengkap;
        document.getElementById('detailProdi').textContent = khs.mahasiswa.jurusan.nama_jurusan;
        document.getElementById('detailSemester').textContent = `${khs.semester.nama_semester} - ${khs.semester.tahun_ajaran}`;
        document.getElementById('footerNama').textContent = khs.mahasiswa.nama_lengkap;

        // Get nilai detail
        const nilaiQuery = `
        query($mahasiswaId: ID!, $semesterId: ID!) {
            nilaiMahasiswaBySemester(mahasiswa_id: $mahasiswaId, semester_id: $semesterId) {
                id
                krsDetail {
                    mataKuliah {
                        kode_mk
                        nama_mk
                        sks
                    }
                }
                nilai_akhir
                nilai_huruf
                nilai_mutu
                status
            }
        }`;

        const nilaiResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: nilaiQuery,
                variables: { 
                    mahasiswaId: khs.mahasiswa.id.toString(),
                    semesterId: khs.semester.id.toString()
                }
            })
        });

        const nilaiResult = await nilaiResponse.json();
        const nilaiList = nilaiResult.data.nilaiMahasiswaBySemester || [];

        // Render table
        const tbody = document.getElementById('detailTableBody');
        tbody.innerHTML = '';

        let totalSKS = 0;
        let totalBobot = 0;

        nilaiList.forEach((nilai, index) => {
            const sks = nilai.krsDetail.mataKuliah.sks;
            const mutu = parseFloat(nilai.nilai_mutu || 0);
            const sksXBobot = sks * mutu;

            totalSKS += sks;
            totalBobot += sksXBobot;

            tbody.innerHTML += `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900">${index + 1}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">${nilai.krsDetail.mataKuliah.kode_mk}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${nilai.krsDetail.mataKuliah.nama_mk}</td>
                    <td class="px-4 py-3 text-sm text-center font-semibold text-gray-900">${sks}</td>
                    <td class="px-4 py-3 text-sm text-center text-gray-900">${nilai.nilai_akhir || '-'}</td>
                    <td class="px-4 py-3 text-sm text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${getGradeColorForDetail(nilai.nilai_huruf)}">
                            ${nilai.nilai_huruf || '-'}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-center font-semibold text-gray-900">${mutu.toFixed(2)}</td>
                    <td class="px-4 py-3 text-sm text-center font-bold text-blue-600">${sksXBobot.toFixed(2)}</td>
                </tr>
            `;
        });

        // Update totals
        document.getElementById('totalSKS').textContent = totalSKS;
        document.getElementById('totalBobot').textContent = totalBobot.toFixed(2);

        // Update summary
        document.getElementById('summarySksSemester').textContent = khs.sks_semester;
        document.getElementById('summaryIpSemester').textContent = parseFloat(khs.ip_semester).toFixed(2);
        document.getElementById('summarySksKumulatif').textContent = khs.sks_kumulatif;
        document.getElementById('summaryIPK').textContent = parseFloat(khs.ipk).toFixed(2);

        // Set predikat
        const ipk = parseFloat(khs.ipk);
        const { predikat, status, statusClass } = getPredikat(ipk);
        
        document.getElementById('predikat').textContent = predikat;
        document.getElementById('statusLulus').textContent = status;
        document.getElementById('statusLulus').className = `inline-block px-4 py-2 rounded-full text-sm font-semibold ${statusClass}`;

    } catch (error) {
        console.error('Error loading KHS detail:', error);
        alert('Gagal memuat detail KHS');
        closeDetailModal();
    }
}

function getGradeColorForDetail(grade) {
    const colors = {
        'A': 'bg-green-100 text-green-800 border border-green-300',
        'B': 'bg-blue-100 text-blue-800 border border-blue-300',
        'C': 'bg-yellow-100 text-yellow-800 border border-yellow-300',
        'D': 'bg-orange-100 text-orange-800 border border-orange-300',
        'E': 'bg-red-100 text-red-800 border border-red-300'
    };
    return colors[grade] || 'bg-gray-100 text-gray-800 border border-gray-300';
}

function getPredikat(ipk) {
    if (ipk >= 3.75) {
        return {
            predikat: 'Dengan Pujian (Cum Laude)',
            status: 'Lulus',
            statusClass: 'bg-green-500 text-white'
        };
    } else if (ipk >= 3.50) {
        return {
            predikat: 'Sangat Memuaskan',
            status: 'Lulus',
            statusClass: 'bg-blue-500 text-white'
        };
    } else if (ipk >= 3.00) {
        return {
            predikat: 'Memuaskan',
            status: 'Lulus',
            statusClass: 'bg-green-600 text-white'
        };
    } else if (ipk >= 2.75) {
        return {
            predikat: 'Cukup Memuaskan',
            status: 'Lulus',
            statusClass: 'bg-yellow-500 text-white'
        };
    } else if (ipk >= 2.00) {
        return {
            predikat: 'Kurang Memuaskan',
            status: 'Lulus Bersyarat',
            statusClass: 'bg-orange-500 text-white'
        };
    } else {
        return {
            predikat: 'Tidak Lulus',
            status: 'Tidak Lulus',
            statusClass: 'bg-red-500 text-white'
        };
    }
}

function printKHS() {
    window.print();
}