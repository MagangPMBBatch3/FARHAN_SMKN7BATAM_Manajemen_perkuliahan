<div id="modalDetail" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-white">Detail Rekap Kehadiran</h2>
                    <button type="button" onclick="closeDetailModal()" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <!-- Informasi Mahasiswa -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Mahasiswa
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-600">NIM</p>
                            <p id="detailNim" class="text-sm font-semibold text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Nama Lengkap</p>
                            <p id="detailNama" class="text-sm font-semibold text-gray-900">-</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Kelas -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Informasi Kelas
                    </h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-600">Kode Kelas</p>
                            <p id="detailKelas" class="text-sm font-semibold text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Mata Kuliah</p>
                            <p id="detailMataKuliah" class="text-sm font-semibold text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Semester</p>
                            <p id="detailSemester" class="text-sm font-semibold text-gray-900">-</p>
                        </div>
                    </div>
                </div>

                <!-- Statistik Kehadiran -->
                <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Statistik Kehadiran
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg text-center">
                            <p class="text-xs text-gray-600 mb-1">Total Pertemuan</p>
                            <p id="detailTotalPertemuan" class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg text-center">
                            <p class="text-xs text-green-700 mb-1">Hadir</p>
                            <p id="detailTotalHadir" class="text-2xl font-bold text-green-600">0</p>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg text-center">
                            <p class="text-xs text-blue-700 mb-1">Izin</p>
                            <p id="detailTotalIzin" class="text-2xl font-bold text-blue-600">0</p>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded-lg text-center">
                            <p class="text-xs text-yellow-700 mb-1">Sakit</p>
                            <p id="detailTotalSakit" class="text-2xl font-bold text-yellow-600">0</p>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg text-center">
                            <p class="text-xs text-red-700 mb-1">Alpa</p>
                            <p id="detailTotalAlpa" class="text-2xl font-bold text-red-600">0</p>
                        </div>
                    </div>
                </div>

                <!-- Hasil Perhitungan -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Hasil Perhitungan
                    </h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Persentase Kehadiran</p>
                            <div id="detailPersentase" class="text-lg font-bold">-</div>
                            <p class="text-xs text-gray-500 mt-1">Hadir + Izin + Sakit</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Nilai Kehadiran</p>
                            <p id="detailNilaiKehadiran" class="text-lg font-bold text-gray-900">0.00</p>
                            <p class="text-xs text-gray-500 mt-1">Skala 0-100</p>
                        </div>
                        <!-- //! checkpoint 1 -->
                        <div hidden>
                            <p class="text-xs text-gray-600 mb-1">Status Minimal 75%</p>
                            <div id="detailStatusMinimal" class="text-lg font-bold">-</div>
                            <p class="text-xs text-gray-500 mt-1">Syarat UAS</p>
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mt-4">
                    <p class="text-xs text-gray-600 mb-1">Keterangan</p>
                    <p id="detailKeterangan" class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">-</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end">
                    <button type="button" onclick="closeDetailModal()" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>