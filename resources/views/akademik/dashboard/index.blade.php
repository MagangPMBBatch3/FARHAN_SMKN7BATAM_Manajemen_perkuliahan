<x-layouts.akademik title="Dashboard Akademik">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Akademik</h1>
        <p class="text-sm text-gray-500">Ringkasan operasional akademik dan layanan mahasiswa.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-xs text-gray-500 uppercase">Mahasiswa Aktif</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">1.245</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-xs text-gray-500 uppercase">Dosen Aktif</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">86</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-xs text-gray-500 uppercase">Pengajuan KRS</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">312</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-xs text-gray-500 uppercase">KHS Tertunda</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">58</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Permintaan Layanan Terbaru</h2>
            <div class="space-y-4 text-sm">
                <div class="flex items-center justify-between border border-gray-100 rounded-xl p-4">
                    <div>
                        <p class="font-semibold text-gray-800">Revisi KRS - TI-2021-019</p>
                        <p class="text-xs text-gray-500">Diajukan 2 jam lalu</p>
                    </div>
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Menunggu</span>
                </div>
                <div class="flex items-center justify-between border border-gray-100 rounded-xl p-4">
                    <div>
                        <p class="font-semibold text-gray-800">Validasi KHS - SI-2020-044</p>
                        <p class="text-xs text-gray-500">Diajukan kemarin</p>
                    </div>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Diproses</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Pengingat</h2>
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-center justify-between">
                    <span>Finalisasi jadwal UTS</span>
                    <span class="text-xs text-rose-500 font-semibold">3 hari</span>
                </li>
                <li class="flex items-center justify-between">
                    <span>Monitoring KRS gelombang 2</span>
                    <span class="text-xs text-amber-500 font-semibold">Minggu ini</span>
                </li>
                <li class="flex items-center justify-between">
                    <span>Audit data dosen</span>
                    <span class="text-xs text-emerald-500 font-semibold">Bulanan</span>
                </li>
            </ul>
        </div>
    </div>
</x-layouts.akademik>
