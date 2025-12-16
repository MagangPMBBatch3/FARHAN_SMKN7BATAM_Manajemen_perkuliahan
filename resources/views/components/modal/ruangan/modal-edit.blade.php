<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Ruangan</h2>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formEditRuangan" onsubmit="updateRuangan(); return false;">
                    @csrf
                    <input type="hidden" id="editId" name="id">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="editKode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kode Ruangan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="editKode" name="kode" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div>
                            <label for="editRuangan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Ruangan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="editRuangan" name="nama" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="editGedung" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Gedung <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="editGedung" name="gedung" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>

                            <div>
                                <label for="editLantai" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Lantai <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="editLantai" name="lantai" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                        </div>

                        <div>
                            <label for="editKapasitas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kapasitas <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="editKapasitas" name="kapasitas" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div>
                            <label for="editJenis" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Jenis Ruangan <span class="text-red-500">*</span>
                            </label>
                            <select id="editJenis" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Jenis Ruangan</option>
                                <option value="Kelas">Kelas</option>
                                <option value="Lab">Lab</option>
                                <option value="Aula">Aula</option>
                                <option value="Seminar">Seminar</option>
                            </select>
                        </div>

                        <div>
                            <label for="editFasilitas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Fasilitas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="editFasilitas" name="fasilitas" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                placeholder="Contoh: AC, Proyektor, Whiteboard" 
                                required>
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
                    <button type="submit" form="formEditRuangan"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>