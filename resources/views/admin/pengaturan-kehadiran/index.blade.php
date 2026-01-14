<x-layouts.dashboard title="Pengaturan Kehadiran">
    <div class="space-y-6">
        <!-- Info Card -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Pengaturan Aturan Kehadiran</h3>
                    <p class="text-purple-100 text-sm">Kelola aturan kehadiran untuk setiap kelas. Atur minimal kehadiran dan fitur auto-generate pertemuan.</p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-16 h-16 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Search & Actions Bar -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" 
                            placeholder="Cari kelas atau keterangan..." 
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm"
                            oninput="searchPengaturanKehadiran()">
                    </div>

                    <button onclick="openAddModal()" 
                        class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Pengaturan
                    </button>
                </div>

                <!-- Filter -->
                <div>
                    <label for="filterKelas" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Filter Kelas
                    </label>
                    <select id="filterKelas"
                        class="w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onchange="searchPengaturanKehadiran()">
                        <option value="">Semua Kelas</option>
                    </select>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200">
                <nav class="flex px-6 -mb-px space-x-8" aria-label="Tabs">
                    <button onclick="showTab('aktif')" id="tabAktif"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-blue-500 text-blue-600">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Data Aktif
                        </div>
                    </button>
                    <button onclick="showTab('arsip')" id="tabArsip"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            Data Arsip
                        </div>
                    </button>
                </nav>
            </div>

            <!-- Table Aktif -->
            <div id="tableAktif" class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Kelas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Minimal Kehadiran</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Auto Generate</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="dataPengaturanKehadiran" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>

                <!-- Pagination Data Aktif -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-4">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button onclick="prevPageAktif()"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous
                        </button>
                        <button onclick="nextPageAktif()"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p id="pageInfoAktif" class="text-sm text-gray-700">
                                Halaman 1 dari 1 (Total: 0)
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-sm text-gray-700">Tampilkan:</label>
                            <select id="perPage"
                                class="border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                onchange="loadPengaturanKehadiranData(1, 1)">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <button id="prevBtnAktif" onclick="prevPageAktif()"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <button id="nextBtnAktif" onclick="nextPageAktif()"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Arsip -->
            <div id="tableArsip" class="p-6 hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Kelas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Minimal Kehadiran</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Auto Generate</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="dataPengaturanKehadiranArsip" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>

                <!-- Pagination Data Arsip -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-4">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button onclick="prevPageArsip()"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous
                        </button>
                        <button onclick="nextPageArsip()"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p id="pageInfoArsip" class="text-sm text-gray-700">
                                Halaman 1 dari 1 (Total: 0)
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-sm text-gray-700">Tampilkan:</label>
                            <select id="perPageArsip"
                                class="border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                onchange="loadPengaturanKehadiranData(1, 1)">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <button id="prevBtnArsip" onclick="prevPageArsip()"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <button id="nextBtnArsip" onclick="nextPageArsip()"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Minimal Kehadiran</p>
                        <p class="text-xs text-gray-500 mt-1">Atur persentase minimal untuk ikut UAS</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Auto Generate</p>
                        <p class="text-xs text-gray-500 mt-1">Otomatis buat pertemuan sesuai jadwal</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Status Aktif</p>
                        <p class="text-xs text-gray-500 mt-1">Aktif/nonaktifkan aturan per kelas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('components.modal.pengaturan-kehadiran.modal-add')
    @include('components.modal.pengaturan-kehadiran.modal-edit')

    <!-- Scripts -->
    <script src="{{ asset('js/admin/pengaturan-kehadiran/pengaturan-kehadiran.js') }}"></script>
    <script src="{{ asset('js/admin/pengaturan-kehadiran/pengaturan-kehadiran-create.js') }}"></script>
    <script src="{{ asset('js/admin/pengaturan-kehadiran/pengaturan-kehadiran-edit.js') }}"></script>

    <script>
        function showTab(tab) {
            const tabAktif = document.getElementById('tabAktif');
            const tabArsip = document.getElementById('tabArsip');
            const tableAktif = document.getElementById('tableAktif');
            const tableArsip = document.getElementById('tableArsip');

            if (tab === 'aktif') {
                tabAktif.classList.add('border-blue-500', 'text-blue-600');
                tabAktif.classList.remove('border-transparent', 'text-gray-500');
                tabArsip.classList.remove('border-blue-500', 'text-blue-600');
                tabArsip.classList.add('border-transparent', 'text-gray-500');
                tableAktif.classList.remove('hidden');
                tableArsip.classList.add('hidden');
            } else {
                tabArsip.classList.add('border-blue-500', 'text-blue-600');
                tabArsip.classList.remove('border-transparent', 'text-gray-500');
                tabAktif.classList.remove('border-blue-500', 'text-blue-600');
                tabAktif.classList.add('border-transparent', 'text-gray-500');
                tableArsip.classList.remove('hidden');
                tableAktif.classList.add('hidden');
            }

            loadPengaturanKehadiranData(currentPageAktif, currentPageArsip);
        }
    </script>
</x-layouts.dashboard>