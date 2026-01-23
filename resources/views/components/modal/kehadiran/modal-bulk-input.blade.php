<div id="modalBulkInput" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-white">Input Kehadiran Massal</h2>
                        <p class="text-sm text-blue-100 mt-1">Isi kehadiran untuk seluruh mahasiswa dalam satu pertemuan
                        </p>
                    </div>
                    <button type="button" onclick="closeBulkInputModal()"
                        class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formBulkInput" onsubmit="submitBulkKehadiran(); return false;">

                    <!-- Info Alert -->
                    <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Tips Penggunaan:</h3>
                                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside space-y-1">
                                    <li>Pilih pertemuan untuk memuat daftar mahasiswa yang terdaftar</li>
                                    <li>Gunakan tombol "Set Semua Hadir" untuk efisiensi, lalu ubah yang tidak hadir
                                    </li>
                                    <li>Keterangan akan muncul otomatis untuk status Izin, Sakit, dan Alpa</li>
                                    <li>Data yang sudah ada akan ditampilkan dan bisa diupdate</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Pilih Pertemuan -->
                    <div class="mb-6">
                        <label for="bulkPertemuan" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Pertemuan <span class="text-red-500">*</span>
                        </label>
                        <select id="bulkPertemuan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            onchange="loadMahasiswaByPertemuan(this.value)" required>
                            <option value="">-- Pilih Pertemuan --</option>
                        </select>
                    </div>

                    <!-- Daftar Mahasiswa -->
                    <div id="mahasiswaListContainer" class="space-y-3">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Catatan:</span> Pastikan semua status kehadiran sudah terisi dengan
                        benar
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeBulkInputModal()"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit" form="formBulkInput"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Semua Data
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>