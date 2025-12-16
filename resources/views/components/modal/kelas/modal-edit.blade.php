<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Kelas</h2>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formEditKelas" onsubmit="updateKelas(); return false;">
                    @csrf
                    <input type="hidden" id="editId" name="id">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="editKode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kode Kelas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="editKode" name="kode" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div>
                            <label for="editKelas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Kelas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="editKelas" name="nama" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                        </div>

                        <div>
                            <label for="editMataKuliahId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mata Kuliah <span class="text-red-500">*</span>
                            </label>
                            <select id="editMataKuliahId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Mata Kuliah</option> 
                            </select>
                        </div>

                        <div>
                            <label for="editDosenId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Dosen <span class="text-red-500">*</span>
                            </label>
                            <select id="editDosenId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Dosen</option> 
                            </select>
                        </div>

                        <div>
                            <label for="editSemesterId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Semester <span class="text-red-500">*</span>
                            </label>
                            <select id="editSemesterId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Semester</option> 
                            </select>
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
                            <label for="editStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="editStatus" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                                <option value="Selesai">Selesai</option>
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
                    <button type="submit" form="formEditKelas"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>