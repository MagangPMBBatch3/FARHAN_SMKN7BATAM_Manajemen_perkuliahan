<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah Grade System</h2>
                    <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddGradeSystem" onsubmit="createGradeSystem(); return false;">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="addGrade" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Grade (Huruf) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="addGrade" name="grade" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                placeholder="Contoh: A, B+, C"
                                maxlength="3"
                                required>
                            <p class="mt-1 text-xs text-gray-500">Maksimal 3 karakter (A, A-, B+, dll)</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="addMinScore" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nilai Minimal <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="addMinScore" name="min_score" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    placeholder="0.00"
                                    required>
                            </div>

                            <div>
                                <label for="addMaxScore" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nilai Maksimal <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="addMaxScore" name="max_score" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    placeholder="100.00"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label for="addGradePoint" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Grade Point (Bobot) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="addGradePoint" name="grade_point" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                step="0.01"
                                min="0"
                                max="4"
                                placeholder="4.00"
                                required>
                            <p class="mt-1 text-xs text-gray-500">Skala 0.00 - 4.00</p>
                        </div>

                        <div>
                            <label for="addStatusKelulusan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status Kelulusan <span class="text-red-500">*</span>
                            </label>
                            <select id="addStatusKelulusan" name="status_kelulusan"
    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
    required>
    <option value="">Pilih Status</option>
    <option value="Lulus">Lulus</option>
    <option value="Tidak Lulus">Tidak Lulus</option>
</select>
                        </div>

                        <div>
                            <label for="addKeterangan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Keterangan
                            </label>
                            <textarea id="addKeterangan" name="keterangan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                placeholder="Contoh: Sangat Baik, Baik, Cukup, dll"></textarea>
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
                    <button type="submit" form="formAddGradeSystem"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>