<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah Pertemuan</h2>
                    <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddPertemuan" onsubmit="createPertemuan(); return false;">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Kelas -->
                        <div>
                            <label for="addKelas" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select id="addKelas" name="kelas_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Kelas</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Pertemuan Ke -->
                            <div>
                                <label for="addPertemuanKe" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Pertemuan Ke- <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="addPertemuanKe" name="pertemuan_ke" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    min="1"
                                    max="16"
                                    placeholder="1-16"
                                    required>
                                <p class="mt-1 text-xs text-gray-500">Pertemuan 1 sampai 16</p>
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label for="addTanggal" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="addTanggal" name="tanggal" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Waktu Mulai -->
                            <div>
                                <label for="addWaktuMulai" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="addWaktuMulai" name="waktu_mulai" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>

                            <!-- Waktu Selesai -->
                            <div>
                                <label for="addWaktuSelesai" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Waktu Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="addWaktuSelesai" name="waktu_selesai" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                        </div>

                        <!-- Materi -->
                        <div>
                            <label for="addMateri" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Materi Perkuliahan
                            </label>
                            <input type="text" id="addMateri" name="materi" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                placeholder="Contoh: Pengenalan Algoritma Pemrograman">
                        </div>

                        <!-- Metode -->
                        <div>
                            <label for="addMetode" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Metode Pembelajaran <span class="text-red-500">*</span>
                            </label>
                            <select id="addMetode" name="metode"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                onchange="toggleMetodeFields('add')"
                                required>
                                <option value="">Pilih Metode</option>
                                <option value="Tatap Muka">Tatap Muka</option>
                                <option value="Daring">Daring</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>

                        <!-- Ruangan (conditional) -->
                        <div id="addRuanganField" class="hidden">
                            <label for="addRuangan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Ruangan <span class="text-red-500">*</span>
                            </label>
                            <select id="addRuangan" name="ruangan_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">Pilih Ruangan</option>
                            </select>
                        </div>

                        <!-- Link Daring (conditional) -->
                        <div id="addLinkField" class="hidden">
                            <label for="addLinkDaring" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Link Daring (Zoom/GMeet) <span class="text-red-500">*</span>
                            </label>
                            <input type="url" id="addLinkDaring" name="link_daring" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                placeholder="https://zoom.us/j/...">
                        </div>

                        <!-- Status Pertemuan -->
                        <div>
                            <label for="addStatusPertemuan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status Pertemuan <span class="text-red-500">*</span>
                            </label>
                            <select id="addStatusPertemuan" name="status_pertemuan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Status</option>
                                <option value="Dijadwalkan">Dijadwalkan</option>
                                <option value="Berlangsung">Berlangsung</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label for="addCatatan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Catatan
                            </label>
                            <textarea id="addCatatan" name="catatan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                placeholder="Catatan tambahan (opsional)"></textarea>
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
                    <button type="submit" form="formAddPertemuan"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>