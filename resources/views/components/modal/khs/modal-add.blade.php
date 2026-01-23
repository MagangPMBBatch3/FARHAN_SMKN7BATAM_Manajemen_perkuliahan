<!-- Modal Add KHS - Auto Calculate -->
<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-white">Generate KHS</h2>
                            <p class="text-blue-100 text-sm">Sistem akan menghitung otomatis dari nilai mahasiswa</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeAddModal()" 
                        class="text-blue-100 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddKhs" onsubmit="generateKhs(); return false;">
                    @csrf

                    <div class="space-y-5">
                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-1">Petunjuk:</p>
                                    <ol class="list-decimal list-inside space-y-1 text-blue-700">
                                        <li>Pilih angkatan untuk filter mahasiswa</li>
                                        <li>Pilih mahasiswa dan semester</li>
                                        <li>Sistem akan menghitung IP dan SKS otomatis berdasarkan nilai yang sudah diinput</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Angkatan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Filter Angkatan <span class="text-red-500">*</span>
                            </label>
                            <select id="addAngkatan" onchange="loadMahasiswaByAngkatan()" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       focus:border-transparent transition-all">
                                <option value="">Pilih Angkatan</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Pilih Mahasiswa -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Mahasiswa <span class="text-red-500">*</span>
                                </label>
                                <select id="addMahasiswaId" disabled required
                                    onchange="onMahasiswaSelected()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-blue-500
                                           focus:border-transparent transition-all disabled:bg-gray-100">
                                    <option value="">Pilih angkatan terlebih dahulu</option>
                                </select>
                            </div>

                            <!-- Pilih Semester -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select id="addSemesterId" disabled required
                                    onchange="calculateKHS()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-blue-500
                                           focus:border-transparent transition-all disabled:bg-gray-100">
                                    <option value="">Pilih mahasiswa terlebih dahulu</option>
                                </select>
                            </div>
                        </div>

                        <!-- Info Mahasiswa -->
                        <div id="infoMahasiswa" class="hidden bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Informasi Mahasiswa</h4>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-600">NIM:</span>
                                    <span id="infoNIM" class="font-medium text-gray-900 ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Nama:</span>
                                    <span id="infoNama" class="font-medium text-gray-900 ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Jurusan:</span>
                                    <span id="infoJurusan" class="font-medium text-gray-900 ml-2">-</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Angkatan:</span>
                                    <span id="infoAngkatan" class="font-medium text-gray-900 ml-2">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div id="loadingKHS" class="hidden text-center py-8">
                            <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="text-sm text-gray-600">Menghitung KHS...</p>
                        </div>

                        <!-- Hasil Perhitungan -->
                        <div id="hasilKHS" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Hasil Perhitungan</h4>
                            
                            <!-- Detail Mata Kuliah -->
                            <div id="detailMataKuliah" class="mb-4 max-h-48 overflow-y-auto">
                                <table class="min-w-full text-xs">
                                    <thead class="bg-yellow-100 sticky top-0">
                                        <tr>
                                            <th class="px-2 py-1 text-left">Mata Kuliah</th>
                                            <th class="px-2 py-1 text-center">SKS</th>
                                            <th class="px-2 py-1 text-center">Nilai</th>
                                            <th class="px-2 py-1 text-center">Mutu</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableDetailMK" class="divide-y divide-yellow-200">
                                        <!-- Will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Summary -->
                            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-yellow-300">
                                <div class="text-center bg-white rounded-lg p-3 border border-yellow-300">
                                    <p class="text-xs text-gray-600 mb-1">Total SKS Semester</p>
                                    <p id="resultSksSemester" class="text-2xl font-bold text-blue-600">0</p>
                                </div>
                                <div class="text-center bg-white rounded-lg p-3 border border-yellow-300">
                                    <p class="text-xs text-gray-600 mb-1">IP Semester</p>
                                    <p id="resultIpSemester" class="text-2xl font-bold text-green-600">0.00</p>
                                </div>
                                <div class="text-center bg-white rounded-lg p-3 border border-yellow-300">
                                    <p class="text-xs text-gray-600 mb-1">Total SKS Kumulatif</p>
                                    <p id="resultSksKumulatif" class="text-2xl font-bold text-blue-600">0</p>
                                </div>
                                <div class="text-center bg-white rounded-lg p-3 border border-yellow-300">
                                    <p class="text-xs text-gray-600 mb-1">IPK</p>
                                    <p id="resultIPK" class="text-2xl font-bold text-purple-600">0.00</p>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden inputs untuk submit -->
                        <input type="hidden" id="calculatedSksSemester" name="sks_semester">
                        <input type="hidden" id="calculatedSksKumulatif" name="sks_kumulatif">
                        <input type="hidden" id="calculatedIpSemester" name="ip_semester">
                        <input type="hidden" id="calculatedIPK" name="ipk">
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAddModal()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white
                               border border-gray-300 rounded-lg hover:bg-gray-50
                               focus:outline-none focus:ring-2 focus:ring-offset-2
                               focus:ring-gray-500 transition-all duration-200">
                        Batal
                    </button>

                    <button type="submit" form="formAddKhs" id="btnSaveKHS" disabled
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600
                               rounded-lg hover:bg-blue-700 focus:outline-none
                               focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                               transition-all duration-200 shadow-sm hover:shadow-md
                               disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan KHS
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>