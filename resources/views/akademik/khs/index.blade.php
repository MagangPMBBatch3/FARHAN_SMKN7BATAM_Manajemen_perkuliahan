<x-layouts.akademik title="Kartu Hasil Studi">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Validasi KHS</h1>
        <p class="text-sm text-gray-500">Lihat status pengumpulan dan validasi KHS mahasiswa.</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase text-gray-500 border-b">
                        <th class="py-3">NIM</th>
                        <th class="py-3">Nama</th>
                        <th class="py-3">Semester</th>
                        <th class="py-3">IP Semester</th>
                        <th class="py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr>
                        <td class="py-3 font-semibold text-gray-700">TI-2021-019</td>
                        <td class="py-3">Nadia Putri</td>
                        <td class="py-3">6</td>
                        <td class="py-3">3.65</td>
                        <td class="py-3">
                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Menunggu</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 font-semibold text-gray-700">SI-2020-044</td>
                        <td class="py-3">Rizky Fadillah</td>
                        <td class="py-3">8</td>
                        <td class="py-3">3.88</td>
                        <td class="py-3">
                            <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">Valid</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.akademik>
