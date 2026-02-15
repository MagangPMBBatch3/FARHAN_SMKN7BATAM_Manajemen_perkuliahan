<!-- Modal Edit Nilai - Improved Version -->
<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Nilai Mahasiswa</h2>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formEditNilai" onsubmit="updateNilai(); return false;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">
                    <input type="hidden" id="editKrsDetailId" name="krs_detail_id">
                    <input type="hidden" id="editBobotNilaiId" name="bobot_nilai_id">
                    
                    <div class="space-y-5">
                        <!-- Info Mahasiswa & Mata Kuliah (Read-only) -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                                        Mahasiswa
                                    </label>
                                    <p id="editMahasiswaDisplay" class="text-sm font-semibold text-gray-900">-</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                                        Mata Kuliah
                                    </label>
                                    <p id="editMataKuliahDisplay" class="text-sm font-semibold text-gray-900">-</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                                        Kelas
                                    </label>
                                    <p id="editKelasDisplay" class="text-sm font-semibold text-gray-900">-</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">
                                        Semester
                                    </label>
                                    <p id="editSemesterDisplay" class="text-sm font-semibold text-gray-900">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Info Bobot -->
                        <div id="editInfoBobot" class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Bobot Penilaian</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tugas:</span>
                                    <span id="editBobotTugas" class="font-medium text-indigo-600">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Quiz:</span>
                                    <span id="editBobotQuiz" class="font-medium text-indigo-600">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">UTS:</span>
                                    <span id="editBobotUTS" class="font-medium text-indigo-600">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">UAS:</span>
                                    <span id="editBobotUAS" class="font-medium text-indigo-600">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kehadiran:</span>
                                    <span id="editBobotKehadiran" class="font-medium text-indigo-600">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Praktikum:</span>
                                    <span id="editBobotPraktikum" class="font-medium text-indigo-600">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Nilai Komponen -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Input Nilai Komponen (0-100)</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="editTugas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Tugas <span id="editLabelBobotTugas" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="editTugas" name="tugas" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhirEdit()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="editQuiz" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Quiz <span id="editLabelBobotQuiz" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="editQuiz" name="quiz" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhirEdit()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="editUts" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai UTS <span id="editLabelBobotUTS" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="editUts" name="uts" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhirEdit()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="editUas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai UAS <span id="editLabelBobotUAS" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="editUas" name="uas" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhirEdit()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="editKehadiran" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Kehadiran <span id="editLabelBobotKehadiran" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="editKehadiran" name="kehadiran" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhirEdit()"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="editPraktikum" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Praktikum <span id="editLabelBobotPraktikum" class="text-xs text-gray-500"></span>
                                    </label>
                                    <input type="number" id="editPraktikum" name="praktikum" step="0.01" min="0" max="100"
                                        oninput="hitungNilaiAkhirEdit()"
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
                                    <input type="number" id="editNilaiAkhir" name="nilai_akhir" step="0.01" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 font-bold text-lg text-center text-blue-600"
                                        placeholder="0.00">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Huruf
                                    </label>
                                    <input type="text" id="editNilaiHuruf" name="nilai_huruf" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 font-bold text-lg text-center text-green-600"
                                        placeholder="-">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Mutu
                                    </label>
                                    <input type="number" id="editNilaiMutu" name="nilai_mutu" step="0.01" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 font-bold text-lg text-center text-purple-600"
                                        placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="editStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="editStatus" name="status" 
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
                    <button type="button" onclick="closeEditModal()" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" form="formEditNilai"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>