<x-layouts.mahasiswa title="Jadwal Pertemuan Saya">
    <div class="space-y-6">
        <!-- Header Section -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Jadwal Pertemuan Kuliah</h1>
                    <p class="text-blue-100">Lihat jadwal pertemuan dari semua kelas yang Anda ambil</p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-16 h-16 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Main Content Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Filter & Search Section - FIXED -->
            <div class="sticky top-0 z-10 bg-white border-b border-gray-200">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Cari Pertemuan
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="search" 
                                    placeholder="Cari mata kuliah atau materi..." 
                                    oninput="searchPertemuan()"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <!-- Filter Semester -->
                        <div>
                            <label for="filterSemester" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Filter Semester
                            </label>
                            <select id="filterSemester"
                                onchange="searchPertemuan()"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Semester</option>
                            </select>
                        </div>

                        <!-- Filter Status -->
                        <div>
                            <label for="filterStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Filter Status
                            </label>
                            <select id="filterStatus"
                                onchange="searchPertemuan()"
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Status</option>
                                <option value="Dijadwalkan">Dijadwalkan</option>
                                <option value="Berlangsung">Berlangsung</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards - FIXED -->
                <div class="px-6 pb-6 bg-gray-50">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Total Kelas</p>
                                    <p id="totalKelas" class="text-xl font-bold text-gray-900">0</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Selesai</p>
                                    <p id="totalSelesai" class="text-xl font-bold text-gray-900">0</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Dijadwalkan</p>
                                    <p id="totalDijadwalkan" class="text-xl font-bold text-gray-900">0</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-500">Total Pertemuan</p>
                                    <p id="totalPertemuan" class="text-xl font-bold text-gray-900">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scrollable Content Area -->
            <div id="pertemuanScrollContainer" class="overflow-y-auto" style="height: calc(100vh - 520px); min-height: 400px;">
                <div class="p-6">
                    <div id="pertemuanContainer" class="space-y-4">
                        <!-- Cards will be rendered here -->
                    </div>

                    <!-- Empty State -->
                    <div id="emptyState" class="hidden text-center py-12">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada jadwal pertemuan</h3>
                        <p class="mt-2 text-sm text-gray-500">Belum ada pertemuan yang dijadwalkan untuk kelas Anda.</p>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="hidden text-center py-8">
                        <div class="inline-flex items-center gap-3">
                            <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm text-gray-600 font-medium">Memuat data...</span>
                        </div>
                    </div>

                    <!-- Load More Button (Fallback for manual loading) -->
                    <div id="loadMoreBtn" class="hidden text-center py-6">
                        <button onclick="loadMoreData()" 
                            class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Muat Lebih Banyak
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer with Info - FIXED -->
            <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p id="pageInfo" class="text-sm text-gray-700 font-medium">Menampilkan 0 dari 0 pertemuan</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="text-sm text-gray-700 font-medium">Tampilkan:</label>
                        <select id="perPage"
                            class="border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium"
                            onchange="searchPertemuan()">
                            <option value="20" selected>20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    @include('components.modal.mahasiswaModal.pertemuan-detail')

    <!-- Scripts -->
    <script src="{{ asset('js/mahasiswa/pertemuan/pertemuan.js') }}"></script>

    <!-- Custom Scrollbar Styles -->
    <style>
        /* Custom Scrollbar for Webkit browsers */
        #pertemuanScrollContainer::-webkit-scrollbar {
            width: 8px;
        }

        #pertemuanScrollContainer::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        #pertemuanScrollContainer::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        #pertemuanScrollContainer::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth scrolling */
        #pertemuanScrollContainer {
            scroll-behavior: smooth;
        }

        /* Prevent layout shift during loading */
        .pertemuan-card {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-layouts.dashboard>