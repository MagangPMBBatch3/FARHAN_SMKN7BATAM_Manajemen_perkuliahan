<x-layouts.mahasiswa title="Dashboard Mahasiswa">
    @push('body-attributes')
        data-mahasiswa-id="{{ auth()->user()->mahasiswa->id ?? '' }}"
    @endpush
    
    {{-- Error Notification Container --}}
    <div id="error-notification"></div>
    
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-emerald-500 via-emerald-600 to-sky-600 rounded-2xl p-8 mb-6 shadow-xl text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold mb-2">
                    Selamat Datang, <span data-mahasiswa-nama>...</span>! 👋
                </h2>
                <p class="text-emerald-50 text-lg">
                    NIM: <span data-mahasiswa-nim>...</span> | 
                    Semester <span data-mahasiswa-semester>...</span>
                </p>
                <div class="flex items-center space-x-6 mt-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-graduation-cap"></i>
                        <span data-mahasiswa-jurusan>...</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar"></i>
                        <span>Angkatan <span data-mahasiswa-angkatan>...</span></span>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 text-center">
                    <p class="text-sm text-emerald-50 mb-1">IPK Anda</p>
                    <p class="text-5xl font-bold" data-mahasiswa-ipk>0.00</p>
                    <div class="mt-2 flex items-center justify-center">
                        <span data-ipk-badge class="bg-blue-400 text-blue-900 text-xs px-3 py-1 rounded-full font-semibold">
                            Memuat...
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Total SKS --}}
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-book-open text-blue-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Total SKS</h3>
            <p class="text-3xl font-bold text-gray-800" data-stat-total-sks>0</p>
            <p class="text-xs text-gray-400 mt-2">dari 144 SKS</p>
            <div class="mt-3 bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" 
                     data-stat-progress style="width: 0%"></div>
            </div>
        </div>

        {{-- Semester Saat Ini --}}
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-emerald-100 p-3 rounded-xl">
                    <i class="fas fa-layer-group text-emerald-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Semester Aktif</h3>
            <p class="text-3xl font-bold text-gray-800" data-stat-semester>0</p>
            <p class="text-xs text-gray-400 mt-2">Semester saat ini</p>
        </div>

        {{-- Mata Kuliah Aktif --}}
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-100 p-3 rounded-xl">
                    <i class="fas fa-clipboard-list text-purple-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Mata Kuliah</h3>
            <p class="text-3xl font-bold text-gray-800" data-stat-mk-aktif>0</p>
            <p class="text-xs text-gray-400 mt-2">Semester ini</p>
        </div>

        {{-- Status Akademik --}}
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-orange-100 p-3 rounded-xl">
                    <i class="fas fa-user-check text-orange-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium mb-1">Status</h3>
            <p class="text-2xl font-bold text-gray-800" data-stat-status>...</p>
            <span data-stat-status-badge class="inline-block mt-2 bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full">
                Memuat...
            </span>
        </div>
    </div>

    {{-- Grid 2 Kolom untuk Mata Kuliah dan Jadwal/Pengumuman --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        {{-- Mata Kuliah Semester Ini (2 kolom) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center justify-between">
                <span class="flex items-center">
                    <i class="fas fa-book text-emerald-500 mr-2"></i>
                    Mata Kuliah Semester Ini
                </span>
                <a href="/mahasiswa/krs" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                    Lihat Detail KRS <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode MK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Mata Kuliah</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">SKS</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Dosen</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kelas</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" data-mk-semester-ini>
                        {{-- Data akan dimuat via JavaScript --}}
                        <tr>
                            <td colspan="6" class="text-center py-8">
                                <i class="fas fa-spinner fa-spin text-emerald-500 text-3xl mb-2"></i>
                                <p class="text-gray-500">Memuat data...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sidebar: Jadwal & Pengumuman (1 kolom) --}}
        <div class="space-y-6">
            
            {{-- Jadwal Hari Ini --}}
            <div class="bg-white rounded-2xl p-6 pb-26 shadow-lg border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-calendar-day text-emerald-500 mr-2"></i>
                    Jadwal Hari Ini
                </h3>
                <div class="space-y-3" data-jadwal-hari-ini>
                    {{-- Data akan dimuat via JavaScript --}}
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin text-emerald-500 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-500">Memuat jadwal...</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/mahasiswa/dashboard/dashboard.js') }}"></script>

</x-layouts.mahasiswa>