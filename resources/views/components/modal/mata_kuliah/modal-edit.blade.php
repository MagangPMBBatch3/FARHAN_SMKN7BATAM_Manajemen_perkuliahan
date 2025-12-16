<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Mata Kuliah</h2>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formEditMataKuliah" onsubmit="updateMataKuliah(); return false;">
                    @csrf
                    <input type="hidden" id="editId" name="id">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="editKode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kode Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="editKode" name="kode" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div>
                            <label for="editMataKuliah" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="editMataKuliah" name="nama" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div>
                            <label for="editJurusanId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jurusan <span class="text-red-500">*</span>
                            </label>
                            <select id="editJurusanId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Jurusan</option> 
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="editSks" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    SKS <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="editSks" name="sks" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>

                            <div>
                                <label for="editRekomendasi" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Semester Rekomendasi <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="editRekomendasi" name="rekomendasi" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                        </div>

                        <div>
                            <label for="editJenis" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <select id="editJenis" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Jenis Mata Kuliah</option>
                                <option value="Wajib">Wajib</option>
                                <option value="Pilihan">Pilihan</option>
                            </select>
                        </div>

                        <div>
                            <label for="editDeskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="editDeskripsi" name="deskripsi" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none" 
                                required></textarea>
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
                    <button type="submit" form="formEditMataKuliah"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>