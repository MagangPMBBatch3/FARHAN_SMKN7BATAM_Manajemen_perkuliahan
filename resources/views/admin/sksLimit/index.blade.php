<x-layouts.dashboard title="Data Batas SKS">
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Search & Actions Bar -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" 
                            placeholder="Cari berdasarkan keterangan..." 
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            oninput="searchSksLimit()">
                    </div>

                    <button onclick="openAddModal()" 
                        class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Batas SKS
                    </button>
                </div>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200">
                <nav class="flex px-6 -mb-px space-x-8">
                    <button onclick="showTab('aktif')" id="tabAktif"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                        Data Aktif
                    </button>
                    <button onclick="showTab('arsip')" id="tabArsip"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700">
                        Data Arsip
                    </button>
                </nav>
            </div>

            <!-- Table Aktif -->
            <div id="tableAktif" class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min IPK</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max IPK</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max SKS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="dataSksLimit" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 mt-4">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button onclick="prevPageAktif()" class="px-4 py-2 border rounded-md text-sm">Previous</button>
                        <button onclick="nextPageAktif()" class="px-4 py-2 border rounded-md text-sm">Next</button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p id="pageInfoAktif" class="text-sm text-gray-700">Halaman 1 dari 1</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-sm text-gray-700">Tampilkan:</label>
                            <select id="perPage" class="border-gray-300 rounded-md text-sm" onchange="loadSksLimitData(1, 1)">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            
                            <button id="prevBtnAktif" onclick="prevPageAktif()" class="px-2 py-2 border rounded-l-md">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <button id="nextBtnAktif" onclick="nextPageAktif()" class="px-2 py-2 border rounded-r-md">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min IPK</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max IPK</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Max SKS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="dataSksLimitArsip" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>

                <!-- Pagination Arsip -->
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 mt-4">
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p id="pageInfoArsip" class="text-sm text-gray-700">Halaman 1 dari 1</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="text-sm text-gray-700">Tampilkan:</label>
                            <select id="perPageArsip" class="border-gray-300 rounded-md text-sm" onchange="loadSksLimitData(1, 1)">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            
                            <button id="prevBtnArsip" onclick="prevPageArsip()" class="px-2 py-2 border rounded-l-md">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <button id="nextBtnArsip" onclick="nextPageArsip()" class="px-2 py-2 border rounded-r-md">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.modal.sksLimit.modal-add')
    @include('components.modal.sksLimit.modal-edit')

    <script src="{{ asset('js/services/loading.js') }}"></script>
    <script src="{{ asset('js/admin/sksLimit/sks-limit.js') }}"></script>
    <script src="{{ asset('js/admin/sksLimit/sks-limit-create.js') }}"></script>
    <script src="{{ asset('js/admin/sksLimit/sks-limit-edit.js') }}"></script>

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
        }
    </script>
</x-layouts.dashboard>