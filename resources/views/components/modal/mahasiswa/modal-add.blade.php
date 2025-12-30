<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah Data Mahasiswa</h2>
                    <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddMahasiswa" onsubmit="createMahasiswa(); return false;">
                    @csrf
                    
                    <!-- Data Akademik -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Data Akademik</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="addNim" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    NIM <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="addNim" 
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
                            <div>
                                <label for="addAngkatan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Angkatan <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="addAngkatan" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                            <div>
                                <label for="addStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="addStatus" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Cuti">Cuti</option>
                                    <option value="Lulus">Lulus</option>
                                    <option value="DO">DO</option>
                                </select>
                            </div>
                            <div>
                                <label for="addSemester" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Semester Saat Ini <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="addSemester" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
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

                    <!-- Kontak -->
                    <div class="mb-8">
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

                    <!-- Data Orang Tua -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Data Orang Tua</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="addNamaAyah" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ayah</label>
                                <input type="text" id="addNamaAyah" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label for="addNamaIbu" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Ibu</label>
                                <input type="text" id="addNamaIbu" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label for="addNoHpOrtu" class="block text-sm font-medium text-gray-700 mb-1.5">No. HP Orang Tua</label>
                                <input type="text" id="addNoHpOrtu" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Data Akademik Lanjutan -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">Data Akademik Lanjutan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="addIpk" class="block text-sm font-medium text-gray-700 mb-1.5">IPK</label>
                                <input type="number" step="0.01" id="addIpk" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    placeholder="0.00">
                            </div>
                            <div>
                                <label for="addTotalSks" class="block text-sm font-medium text-gray-700 mb-1.5">Total SKS</label>
                                <input type="number" id="addTotalSks" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    placeholder="0">
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
                    <button type="submit" form="formAddMahasiswa"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>