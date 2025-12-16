<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah Data Dosen</h2>
                    <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddDosen" onsubmit="createDosen(); return false;">
                    @csrf
                    
                    <!-- Data Identitas -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Data Identitas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="addNidn" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    NIDN <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="addNidn" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                            <div>
                                <label for="addNip" class="block text-sm font-medium text-gray-700 mb-1.5">NIP</label>
                                <input type="text" id="addNip" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label for="addUserId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    User ID <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="addUserId" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pribadi -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Data Pribadi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="addGelarDepan" class="block text-sm font-medium text-gray-700 mb-1.5">Gelar Depan</label>
                                <input type="text" id="addGelarDepan" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    placeholder="Dr., Prof., dll">
                            </div>
                            <div>
                                <label for="addGelarBelakang" class="block text-sm font-medium text-gray-700 mb-1.5">Gelar Belakang</label>
                                <input type="text" id="addGelarBelakang" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    placeholder="S.Kom., M.T., Ph.D., dll">
                            </div>
                            <div class="md:col-span-2">
                                <label for="addNamaLengkap" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="addNamaLengkap" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                            <div>
                                <label for="addJenisKelamin" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select id="addJenisKelamin" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label for="addTanggalLahir" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                                <input type="date" id="addTanggalLahir" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label for="addTempatLahir" class="block text-sm font-medium text-gray-700 mb-1.5">Tempat Lahir</label>
                                <input type="text" id="addTempatLahir" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label for="addAlamat" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat</label>
                                <textarea id="addAlamat" rows="2" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Data Kepegawaian -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Data Kepegawaian</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                            <div>
                                <label for="addStatusKepegawaian" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Status Kepegawaian <span class="text-red-500">*</span>
                                </label>
                                <select id="addStatusKepegawaian" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                                    <option value="">Pilih</option>
                                    <option value="PNS">PNS</option>
                                    <option value="CPNS">CPNS</option>
                                    <option value="HONORER">Honorer</option>
                                    <option value="KONTRAK">Kontrak</option>
                                    <option value="TETAP">Tetap</option>
                                </select>
                            </div>
                            <div>
                                <label for="addJabatan" class="block text-sm font-medium text-gray-700 mb-1.5">Jabatan</label>
                                <input type="text" id="addJabatan" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    placeholder="Dosen, Lektor, dll">
                            </div>
                            <div>
                                <label for="addStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="addStatus" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                                    <option value="AKTIF">Aktif</option>
                                    <option value="CUTI">Cuti</option>
                                    <option value="PENSIUN">Pensiun</option>
                                    <option value="NONAKTIF">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Kontak -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Kontak</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="addNoHp" class="block text-sm font-medium text-gray-700 mb-1.5">No. HP</label>
                                <input type="text" id="addNoHp" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label for="addEmailPribadi" class="block text-sm font-medium text-gray-700 mb-1.5">Email Pribadi</label>
                                <input type="email" id="addEmailPribadi" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
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
                    <button type="submit" form="formAddDosen"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>