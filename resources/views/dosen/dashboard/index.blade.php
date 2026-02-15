<x-layouts.dosen title="Dashboard Dosen">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Ringkasan Aktivitas Mengajar</h1>
        <p class="text-sm text-gray-500">Pantau jadwal, pertemuan, dan penilaian kelas Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Kelas Diampu</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="totalKelas">-</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-users text-emerald-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Mata Kuliah</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="totalMataKuliah">-</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-sky-100 flex items-center justify-center">
                    <i class="fas fa-book text-sky-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Pertemuan Aktif</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="totalPertemuan">-</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-chalkboard text-amber-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Nilai Tertunda</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" id="totalNilaiTertunda">-</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-rose-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-day text-emerald-500"></i>
                Jadwal Mengajar Minggu Ini
            </h2>
            <div id="jadwalMingguIni" class="space-y-3">
                <div class="text-center text-gray-500 py-4">Memuat data...</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-bell text-amber-500"></i>
                Tugas Cepat
            </h2>
            <ul id="tugasCepat" class="space-y-3 text-sm text-gray-600">
                <li class="text-center text-gray-500">Memuat data...</li>
            </ul>
        </div>
    </div>

    <script src="{{ asset('js/dosen/dashboard.js') }}"></script>
</x-layouts.dosen>