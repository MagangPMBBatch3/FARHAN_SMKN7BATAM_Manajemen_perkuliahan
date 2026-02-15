const API_URL = "/graphql";
let currentPage = 1;

async function loadJadwalData(page = 1) {
    currentPage = page;
    
    const perPage = parseInt(document.getElementById("perPage")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";
    const filterHari = document.getElementById("filterHari")?.value || "";

    showTableLoading('dataJadwal', 6, 10);

    try {
        // Query untuk mendapatkan jadwal dosen yang sedang login
        const query = `
        query($first: Int, $page: Int, $search: String, $hari: String) {
            currentDosenJadwal(first: $first, page: $page, search: $search, hari: $hari) {
                data {
                    id
                    kelas {
                        id
                        kode_kelas
                        nama_kelas
                        mataKuliah {
                            kode_mk
                            nama_mk
                        }
                    }
                    ruangan {
                        kode_ruangan
                        nama_ruangan
                    }
                    hari
                    jam_mulai
                    jam_selesai
                    keterangan
                }
                paginatorInfo {
                    currentPage
                    lastPage
                    total
                    hasMorePages
                    perPage
                }
            }
        }`;

        const variables = { 
            first: perPage, 
            page: page, 
            search: searchValue,
            hari: filterHari || null
        };

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query, variables })
        });

        const result = await response.json();

        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            alert('Gagal memuat data jadwal');
            return;
        }

        const data = result.data.currentDosenJadwal.data || [];
        const pageInfo = result.data.currentDosenJadwal.paginatorInfo;

        renderJadwalTable(data);
        updatePagination(pageInfo);

    } catch (error) {
        console.error('Error loading jadwal:', error);
        alert('Terjadi kesalahan saat memuat data');
    }
}

function renderJadwalTable(jadwalList) {
    const tbody = document.getElementById('dataJadwal');
    tbody.innerHTML = '';

    if (!jadwalList.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-gray-500 p-4">Tidak ada data jadwal</td>
            </tr>
        `;
        return;
    }

    jadwalList.forEach(jadwal => {
        const hariClass = getHariColor(jadwal.hari);
        
        tbody.innerHTML += `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <div class="font-semibold text-gray-800">${jadwal.kelas.mataKuliah.nama_mk}</div>
                    <div class="text-xs text-gray-500">${jadwal.kelas.mataKuliah.kode_mk}</div>
                </td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-700">${jadwal.kelas.kode_kelas}</div>
                    <div class="text-xs text-gray-500">${jadwal.kelas.nama_kelas}</div>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${hariClass}">
                        ${jadwal.hari}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">
                    ${jadwal.jam_mulai} - ${jadwal.jam_selesai}
                </td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-700">${jadwal.ruangan.nama_ruangan}</div>
                    <div class="text-xs text-gray-500">${jadwal.ruangan.kode_ruangan}</div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    ${jadwal.keterangan || '-'}
                </td>
            </tr>
        `;
    });
}

function getHariColor(hari) {
    const colors = {
        'Senin': 'bg-blue-100 text-blue-800',
        'Selasa': 'bg-green-100 text-green-800',
        'Rabu': 'bg-yellow-100 text-yellow-800',
        'Kamis': 'bg-purple-100 text-purple-800',
        'Jumat': 'bg-pink-100 text-pink-800',
        'Sabtu': 'bg-orange-100 text-orange-800',
        'Minggu': 'bg-red-100 text-red-800'
    };
    return colors[hari] || 'bg-gray-100 text-gray-800';
}

function updatePagination(pageInfo) {
    if (pageInfo) {
        document.getElementById("pageInfo").innerText =
            `Halaman ${pageInfo.currentPage} dari ${pageInfo.lastPage} (Total: ${pageInfo.total})`;
        document.getElementById("prevBtn").disabled = pageInfo.currentPage <= 1;
        document.getElementById("nextBtn").disabled = !pageInfo.hasMorePages;
    }
}

function showTableLoading(tableId, colspan, rows) {
    const tbody = document.getElementById(tableId);
    let loadingRows = '';
    for(let i = 0; i < rows; i++) {
        loadingRows += `
            <tr class="animate-pulse">
                ${Array(colspan).fill('<td class="px-4 py-3"><div class="h-4 bg-gray-200 rounded"></div></td>').join('')}
            </tr>
        `;
    }
    tbody.innerHTML = loadingRows;
}

function searchJadwal() {
    loadJadwalData(1);
}

function prevPage() {
    if (currentPage > 1) {
        loadJadwalData(currentPage - 1);
    }
}

function nextPage() {
    loadJadwalData(currentPage + 1);
}

document.addEventListener("DOMContentLoaded", () => {
    loadJadwalData();
});