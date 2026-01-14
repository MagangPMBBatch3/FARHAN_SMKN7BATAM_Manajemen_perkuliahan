<!-- Modal Bulk Input Nilai -->
<div id="modalBulk" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-7xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-white">Input Nilai Massal</h2>
                            <p class="text-blue-100 text-sm">Input nilai untuk seluruh mahasiswa dalam satu kelas</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeBulkModal()" class="text-blue-100 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Pilih Semester -->
                    <div>
                        <label for="bulkSemester" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select id="bulkSemester" 
                            onchange="onBulkSemesterChange()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Pilih Semester</option>
                        </select>
                    </div>

                    <!-- Pilih Kelas -->
                    <div>
                        <label for="bulkKelas" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select id="bulkKelas" 
                            onchange="loadBulkMahasiswaList()"
                            disabled
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100">
                            <option value="">Pilih semester terlebih dahulu</option>
                        </select>
                    </div>
                </div>

                <!-- Info Bobot -->
                <div id="bulkInfoBobot" class="hidden mt-4 bg-white border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Informasi Mata Kuliah & Bobot</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm mb-3">
                                <div>
                                    <span class="text-gray-600">Kode MK:</span>
                                    <span id="bulkKodeMK" class="font-medium text-gray-900 ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Nama MK:</span>
                                    <span id="bulkNamaMK" class="font-medium text-gray-900 ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">SKS:</span>
                                    <span id="bulkSKS" class="font-medium text-gray-900 ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Dosen:</span>
                                    <span id="bulkDosen" class="font-medium text-gray-900 ml-2">-</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 md:grid-cols-6 gap-2 text-xs">
                                <div class="bg-blue-50 px-2 py-1 rounded">
                                    <span class="text-gray-600">Tugas:</span>
                                    <span id="bulkBobotTugas" class="font-semibold text-blue-700 ml-1">-</span>
                                </div>
                                <div class="bg-blue-50 px-2 py-1 rounded">
                                    <span class="text-gray-600">Quiz:</span>
                                    <span id="bulkBobotQuiz" class="font-semibold text-blue-700 ml-1">-</span>
                                </div>
                                <div class="bg-blue-50 px-2 py-1 rounded">
                                    <span class="text-gray-600">UTS:</span>
                                    <span id="bulkBobotUTS" class="font-semibold text-blue-700 ml-1">-</span>
                                </div>
                                <div class="bg-blue-50 px-2 py-1 rounded">
                                    <span class="text-gray-600">UAS:</span>
                                    <span id="bulkBobotUAS" class="font-semibold text-blue-700 ml-1">-</span>
                                </div>
                                <div class="bg-blue-50 px-2 py-1 rounded">
                                    <span class="text-gray-600">Hadir:</span>
                                    <span id="bulkBobotKehadiran" class="font-semibold text-blue-700 ml-1">-</span>
                                </div>
                                <div class="bg-blue-50 px-2 py-1 rounded">
                                    <span class="text-gray-600">Praktikum:</span>
                                    <span id="bulkBobotPraktikum" class="font-semibold text-blue-700 ml-1">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body - Tabel Input -->
            <div class="flex-1 overflow-y-auto px-6 py-4">
                <div id="bulkTableContainer" class="hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="bulkInputTable">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">No</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Tugas</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Quiz</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">UTS</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">UAS</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Hadir</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Praktikum</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24 bg-yellow-50">Nilai Akhir</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16 bg-green-50">Huruf</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16 bg-blue-50">Mutu</th>
                                </tr>
                            </thead>
                            <tbody id="bulkTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="bulkEmptyState" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data</h3>
                    <p class="mt-1 text-sm text-gray-500">Pilih semester dan kelas untuk mulai input nilai</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        <span id="bulkTotalMahasiswa">0</span> mahasiswa akan diinput
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeBulkModal()" 
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            Batal
                        </button>
                        <button type="button" onclick="saveBulkNilai()"
                            id="btnSaveBulk"
                            disabled
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Semua Nilai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>