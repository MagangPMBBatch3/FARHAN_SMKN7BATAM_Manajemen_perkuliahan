<!-- Modal Edit Nilai -->
<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
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
                    
                    <div class="space-y-5">
                        <!-- Info Mahasiswa & Mata Kuliah (Read-only) -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="space-y-3">
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
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-300">
                                <p class="text-xs text-gray-600 flex items-start">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Mahasiswa dan mata kuliah tidak dapat diubah. Jika ingin mengubah, silakan hapus dan buat data nilai baru.</span>
                                </p>
                            </div>
                        </div>

                        <!-- Nilai Komponen -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Nilai Komponen</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="editTugas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Tugas
                                    </label>
                                    <input type="number" id="editTugas" name="tugas" step="0.01" min="0" max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="editQuiz" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Quiz
                                    </label>
                                    <input type="number" id="editQuiz" name="quiz" step="0.01" min="0" max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="editUts" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai UTS
                                    </label>
                                    <input type="number" id="editUts" name="uts" step="0.01" min="0" max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="editUas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai UAS
                                    </label>
                                    <input type="number" id="editUas" name="uas" step="0.01" min="0" max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>
                            </div>
                        </div>

                        <!-- Nilai Akhir & Konversi -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Nilai Akhir & Konversi</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="editNilaiAkhir" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Akhir
                                    </label>
                                    <input type="number" id="editNilaiAkhir" name="nilai_akhir" step="0.01" min="0" max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="editNilaiHuruf" class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Nilai Huruf
                                        </label>
                                        <select id="editNilaiHuruf" name="nilai_huruf"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                            <option value="">Pilih Nilai Huruf</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                            <option value="E">E</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="editNilaiMutu" class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Nilai Mutu
                                        </label>
                                        <input type="number" id="editNilaiMutu" name="nilai_mutu" step="0.01" min="0" max="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="0-4">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="editStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status Kelulusan <span class="text-red-500">*</span>
                            </label>
                            <select id="editStatus" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                required>
                                <option value="">Pilih Status</option>
                                <option value="Lulus">Lulus</option>
                                <option value="Tidak Lulus">Tidak Lulus</option>
                                <option value="Pending">Pending</option>
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