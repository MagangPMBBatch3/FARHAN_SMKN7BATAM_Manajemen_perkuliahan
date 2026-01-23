// File: public/js/admin/kelas/kelas-detail.js

const API_URL = "/graphql";
let currentKelasData = null;

async function loadKelasDetail(kelasId) {
    const query = `
    query {
        kelas(id: ${kelasId}) {
            id
            kode_kelas
            nama_kelas
            kapasitas
            kuota_terisi
            status
            created_at
            updated_at
            mataKuliah {
                id
                kode_mk
                nama_mk
                sks
                jurusan {
                    nama_jurusan
                }
            }
            dosen {
                id
                nama_lengkap
                nidn
                gelar_depan
                gelar_belakang
            }
            semester {
                id
                nama_semester
                kode_semester
                tahun_ajaran
            }
            jadwalKuliah {
                id
                hari
                jam_mulai
                jam_selesai
                keterangan
                ruangan {
                    nama_ruangan
                    gedung
                    lantai
                }
            }
            krsDetail {
                id
                krs {
                    mahasiswa {
                        id
                        nim
                        nama_lengkap
                        jurusan {
                            nama_jurusan
                        }
                    }
                    status
                }
            }
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();

        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            alert('Gagal memuat data kelas');
            return;
        }

        currentKelasData = result.data.kelas;
        renderKelasDetail(currentKelasData);
        renderJadwalKuliah(currentKelasData.jadwalKuliah);
        renderMahasiswaList(currentKelasData.krsDetail);
        loadPertemuanData(kelasId);

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data');
    }
}

function renderKelasDetail(kelas) {
    // Update title
    document.getElementById('kelasTitle').textContent = `${kelas.nama_kelas} - ${kelas.kode_kelas}`;

    // Informasi Umum
    document.getElementById('detailKodeKelas').textContent = kelas.kode_kelas;
    document.getElementById('detailNamaKelas').textContent = kelas.nama_kelas;
    
    // Mata Kuliah dengan info lengkap
    const mkInfo = `${kelas.mataKuliah.nama_mk} (${kelas.mataKuliah.kode_mk}) - ${kelas.mataKuliah.sks} SKS`;
    document.getElementById('detailMataKuliah').textContent = mkInfo;
    
    // Dosen dengan gelar
    let dosenName = kelas.dosen.nama_lengkap;
    if (kelas.dosen.gelar_depan) dosenName = `${kelas.dosen.gelar_depan} ${dosenName}`;
    if (kelas.dosen.gelar_belakang) dosenName = `${dosenName}, ${kelas.dosen.gelar_belakang}`;
    document.getElementById('detailDosen').textContent = `${dosenName} (NIDN: ${kelas.dosen.nidn})`;
    
    // Semester
    const semesterInfo = `${kelas.semester.nama_semester} - ${kelas.semester.tahun_ajaran}`;
    document.getElementById('detailSemester').textContent = semesterInfo;
    
    // Status dengan badge warna
    const statusEl = document.getElementById('detailStatus');
    statusEl.textContent = kelas.status;
    statusEl.className = 'text-lg font-semibold inline-flex items-center px-3 py-1 rounded-full text-sm';
    
    if (kelas.status === 'Aktif') {
        statusEl.classList.add('bg-green-100', 'text-green-800');
    } else if (kelas.status === 'Nonaktif') {
        statusEl.classList.add('bg-red-100', 'text-red-800');
    } else if (kelas.status === 'Selesai') {
        statusEl.classList.add('bg-gray-100', 'text-gray-800');
    }
    
    // Kapasitas dan Kuota
    document.getElementById('detailKapasitas').textContent = kelas.kapasitas;
    document.getElementById('detailKuotaTerisi').textContent = kelas.kuota_terisi;
    
    const sisaKuota = kelas.kapasitas - kelas.kuota_terisi;
    const sisaKuotaEl = document.getElementById('detailSisaKuota');
    sisaKuotaEl.textContent = sisaKuota;
    
    // Ubah warna berdasarkan sisa kuota
    const parentDiv = sisaKuotaEl.parentElement;
    if (sisaKuota <= 0) {
        parentDiv.className = 'bg-red-50 rounded-lg p-4';
        sisaKuotaEl.className = 'text-lg font-semibold text-red-900';
        parentDiv.querySelector('p').className = 'text-sm text-red-600 mb-1';
    } else if (sisaKuota <= 5) {
        parentDiv.className = 'bg-yellow-50 rounded-lg p-4';
        sisaKuotaEl.className = 'text-lg font-semibold text-yellow-900';
        parentDiv.querySelector('p').className = 'text-sm text-yellow-600 mb-1';
    }
}

function renderJadwalKuliah(jadwalList) {
    const tbody = document.getElementById('tableJadwal');
    tbody.innerHTML = '';

    if (!jadwalList || jadwalList.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                    Belum ada jadwal kuliah
                </td>
            </tr>
        `;
        return;
    }

    jadwalList.forEach(jadwal => {
        const ruanganInfo = jadwal.ruangan 
            ? `${jadwal.ruangan.nama_ruangan} - ${jadwal.ruangan.gedung} Lt.${jadwal.ruangan.lantai}`
            : '-';

        tbody.innerHTML += `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${jadwal.hari}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${jadwal.jam_mulai}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${jadwal.jam_selesai}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${ruanganInfo}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${jadwal.keterangan || '-'}</td>
            </tr>
        `;
    });
}

function renderMahasiswaList(krsDetailList) {
    const tbody = document.getElementById('tableMahasiswa');
    tbody.innerHTML = '';

    // Filter hanya KRS yang disetujui
    const approvedKrs = krsDetailList.filter(kd => kd.krs.status === 'Disetujui');

    document.getElementById('totalMahasiswa').textContent = approvedKrs.length;

    if (approvedKrs.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                    Belum ada mahasiswa terdaftar
                </td>
            </tr>
        `;
        return;
    }

    approvedKrs.forEach((kd, index) => {
        const mhs = kd.krs.mahasiswa;
        tbody.innerHTML += `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${mhs.nim}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${mhs.nama_lengkap}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${mhs.jurusan.nama_jurusan}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        ${kd.krs.status}
                    </span>
                </td>
            </tr>
        `;
    });
}

async function loadPertemuanData(kelasId) {
    const query = `
    query {
        allPertemuan(kelas_id: ${kelasId}) {
            id
            pertemuan_ke
            tanggal
            waktu_mulai
            waktu_selesai
            materi
            metode
            status_pertemuan
            ruangan {
                nama_ruangan
            }
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();

        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            renderPertemuanTable([]);
            return;
        }

        renderPertemuanTable(result.data.allPertemuan || []);

    } catch (error) {
        console.error('Error:', error);
        renderPertemuanTable([]);
    }
}

function renderPertemuanTable(pertemuanList) {
    const tbody = document.getElementById('tablePertemuan');
    tbody.innerHTML = '';

    if (!pertemuanList || pertemuanList.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    Belum ada pertemuan
                </td>
            </tr>
        `;
        return;
    }

    // Sort by pertemuan_ke
    pertemuanList.sort((a, b) => a.pertemuan_ke - b.pertemuan_ke);

    pertemuanList.forEach(p => {
        const statusClass = getStatusPertemuanClass(p.status_pertemuan);
        const waktu = `${p.waktu_mulai} - ${p.waktu_selesai}`;

        tbody.innerHTML += `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">
                    Pertemuan ${p.pertemuan_ke}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${formatDate(p.tanggal)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${waktu}</td>
                <td class="px-6 py-4 text-sm text-gray-900">${p.materi || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getMetodeClass(p.metode)}">
                        ${p.metode}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                        ${p.status_pertemuan}
                    </span>
                </td>
            </tr>
        `;
    });
}

function getStatusPertemuanClass(status) {
    const classes = {
        'Dijadwalkan': 'bg-blue-100 text-blue-800',
        'Berlangsung': 'bg-yellow-100 text-yellow-800',
        'Selesai': 'bg-green-100 text-green-800',
        'Dibatalkan': 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function getMetodeClass(metode) {
    const classes = {
        'Tatap Muka': 'bg-purple-100 text-purple-800',
        'Daring': 'bg-blue-100 text-blue-800',
        'Hybrid': 'bg-indigo-100 text-indigo-800'
    };
    return classes[metode] || 'bg-gray-100 text-gray-800';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    };
    return date.toLocaleDateString('id-ID', options);
}

function openEditModalFromDetail() {
    if (!currentKelasData) {
        alert('Data kelas belum dimuat');
        return;
    }

    openEditModal(
        currentKelasData.id,
        currentKelasData.kode_kelas,
        currentKelasData.nama_kelas,
        currentKelasData.mataKuliah.id,
        currentKelasData.dosen.id,
        currentKelasData.semester.id,
        currentKelasData.kapasitas,
        currentKelasData.kuota_terisi,
        currentKelasData.status
    );
}

// Override updateKelas untuk reload detail setelah update
const originalUpdateKelas = window.updateKelas;
window.updateKelas = async function() {
    await originalUpdateKelas();
    // Reload detail page
    const kelasId = window.location.pathname.split('/').pop();
    loadKelasDetail(kelasId);
};