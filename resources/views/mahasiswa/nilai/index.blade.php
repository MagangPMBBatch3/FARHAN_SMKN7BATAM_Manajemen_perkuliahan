<x-layouts.mahasiswa title="Nilai Saya">
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3 mr-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Nilai Akademik Saya</h1>
                            <p class="text-blue-100 text-sm mt-1">Lihat semua nilai mata kuliah yang telah Anda ambil</p>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                            <p class="text-blue-100 text-xs">NIM</p>
                            <p id="headerNIM" class="text-white font-bold text-lg">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Filter Semester -->
                    <div>
                        <label for="filterSemester" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter Semester
                        </label>
                        <select id="filterSemester" 
                            onchange="loadNilaiMahasiswa()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Semua Semester</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari Mata Kuliah
                        </label>
                        <input type="text" id="searchMK" 
                            placeholder="Cari berdasarkan kode atau nama mata kuliah..."
                            oninput="searchNilai()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                </div>
            </div>

            {{-- IPK Summary --}}
            <div id="ipkSummary" class="hidden px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">Total SKS Diambil</p>
                        <p id="totalSKS" class="text-2xl font-bold text-indigo-600">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">Total SKS Lulus</p>
                        <p id="totalSKSLulus" class="text-2xl font-bold text-green-600">0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">IPK</p>
                        <p id="ipkValue" class="text-2xl font-bold text-blue-600">0.00</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600 mb-1">Total Mata Kuliah</p>
                        <p id="totalMK" class="text-2xl font-bold text-purple-600">0</p>
                    </div>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-yellow-50">Nilai Akhir</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-green-50">Grade</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-blue-50">Mutu</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableNilai" class="bg-white divide-y divide-gray-200">
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>

                {{-- Empty State --}}
                <div id="emptyState" class="hidden text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada nilai</h3>
                    <p class="mt-1 text-sm text-gray-500">Nilai Anda akan ditampilkan di sini setelah dosen menginput nilai</p>
                </div>

                {{-- Loading State --}}
                <div id="loadingState" class="text-center py-12">
                    <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-4 text-sm text-gray-600">Memuat data nilai...</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Nilai --}}
    @include('components.modal.mahasiswaModal.modal-detail-nilai')

    {{-- Script --}}
    <script src="{{ asset('js/mahasiswa/nilai/mahasiswa-nilai.js') }}"></script>
</x-layouts.mahasiswa>