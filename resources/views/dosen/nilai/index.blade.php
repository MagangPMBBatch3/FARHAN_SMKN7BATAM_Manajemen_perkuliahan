<x-layouts.dosen title="Input Nilai">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Input Nilai Mahasiswa</h1>
        <p class="text-sm text-gray-500">Kelola penilaian mahasiswa di kelas yang Anda ampu.</p>
    </div>

    <!-- Filter & Action -->
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mahasiswa</label>
                <input type="text" id="search" placeholder="NIM atau nama mahasiswa..."
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
                    <option value="Draft">Draft</option>
                    <option value="Pending">Pending</option>
                    <option value="Final">Final</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button onclick="searchNilai()" class="flex-1 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
                <button onclick="openAddModal()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" title="Tambah Nilai">
                    <i class="fas fa-plus"></i>
                </button>
                <button onclick="openBulkInputModal()" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors" title="Input Massal">
                    <i class="fas fa-list"></i>
                </button>
                <button onclick="openLaporanModal()" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors" title="Laporan">
                    <i class="fas fa-file-alt"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-2xl shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button onclick="switchTab('aktif')" id="tabAktif" class="tab-button active px-6 py-3 text-sm font-medium border-b-2 border-blue-500 text-blue-600">
                    Data Nilai Aktif
                </button>
                <button onclick="switchTab('arsip')" id="tabArsip" class="tab-button px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    Arsip
                </button>
            </nav>
        </div>
    </div>

    <!-- Tabel Nilai Aktif -->
    <div id="contentAktif" class="tab-content bg-white rounded-2xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Data Nilai</h2>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Tampilkan:</label>
                <select id="perPage" onchange="loadNilaiData()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
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
                        <th class="py-3 px-4">Mahasiswa</th>
                        <th class="py-3 px-4">Mata Kuliah</th>
                        <th class="py-3 px-4 text-center">Tugas</th>
                        <th class="py-3 px-4 text-center">Quiz</th>
                        <th class="py-3 px-4 text-center">UTS</th>
                        <th class="py-3 px-4 text-center">UAS</th>
                        <th class="py-3 px-4 text-center">Nilai Akhir</th>
                        <th class="py-3 px-4 text-center">Grade</th>
                        <th class="py-3 px-4 text-center">Mutu</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataNilai" class="divide-y">
                    <tr>
                        <td colspan="11" class="text-center text-gray-500 p-4">Memuat data...</td>
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

    <!-- Tabel Nilai Arsip -->
    <div id="contentArsip" class="tab-content hidden bg-white rounded-2xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Data Arsip</h2>
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Tampilkan:</label>
                <select id="perPageArsip" onchange="loadNilaiData()" class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
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
                        <th class="py-3 px-4">Mahasiswa</th>
                        <th class="py-3 px-4">Mata Kuliah</th>
                        <th class="py-3 px-4 text-center">Tugas</th>
                        <th class="py-3 px-4 text-center">Quiz</th>
                        <th class="py-3 px-4 text-center">UTS</th>
                        <th class="py-3 px-4 text-center">UAS</th>
                        <th class="py-3 px-4 text-center">Nilai Akhir</th>
                        <th class="py-3 px-4 text-center">Grade</th>
                        <th class="py-3 px-4 text-center">Mutu</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataNilaiArsip" class="divide-y">
                    <tr>
                        <td colspan="11" class="text-center text-gray-500 p-4">Memuat data...</td>
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

    @include('components.modal.dosenModal.nilai.modal-add')
    @include('components.modal.dosenModal.nilai.modal-edit')
    @include('components.modal.dosenModal.nilai.modal-bulk')
    @include('components.modal.dosenModal.nilai.modal-laporan')

    <script src="{{ asset('js/dosen/nilai/nilai.js') }}"></script>
    <script src="{{ asset('js/dosen/nilai/nilai-create.js') }}"></script>
    <script src="{{ asset('js/dosen/nilai/nilai-edit.js') }}"></script>
    <script src="{{ asset('js/dosen/nilai/nilai-bulk-input.js') }}"></script>
    <script src="{{ asset('js/dosen/nilai/nilai-laporan.js') }}"></script>
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