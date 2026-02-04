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
                    <p class="text-3xl font-bold text-gray-800 mt-2">4</p>
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
                    <p class="text-3xl font-bold text-gray-800 mt-2">3</p>
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
                    <p class="text-3xl font-bold text-gray-800 mt-2">12</p>
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
                    <p class="text-3xl font-bold text-gray-800 mt-2">8</p>
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
            <div class="space-y-3">
                <div class="flex items-center justify-between border border-gray-100 rounded-xl p-4">
                    <div>
                        <p class="font-semibold text-gray-800">Algoritma & Struktur Data</p>
                        <p class="text-xs text-gray-500">Senin, 08.00 - 10.00 • R. 301</p>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Aktif</span>
                </div>
                <div class="flex items-center justify-between border border-gray-100 rounded-xl p-4">
                    <div>
                        <p class="font-semibold text-gray-800">Basis Data</p>
                        <p class="text-xs text-gray-500">Rabu, 10.00 - 12.00 • Lab 2</p>
                    </div>
                    <span class="text-xs font-semibold text-sky-600 bg-sky-50 px-3 py-1 rounded-full">Minggu Ini</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-bell text-amber-500"></i>
                Tugas Cepat
            </h2>
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-center justify-between">
                    <span>Input nilai kelas TI-3A</span>
                    <span class="text-xs text-rose-500 font-semibold">Hari ini</span>
                </li>
                <li class="flex items-center justify-between">
                    <span>Validasi presensi TI-2B</span>
                    <span class="text-xs text-amber-500 font-semibold">2 hari</span>
                </li>
                <li class="flex items-center justify-between">
                    <span>Unggah materi pertemuan</span>
                    <span class="text-xs text-emerald-500 font-semibold">Minggu ini</span>
                </li>
            </ul>
        </div>
    </div>
</x-layouts.dosen>
