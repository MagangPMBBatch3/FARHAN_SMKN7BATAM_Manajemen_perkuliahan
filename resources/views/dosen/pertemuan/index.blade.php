<x-layouts.dosen title="Manajemen Pertemuan">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Pertemuan</h1>
        <p class="text-sm text-gray-500">Pantau status pertemuan dan presensi mahasiswa.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Pertemuan Minggu Ini</h2>
            <div class="space-y-4 text-sm">
                <div class="flex items-start justify-between border border-gray-100 rounded-xl p-4">
                    <div>
                        <p class="font-semibold text-gray-800">Pertemuan 7 - TI-3A</p>
                        <p class="text-xs text-gray-500">Senin, 08.00 • Algoritma</p>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Berjalan</span>
                </div>
                <div class="flex items-start justify-between border border-gray-100 rounded-xl p-4">
                    <div>
                        <p class="font-semibold text-gray-800">Pertemuan 5 - TI-2B</p>
                        <p class="text-xs text-gray-500">Rabu, 10.00 • Basis Data</p>
                    </div>
                    <span class="text-xs font-semibold text-sky-600 bg-sky-50 px-3 py-1 rounded-full">Dijadwalkan</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Kehadiran</h2>
            <div class="space-y-4 text-sm text-gray-600">
                <div class="flex items-center justify-between">
                    <span>Hadir</span>
                    <span class="font-semibold text-emerald-600">92%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Izin</span>
                    <span class="font-semibold text-amber-600">5%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Alpha</span>
                    <span class="font-semibold text-rose-600">3%</span>
                </div>
                <div class="mt-6 p-4 rounded-xl bg-slate-50 text-xs text-gray-500">
                    Pastikan presensi pertemuan terbaru sudah diverifikasi sebelum mengunci kelas.
                </div>
            </div>
        </div>
    </div>
</x-layouts.dosen>
