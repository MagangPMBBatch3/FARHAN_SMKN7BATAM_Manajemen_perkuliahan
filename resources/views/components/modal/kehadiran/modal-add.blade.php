<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah Kehadiran</h2>
                    <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddKehadiran" onsubmit="createKehadiran(); return false;">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Info Alert -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Pilih pertemuan dan mahasiswa untuk mencatat kehadiran. Pastikan mahasiswa sudah terdaftar di KRS.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Pertemuan -->
                        <div>
                            <label for="addPertemuan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Pertemuan <span class="text-red-500">*</span>
                            </label>
                            <select id="addPertemuan" name="pertemuan_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Pertemuan</option>
                            </select>
                        </div>

                        <!-- Mahasiswa -->
                        <div>
                            <label for="addMahasiswa" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mahasiswa <span class="text-red-500">*</span>
                            </label>
                            <select id="addMahasiswa" name="mahasiswa_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                onchange="loadKrsDetailByMahasiswa(this.value)"
                                required>
                                <option value="">Pilih Mahasiswa</option>
                            </select>
                        </div>

                        <!-- KRS Detail -->
                        <div>
                            <label for="addKrsDetail" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kelas yang Diambil <span class="text-red-500">*</span>
                            </label>
                            <select id="addKrsDetail" name="krs_detail_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih KRS Detail</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Pilih mahasiswa terlebih dahulu untuk melihat kelas yang diambil</p>
                        </div>

                        <!-- Status Kehadiran -->
                        <div>
                            <label for="addStatusKehadiran" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status Kehadiran <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-all">
                                    <input type="radio" id="addStatusKehadiran" name="status_kehadiran" value="Hadir" 
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500" required>
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Hadir</span>
                                        <span class="block text-xs text-gray-500">Mahasiswa hadir</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-all">
                                    <input type="radio" name="status_kehadiran" value="Izin" 
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Izin</span>
                                        <span class="block text-xs text-gray-500">Ada izin tertulis</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-yellow-500 transition-all">
                                    <input type="radio" name="status_kehadiran" value="Sakit" 
                                        class="w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Sakit</span>
                                        <span class="block text-xs text-gray-500">Ada surat sakit</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition-all">
                                    <input type="radio" name="status_kehadiran" value="Alpa" 
                                        class="w-4 h-4 text-red-600 focus:ring-red-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Alpa</span>
                                        <span class="block text-xs text-gray-500">Tidak hadir tanpa keterangan</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="addKeterangan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Keterangan
                            </label>
                            <textarea id="addKeterangan" name="keterangan" rows="3"
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
                    <button type="submit" form="formAddKehadiran"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>