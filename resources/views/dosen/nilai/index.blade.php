<x-layouts.dosen title="Input Nilai">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Input Nilai Mahasiswa</h1>
        <p class="text-sm text-gray-500">Periksa progres penilaian dan status pengumpulan.</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase text-gray-500 border-b">
                        <th class="py-3">Kelas</th>
                        <th class="py-3">Mata Kuliah</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Deadline</th>
                        <th class="py-3 text-right">Progress</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr>
                        <td class="py-3 font-semibold text-gray-700">TI-3A</td>
                        <td class="py-3">Algoritma</td>
                        <td class="py-3">
                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Perlu Input</span>
                        </td>
                        <td class="py-3">20 Okt 2024</td>
                        <td class="py-3 text-right text-amber-600 font-semibold">65%</td>
                    </tr>
                    <tr>
                        <td class="py-3 font-semibold text-gray-700">TI-2B</td>
                        <td class="py-3">Basis Data</td>
                        <td class="py-3">
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Lengkap</span>
                        </td>
                        <td class="py-3">10 Okt 2024</td>
                        <td class="py-3 text-right text-emerald-600 font-semibold">100%</td>
                    </tr>
                    <tr>
                        <td class="py-3 font-semibold text-gray-700">TI-4C</td>
                        <td class="py-3">Pemrograman Web</td>
                        <td class="py-3">
                            <span class="text-xs font-semibold text-sky-600 bg-sky-50 px-3 py-1 rounded-full">Berjalan</span>
                        </td>
                        <td class="py-3">30 Okt 2024</td>
                        <td class="py-3 text-right text-sky-600 font-semibold">40%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dosen>
