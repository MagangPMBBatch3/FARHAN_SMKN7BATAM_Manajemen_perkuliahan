<x-layouts.dosen title="Jadwal Mengajar">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Jadwal Mengajar</h1>
        <p class="text-sm text-gray-500">Lihat jadwal kelas yang Anda ampu.</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" id="search" placeholder="Cari mata kuliah, hari..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hari</label>
                <select id="filterHari" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="searchJadwal()" class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </div>
        </div>
    </div>

    <!-- Tabel Jadwal -->
    <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Jadwal Mengajar</h2>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Tampilkan:</label>
                <select id="perPage" onchange="loadJadwalData()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase text-gray-500 border-b">
                        <th class="py-3 px-4">Mata Kuliah</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Hari</th>
                        <th class="py-3 px-4">Jam</th>
                        <th class="py-3 px-4">Ruangan</th>
                        <th class="py-3 px-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="dataJadwal" class="divide-y">
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 p-4">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between mt-4 pt-4 border-t">
            <span id="pageInfo" class="text-sm text-gray-600">-</span>
            <div class="flex gap-2">
                <button id="prevBtn" onclick="prevPage()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i> Sebelumnya
                </button>
                <button id="nextBtn" onclick="nextPage()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Selanjutnya <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dosen/jadwal.js') }}"></script>
</x-layouts.dosen>