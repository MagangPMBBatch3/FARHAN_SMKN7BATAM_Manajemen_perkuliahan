<!-- Modal Laporan Nilai Per Kelas -->
<div id="modalLaporan" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-7xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-white">Laporan Nilai Per Kelas</h2>
                            <p class="text-green-100 text-sm">Lihat rekap dan statistik nilai mahasiswa</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeLaporanModal()" class="text-green-100 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-end gap-4">
                    <!-- Pilih Semester -->
                    <div class="flex-1">
                        <label for="laporanSemester" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select id="laporanSemester" 
                            onchange="onLaporanSemesterChange()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                            <option value="">Pilih Semester</option>
                        </select>
                    </div>

                    <!-- Pilih Kelas -->
                    <div class="flex-1">
                        <label for="laporanKelas" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select id="laporanKelas" 
                            disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all disabled:bg-gray-100">
                            <option value="">Pilih semester terlebih dahulu</option>
                        </select>
                    </div>

                    <!-- Button Generate -->
                    <div>
                        <button onclick="generateLaporan()" 
                            id="btnGenerateLaporan"
                            disabled
                            class="px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Generate Laporan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <!-- Info Kelas -->
                <div id="laporanInfoKelas" class="hidden mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Mata Kuliah</p>
                            <p id="laporanNamaMK" class="font-semibold text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Kode MK</p>
                            <p id="laporanKodeMK" class="font-medium text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Dosen</p>
                            <p id="laporanDosen" class="font-medium text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Total Mahasiswa</p>
                            <p id="laporanTotalMhs" class="font-semibold text-gray-900">-</p>
                        </div>
                    </div>
                </div>

                <!-- Statistik -->
                <div id="laporanStatistik" class="hidden mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Nilai</h3>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-xs text-green-700 mb-1">Nilai Tertinggi</p>
                            <p id="statNilaiMax" class="text-2xl font-bold text-green-600">-</p>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-xs text-blue-700 mb-1">Rata-rata</p>
                            <p id="statNilaiAvg" class="text-2xl font-bold text-blue-600">-</p>
                        </div>
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <p class="text-xs text-orange-700 mb-1">Nilai Terendah</p>
                            <p id="statNilaiMin" class="text-2xl font-bold text-orange-600">-</p>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <p class="text-xs text-purple-700 mb-1">Lulus</p>
                            <p id="statLulus" class="text-2xl font-bold text-purple-600">-</p>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-xs text-red-700 mb-1">Tidak Lulus</p>
                            <p id="statTidakLulus" class="text-2xl font-bold text-red-600">-</p>
                        </div>
                    </div>
                </div>

                <!-- Distribusi Grade -->
                <div id="laporanDistribusi" class="hidden mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Grade</h3>
                    <div id="gradeDistribution" class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Tabel Nilai -->
                <div id="laporanTabel" class="hidden">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Nilai Mahasiswa</h3>
                        <button onclick="exportToExcel()" 
                            class="px-4 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-300 rounded-lg hover:bg-green-100 focus:outline-none transition-all duration-200">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Excel
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UTS</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">UAS</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Praktikum</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-yellow-50">Nilai Akhir</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-green-50">Grade</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider bg-blue-50">Mutu</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody id="laporanTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="laporanEmptyState" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada laporan</h3>
                    <p class="mt-1 text-sm text-gray-500">Pilih semester dan kelas, lalu klik Generate Laporan</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end">
                    <button type="button" onclick="closeLaporanModal()" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>