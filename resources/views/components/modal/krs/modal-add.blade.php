<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah KRS</h2>
                    <button type="button" onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddKrs" onsubmit="event.preventDefault(); createKrs();">
                    <div class="space-y-4">
                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium">Pilih mahasiswa secara berurutan:</p>
                                    <p class="text-blue-700">Fakultas → Jurusan → Mahasiswa</p>
                                </div>
                            </div>
                        </div>

                        <!-- Cascading Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Fakultas -->
                            <div>
                                <label for="addFakultasId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Fakultas <span class="text-red-500">*</span>
                                </label>
                                <select id="addFakultasId" 
                                    onchange="loadJurusanByFakultasAdd(this.value)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    required>
                                    <option value="">Pilih Fakultas</option>
                                </select>
                            </div>

                            <!-- Jurusan -->
                            <div>
                                <label for="addJurusanId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Jurusan <span class="text-red-500">*</span>
                                </label>
                                <select id="addJurusanId" 
                                    onchange="loadMahasiswaByJurusanAdd(this.value)"
                                    disabled
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed"
                                    required>
                                    <option value="">Pilih fakultas dulu</option>
                                </select>
                            </div>

                            <!-- Search Mahasiswa -->
                            <div>
                                <label for="addMahasiswaSearch" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Cari Mahasiswa
                                </label>
                                <input type="text" 
                                    id="addMahasiswaSearch" 
                                    placeholder="NIM atau Nama..."
                                    oninput="searchMahasiswaAdd(this.value)"
                                    disabled
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Mahasiswa Dropdown -->
                        <div>
                            <label for="addMahasiswaId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mahasiswa <span class="text-red-500">*</span>
                            </label>
                            <select id="addMahasiswaId" 
                                disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed"
                                required>
                                <option value="">Pilih jurusan dulu</option>
                            </select>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Semester & Tanggal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="addSemesterId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select id="addSemesterId" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                                    <option value="">Pilih Semester</option>
                                </select>
                            </div>

                            <div>
                                <label for="addPengisian" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal Pengisian <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="addPengisian" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                        </div>

                        <!-- Status & Dosen -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="addStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="addStatus" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                                    <option value="">Pilih Status</option>
                                    <option value="Draft">Draft</option>
                                    <option value="Diajukan">Diajukan</option>
                                    <option value="Disetujui">Disetujui</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>

                            <div>
                                <label for="addDosenId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Dosen Wali <span class="text-red-500">*</span>
                                </label>
                                <select id="addDosenId" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                                    <option value="">Pilih Dosen</option>
                                </select>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label for="addCatatan" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan</label>
                            <textarea id="addCatatan" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
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
                    <button type="submit" form="formAddKrs"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>