<!-- Modal Add Nilai - Improved Version -->
<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Input Nilai Mahasiswa</h2>
                    <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddNilai" onsubmit="createNilai(); return false;">
                    @csrf
                    
                    <div class="space-y-5">
                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-1">Petunjuk Pengisian:</p>
                                    <ol class="list-decimal list-inside space-y-1 text-blue-700">
                                        <li>Pilih kelas terlebih dahulu</li>
                                        <li>Pilih mahasiswa dari kelas tersebut</li>
                                        <li>Sistem akan otomatis menghitung nilai akhir berdasarkan bobot</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Section -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Pilih Semester -->
                            <div>
                                <label for="addSemester" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select id="addSemester" 
                                    onchange="onSemesterChange()"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                                    <option value="">Pilih Semester</option>
                                </select>
                            </div>

                            <!-- Pilih Kelas -->
                            <div>
                                <label for="addKelas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kelas <span class="text-red-500">*</span>
                                </label>
                                <select id="addKelas" 
                                    onchange="onKelasChange()"
                                    disabled
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed" 
                                    required>
                                    <option value="">Pilih semester terlebih dahulu</option>
                                </select>
                            </div>

                            <!-- Pilih Mahasiswa -->
                            <div>
                                <label for="addMahasiswa" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Mahasiswa <span class="text-red-500">*</span>
                                </label>
                                <select id="addMahasiswa" 
                                    onchange="onMahasiswaChangeImproved()"
                                    disabled
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed" 
                                    required>
                                    <option value="">Pilih kelas terlebih dahulu</option>
                                </select>
                            </div>
                        </div>

                        <!-- Info Mata Kuliah & Bobot -->
                        <div id="infoMataKuliah" class="hidden bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Informasi Mata Kuliah</h4>
                                    <div class="space-y-1 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kode MK:</span>
                                            <span id="infoKodeMK" class="font-medium text-gray-900">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Nama MK:</span>
                                            <span id="infoNamaMK" class="font-medium text-gray-900">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">SKS:</span>
                                            <span id="infoSKS" class="font-medium text-gray-900">-</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Bobot Penilaian</h4>
                                    <div class="space-y-1 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Tugas:</span>
                                            <span id="bobotTugas" class="font-medium text-indigo-600">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Quiz:</span>
                                            <span id="bobotQuiz" class="font-medium text-indigo-600">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">UTS:</span>
                                            <span id="bobotUTS" class="font-medium text-indigo-600">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">UAS:</span>
                                            <span id="bobotUAS" class="font-medium text-indigo-600">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Kehadiran:</span>
                                            <span id="bobotKehadiran" class="font-medium text-indigo-600">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Praktikum:</span>
                                            <span id="bobotPraktikum" class="font-medium text-indigo-600">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="addKrsDetailId" name="krs_detail_id">
                        <input type="hidden" id="addBobotNilaiId" name="bobot_nilai_id">

                        <hr class="border-gray-200">

                        <!-- Nilai Komponen -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Input Nilai Komponen (0-100)</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="addTugas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Tugas <span id="labelBobotTugas" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="addTugas" name="tugas" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhir()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="addQuiz" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Quiz <span id="labelBobotQuiz" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="addQuiz" name="quiz" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhir()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="addUts" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai UTS <span id="labelBobotUTS" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="addUts" name="uts" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhir()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="addUas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai UAS <span id="labelBobotUAS" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="addUas" name="uas" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhir()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="addKehadiran" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Kehadiran <span id="labelBobotKehadiran" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="addKehadiran" name="kehadiran" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhir()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="addPraktikum" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Praktikum <span id="labelBobotPraktikum" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="addPraktikum" name="praktikum" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhir()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>
                            </div>
                        </div>

                        <!-- Nilai Akhir & Konversi -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Hasil Perhitungan (Otomatis)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Akhir
                                    </label>
                                    <input type="number" id="addNilaiAkhir" name="nilai_akhir" step="0.01" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 font-bold text-lg text-center text-blue-600"
                                        placeholder="0.00">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Huruf
                                    </label>
                                    <input type="text" id="addNilaiHuruf" name="nilai_huruf" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 font-bold text-lg text-center text-green-600"
                                        placeholder="-">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Mutu
                                    </label>
                                    <input type="number" id="addNilaiMutu" name="nilai_mutu" step="0.01" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 font-bold text-lg text-center text-purple-600"
                                        placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="addStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="addStatus" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                required>
                                <option value="Draft">Draft</option>
                                <option value="Final">Final</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAddModal()" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" form="formAddNilai"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>