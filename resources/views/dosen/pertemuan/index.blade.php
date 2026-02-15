<x-layouts.dosen title="Manajemen Pertemuan">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Pertemuan</h1>
        <p class="text-sm text-gray-500">Kelola pertemuan kelas yang Anda ampu.</p>
    </div>

    <!-- Filter & Action -->
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" id="search" placeholder="Cari pertemuan..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select id="filterKelas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Kelas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="filterStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="Dijadwalkan">Dijadwalkan</option>
                    <option value="Berlangsung">Berlangsung</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button onclick="searchPertemuan()" class="flex-1 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <button onclick="openAddModal()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-2xl shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="switchTab('aktif')" id="tabAktif" class="tab-button active px-6 py-3 text-sm font-medium border-b-2 border-blue-500 text-blue-600">
                    Pertemuan Aktif
                </button>
                <button onclick="switchTab('arsip')" id="tabArsip" class="tab-button px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Arsip
                </button>
            </nav>
        </div>
    </div>

    <!-- Tabel Pertemuan Aktif -->
    <div id="contentAktif" class="tab-content bg-white rounded-2xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Pertemuan Aktif</h2>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Tampilkan:</label>
                <select id="perPage" onchange="loadPertemuanData()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase text-gray-500 border-b">
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Mata Kuliah</th>
                        <th class="py-3 px-4 text-center">Pertemuan</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Materi</th>
                        <th class="py-3 px-4 text-center">Metode</th>
                        <th class="py-3 px-4">Ruangan</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataPertemuan" class="divide-y">
                    <tr>
                        <td colspan="10" class="text-center text-gray-500 p-4">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mt-4 pt-4 border-t">
            <span id="pageInfoAktif" class="text-sm text-gray-600">-</span>
            <div class="flex gap-2">
                <button id="prevBtnAktif" onclick="prevPageAktif()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i> Sebelumnya
                </button>
                <button id="nextBtnAktif" onclick="nextPageAktif()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Selanjutnya <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabel Pertemuan Arsip -->
    <div id="contentArsip" class="tab-content hidden bg-white rounded-2xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Pertemuan Arsip</h2>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Tampilkan:</label>
                <select id="perPageArsip" onchange="loadPertemuanData()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase text-gray-500 border-b">
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Mata Kuliah</th>
                        <th class="py-3 px-4 text-center">Pertemuan</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Materi</th>
                        <th class="py-3 px-4 text-center">Metode</th>
                        <th class="py-3 px-4">Ruangan</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataPertemuanArsip" class="divide-y">
                    <tr>
                        <td colspan="10" class="text-center text-gray-500 p-4">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between mt-4 pt-4 border-t">
            <span id="pageInfoArsip" class="text-sm text-gray-600">-</span>
            <div class="flex gap-2">
                <button id="prevBtnArsip" onclick="prevPageArsip()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i> Sebelumnya
                </button>
                <button id="nextBtnArsip" onclick="nextPageArsip()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Selanjutnya <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    @include('components.modal.dosenModal.pertemuan.modal-add')
    @include('components.modal.dosenModal.pertemuan.modal-edit')

    <script src="{{ asset('js/dosen/pertemuan/pertemuan.js') }}"></script>
    <script src="{{ asset('js/dosen/pertemuan/pertemuan-create.js') }}"></script>
    <script src="{{ asset('js/dosen/pertemuan/pertemuan-edit.js') }}"></script>
    <script>
        function switchTab(tab) {
            const tabs = ['aktif', 'arsip'];
            tabs.forEach(t => {
                const btn = document.getElementById(`tab${t.charAt(0).toUpperCase() + t.slice(1)}`);
                const content = document.getElementById(`content${t.charAt(0).toUpperCase() + t.slice(1)}`);
                
                if (t === tab) {
                    btn.classList.add('active', 'border-blue-500', 'text-blue-600');
                    btn.classList.remove('border-transparent', 'text-gray-500');
                    content.classList.remove('hidden');
                } else {
                    btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                    btn.classList.add('border-transparent', 'text-gray-500');
                    content.classList.add('hidden');
                }
            });
        }

        function showTableLoading(tableId, colspan, rows) {
            const tbody = document.getElementById(tableId);
            let loadingRows = '';
            for(let i = 0; i < rows; i++) {
                loadingRows += `
                    <tr class="animate-pulse">
                        ${Array(colspan).fill('<td class="px-6 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>').join('')}
                    </tr>
                `;
            }
            tbody.innerHTML = loadingRows;
        }
    </script>
</x-layouts.dosen>