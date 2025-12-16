<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Jadwal</h2>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formEditJadwal" onsubmit="updateJadwal(); return false;">
                    @csrf
                    <input type="hidden" id="editId" name="id"> 
                    
                    <div class="space-y-4">
                        <div>
                            <label for="editKelasId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select id="editKelasId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Kelas</option>
                            </select>
                        </div>

                        <div>
                            <label for="editRuanganId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Ruangan <span class="text-red-500">*</span>
                            </label>
                            <select id="editRuanganId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Ruangan</option>
                            </select>
                        </div>

                        <div>
                            <label for="editHari" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Hari <span class="text-red-500">*</span>
                            </label>
                            <select id="editHari" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Hari</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="editMulai" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Jam Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="editMulai" name="jam_mulai" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>

                            <div>
                                <label for="editSelesai" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Jam Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="editSelesai" name="jam_selesai" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                        </div>

                        <div>
                            <label for="editKeterangan" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                            <textarea id="editKeterangan" name="keterangan" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"></textarea>
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
                    <button type="submit" form="formEditJadwal"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>