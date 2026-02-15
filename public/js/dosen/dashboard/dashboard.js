const API_URL = "/graphql";

async function loadDashboardData() {
    try {
        // Query untuk mendapatkan data dosen yang sedang login
        const query = `
        query {
            currentDosen {
                id
                nama_lengkap
                kelasDiampu {
                    id
                    kode_kelas
                    nama_kelas
                    mataKuliah {
                        id
                        kode_mk
                        nama_mk
                    }
                }
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const dosen = result.data.currentDosen;
        
        if (!dosen) {
            console.error('Data dosen tidak ditemukan');
            return;
        }

        // Hitung statistik
        const kelasUnik = dosen.kelasDiampu || [];
        const mataKuliahUnik = [...new Set(kelasUnik.map(k => k.mataKuliah.id))];
        
        document.getElementById('totalKelas').textContent = kelasUnik.length;
        document.getElementById('totalMataKuliah').textContent = mataKuliahUnik.length;

        // Load pertemuan aktif
        await loadPertemuanAktif(kelasUnik.map(k => k.id));
        
        // Load nilai tertunda
        await loadNilaiTertunda(kelasUnik.map(k => k.id));
        
        // Load jadwal minggu ini
        await loadJadwalMingguIni(kelasUnik.map(k => k.id));
        
        // Load tugas cepat
        await loadTugasCepat(kelasUnik.map(k => k.id));

    } catch (error) {
        console.error('Error loading dashboard data:', error);
    }
}

async function loadPertemuanAktif(kelasIds) {
    try {
        const query = `
        query($kelasIds: [ID!]) {
            pertemuanByKelas(kelas_ids: $kelasIds, status: [Dijadwalkan, Berlangsung]) {
                id
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { kelasIds }
            })
        });

        const result = await response.json();
        const pertemuan = result.data.pertemuanByKelas || [];
        document.getElementById('totalPertemuan').textContent = pertemuan.length;

    } catch (error) {
        console.error('Error loading pertemuan:', error);
        document.getElementById('totalPertemuan').textContent = '0';
    }
}

async function loadNilaiTertunda(kelasIds) {
    try {
        const query = `
        query($kelasIds: [ID!]) {
            nilaiByKelas(kelas_ids: $kelasIds, status: [Draft, Pending]) {
                id
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { kelasIds }
            })
        });

        const result = await response.json();
        const nilai = result.data.nilaiByKelas || [];
        document.getElementById('totalNilaiTertunda').textContent = nilai.length;

    } catch (error) {
        console.error('Error loading nilai tertunda:', error);
        document.getElementById('totalNilaiTertunda').textContent = '0';
    }
}

async function loadJadwalMingguIni(kelasIds) {
    try {
        const today = new Date();
        const dayOfWeek = today.getDay(); // 0 = Minggu, 1 = Senin, dst
        
        const query = `
        query($kelasIds: [ID!]) {
            jadwalByKelas(kelas_ids: $kelasIds) {
                id
                kelas {
                    kode_kelas
                    nama_kelas
                    mataKuliah {
                        nama_mk
                    }
                }
                hari
                jam_mulai
                jam_selesai
                ruangan {
                    nama_ruangan
                }
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { kelasIds }
            })
        });

        const result = await response.json();
        const jadwalList = result.data.jadwalByKelas || [];
        
        const container = document.getElementById('jadwalMingguIni');
        container.innerHTML = '';

        if (jadwalList.length === 0) {
            container.innerHTML = '<div class="text-center text-gray-500 py-4">Tidak ada jadwal minggu ini</div>';
            return;
        }

        // Filter jadwal minggu ini (opsional, bisa ditampilkan semua)
        const jadwalMingguIni = jadwalList.slice(0, 5); // Tampilkan 5 jadwal terdekat

        jadwalMingguIni.forEach(jadwal => {
            const isToday = isHariIni(jadwal.hari);
            const statusClass = isToday ? 'text-emerald-600 bg-emerald-50' : 'text-sky-600 bg-sky-50';
            const statusText = isToday ? 'Hari Ini' : 'Minggu Ini';

            container.innerHTML += `
                <div class="flex items-center justify-between border border-gray-100 rounded-xl p-4">
                    <div>
                        <p class="font-semibold text-gray-800">${jadwal.kelas.mataKuliah.nama_mk}</p>
                        <p class="text-xs text-gray-500">
                            ${jadwal.hari}, ${jadwal.jam_mulai} - ${jadwal.jam_selesai} • ${jadwal.ruangan.nama_ruangan}
                        </p>
                    </div>
                    <span class="text-xs font-semibold ${statusClass} px-3 py-1 rounded-full">
                        ${statusText}
                    </span>
                </div>
            `;
        });

    } catch (error) {
        console.error('Error loading jadwal:', error);
        document.getElementById('jadwalMingguIni').innerHTML = 
            '<div class="text-center text-gray-500 py-4">Gagal memuat jadwal</div>';
    }
}

async function loadTugasCepat(kelasIds) {
    try {
        // Ini bisa disesuaikan dengan kebutuhan spesifik
        const tugas = [
            {
                text: 'Input nilai yang belum lengkap',
                deadline: 'Segera',
                color: 'text-rose-500'
            },
            {
                text: 'Verifikasi presensi pertemuan',
                deadline: '2 hari',
                color: 'text-amber-500'
            },
            {
                text: 'Upload materi pertemuan berikutnya',
                deadline: 'Minggu ini',
                color: 'text-emerald-500'
            }
        ];

        const container = document.getElementById('tugasCepat');
        container.innerHTML = '';

        tugas.forEach(t => {
            container.innerHTML += `
                <li class="flex items-center justify-between">
                    <span>${t.text}</span>
                    <span class="text-xs ${t.color} font-semibold">${t.deadline}</span>
                </li>
            `;
        });

    } catch (error) {
        console.error('Error loading tugas cepat:', error);
    }
}

function isHariIni(hari) {
    const hariMap = {
        0: 'Minggu',
        1: 'Senin',
        2: 'Selasa',
        3: 'Rabu',
        4: 'Kamis',
        5: 'Jumat',
        6: 'Sabtu'
    };
    const today = new Date().getDay();
    return hariMap[today] === hari;
}

document.addEventListener('DOMContentLoaded', () => {
    loadDashboardData();
});