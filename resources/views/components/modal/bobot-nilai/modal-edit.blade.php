<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Bobot Nilai</h2>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formEditBobotNilai" onsubmit="updateBobotNilai(); return false;">
                    <input type="hidden" id="editId" name="id">
                    
                    <div class="space-y-4">
                        <!-- Mata Kuliah -->
                        <div>
                            <label for="editMataKuliah" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <select id="editMataKuliah" name="mata_kuliah_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Mata Kuliah</option>
                            </select>
                        </div>

                        <!-- Semester -->
                        <div>
                            <label for="editSemester" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Semester <span class="text-red-500">*</span>
                            </label>
                            <select id="editSemester" name="semester_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Semester</option>
                            </select>
                        </div>

                        <!-- Bobot Nilai -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-gray-800 mb-3">Distribusi Bobot Penilaian (%)</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Tugas -->
                                <div>
                                    <label for="editTugas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Tugas
                                    </label>
                                    <input type="number" id="editTugas" name="tugas" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        oninput="calculateTotalBobot('edit')"
                                        required>
                                </div>

                                <!-- Quiz -->
                                <div>
                                    <label for="editQuiz" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Quiz
                                    </label>
                                    <input type="number" id="editQuiz" name="quiz" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        oninput="calculateTotalBobot('edit')"
                                        required>
                                </div>

                                <!-- UTS -->
                                <div>
                                    <label for="editUTS" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        UTS
                                    </label>
                                    <input type="number" id="editUTS" name="uts" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        oninput="calculateTotalBobot('edit')"
                                        required>
                                </div>

                                <!-- UAS -->
                                <div>
                                    <label for="editUAS" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        UAS
                                    </label>
                                    <input type="number" id="editUAS" name="uas" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        oninput="calculateTotalBobot('edit')"
                                        required>
                                </div>

                                <!-- Kehadiran -->
                                <div>
                                    <label for="editKehadiran" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Kehadiran
                                    </label>
                                    <input type="number" id="editKehadiran" name="kehadiran" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        oninput="calculateTotalBobot('edit')">
                                </div>

                                <!-- Praktikum -->
                                <div>
                                    <label for="editPraktikum" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Praktikum
                                    </label>
                                    <input type="number" id="editPraktikum" name="praktikum" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        oninput="calculateTotalBobot('edit')">
                                </div>
                            </div>

                            <!-- Total Bobot -->
                            <div class="mt-4 pt-4 border-t border-blue-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-700">Total Bobot:</span>
                                    <span id="editTotalBobot" class="text-lg font-bold text-green-600">100.00%</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Total harus 100%</p>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="editKeterangan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Keterangan
                            </label>
                            <textarea id="editKeterangan" name="keterangan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                placeholder="Catatan tambahan (opsional)"></textarea>
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
                    <button type="submit" form="formEditBobotNilai"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>