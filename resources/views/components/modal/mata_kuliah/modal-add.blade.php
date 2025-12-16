<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah Mata Kuliah</h2>
                    <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddMataKuliah" onsubmit="createMataKuliah(); return false;">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="addKode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kode Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="addKode" name="kode" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div>
                            <label for="addMataKuliah" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="addMataKuliah" name="nama" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div>
                            <label for="addJurusanId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jurusan <span class="text-red-500">*</span>
                            </label>
                            <select id="addJurusanId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Jurusan</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="addSks" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    SKS <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="addSks" name="sks" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>

                            <div>
                                <label for="addRekomendasi" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Semester Rekomendasi <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="addRekomendasi" name="rekomendasi" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                        </div>

                        <div>
                            <label for="addJenis" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <select id="addJenis" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Jenis Mata Kuliah</option>
                                <option value="Wajib">Wajib</option>
                                <option value="Pilihan">Pilihan</option>
                            </select>
                        </div>

                        <div>
                            <label for="addDeskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="addDeskripsi" name="deskripsi" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none" 
                                required></textarea>
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
                    <button type="submit" form="formAddMataKuliah"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>