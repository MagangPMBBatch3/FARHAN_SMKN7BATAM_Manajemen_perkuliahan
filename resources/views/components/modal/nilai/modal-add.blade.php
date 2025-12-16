<!-- Modal Add Nilai -->
<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah Nilai Mahasiswa</h2>
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
                                        <li>Pilih mahasiswa terlebih dahulu</li>
                                        <li>Sistem akan menampilkan mata kuliah yang diambil mahasiswa</li>
                                        <li>Hanya mata kuliah yang belum memiliki nilai yang akan ditampilkan</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Pilih Mahasiswa -->
                        <div>
                            <label for="addMahasiswa" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Pilih Mahasiswa <span class="text-red-500">*</span>
                            </label>
                            <select id="addMahasiswa" 
                                onchange="onMahasiswaChangeAdd()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Mahasiswa</option>
                            </select>
                        </div>

                        <!-- Pilih Mata Kuliah -->
                        <div>
                            <label for="addMataKuliah" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <select id="addMataKuliah" 
                                disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed" 
                                required>
                                <option value="">Pilih mahasiswa terlebih dahulu</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Hanya mata kuliah yang diambil mahasiswa dan belum memiliki nilai</p>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Nilai Komponen -->
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Nilai Komponen</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="addTugas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Tugas
                                    </label>
                                    <input type="number" id="addTugas" name="tugas" step="0.01" min="0" max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="addQuiz" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Quiz
                                    </label>
                                    <input type="number" id="addQuiz" name="quiz" step="0.01" min="0" max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="addUts" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai UTS
                                    </label>
                                    <input type="number" id="addUts" name="uts" step="0.01" min="0" max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div>
                                    <label for="addUas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai UAS
                                    </label>
                                    <input type="number" id="addUas" name="uas" step="0.01" min="0" max="100"
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
                                    <label for="addNilaiAkhir" class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nilai Akhir
                                    </label>
                                    <input type="number" id="addNilaiAkhir" name="nilai_akhir" step="0.01" min="0" max="100"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="0-100">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="addNilaiHuruf" class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Nilai Huruf
                                        </label>
                                        <select id="addNilaiHuruf" name="nilai_huruf"
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
                                        <label for="addNilaiMutu" class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Nilai Mutu
                                        </label>
                                        <input type="number" id="addNilaiMutu" name="nilai_mutu" step="0.01" min="0" max="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="0-4">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="addStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status Kelulusan <span class="text-red-500">*</span>
                            </label>
                            <select id="addStatus" name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                required>
                                <option value="">Pilih Status</option>
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