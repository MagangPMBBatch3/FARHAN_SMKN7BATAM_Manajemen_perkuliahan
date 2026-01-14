<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah Pengaturan Kehadiran</h2>
                    <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddPengaturanKehadiran" onsubmit="createPengaturanKehadiran(); return false;">
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
                                        Atur aturan kehadiran untuk kelas. Minimal kehadiran biasanya 75% untuk bisa mengikuti UAS.
                                    </p>
                                </div>
                            </div>
                        </div>

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

                        <!-- Minimal Kehadiran -->
                        <div>
                            <label for="addMinimalKehadiran" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Minimal Kehadiran (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="addMinimalKehadiran" name="minimal_kehadiran" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all pr-12" 
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    value="75"
                                    placeholder="75.00"
                                    required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Persentase minimal kehadiran untuk memenuhi syarat UAS (0-100)</p>
                        </div>

                        <!-- Visual Range Indicator -->
                        <div class="bg-gradient-to-r from-red-100 via-yellow-100 to-green-100 rounded-lg p-4">
                            <div class="flex justify-between text-xs font-medium mb-2">
                                <span class="text-red-700">Rendah</span>
                                <span class="text-yellow-700">Sedang</span>
                                <span class="text-green-700">Tinggi</span>
                            </div>
                            <div class="relative pt-1">
                                <div class="flex mb-2 items-center justify-between">
                                    <div class="text-xs font-semibold inline-block text-gray-700">0%</div>
                                    <div class="text-xs font-semibold inline-block text-gray-700">50%</div>
                                    <div class="text-xs font-semibold inline-block text-gray-700">100%</div>
                                </div>
                            </div>
                        </div>

                        <!-- Auto Generate Pertemuan -->
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="addAutoGenerate" name="auto_generate_pertemuan" type="checkbox" checked
                                        class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                </div>
                                <div class="ml-3">
                                    <label for="addAutoGenerate" class="font-medium text-gray-900 text-sm">Auto Generate Pertemuan</label>
                                    <p class="text-xs text-gray-600 mt-1">
                                        Otomatis membuat data pertemuan sesuai jadwal kuliah yang telah ditentukan
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="addAktif" name="aktif" type="checkbox" checked
                                        class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                </div>
                                <div class="ml-3">
                                    <label for="addAktif" class="font-medium text-gray-900 text-sm">Aktifkan Pengaturan</label>
                                    <p class="text-xs text-gray-600 mt-1">
                                        Pengaturan ini akan langsung berlaku untuk kelas yang dipilih
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="addKeterangan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Keterangan
                            </label>
                            <textarea id="addKeterangan" name="keterangan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                placeholder="Catatan tambahan tentang aturan kehadiran (opsional)"></textarea>
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
                    <button type="submit" form="formAddPengaturanKehadiran"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>