<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Edit KRS</h2>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form onsubmit="event.preventDefault(); createKrs();">
                    <input type="hidden" id="editId">
                    
                    <div class="space-y-4">
                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium">Filter mahasiswa:</p>
                                    <p class="text-blue-700">Fakultas → Jurusan → Mahasiswa</p>
                                </div>
                            </div>
                        </div>

                        <!-- Cascading Filters -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Fakultas -->
                            <div>
                                <label for="editFakultasId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Fakultas <span class="text-red-500">*</span>
                                </label>
                                <select id="editFakultasId" 
                                    onchange="loadJurusanByFakultasEdit(this.value)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    required>
                                    <option value="">Pilih Fakultas</option>
                                </select>
                            </div>

                            <!-- Jurusan -->
                            <div>
                                <label for="editJurusanId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Jurusan <span class="text-red-500">*</span>
                                </label>
                                <select id="editJurusanId" 
                                    onchange="loadMahasiswaByJurusanEdit(this.value)"
                                    disabled
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed"
                                    required>
                                    <option value="">Pilih fakultas dulu</option>
                                </select>
                            </div>

                            <!-- Search Mahasiswa -->
                            <div>
                                <label for="editMahasiswaSearch" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Cari Mahasiswa
                                </label>
                                <input type="text" 
                                    id="editMahasiswaSearch" 
                                    placeholder="NIM atau Nama..."
                                    oninput="searchMahasiswaEdit(this.value)"
                                    disabled
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed">
                            </div>
                        </div>

                        <!-- Mahasiswa Dropdown -->
                        <div>
                            <label for="editMahasiswaId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mahasiswa <span class="text-red-500">*</span>
                            </label>
                            <select id="editMahasiswaId" 
                                disabled
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed"
                                required>
                                <option value="">Pilih jurusan dulu</option>
                            </select>
                        </div>

                        <hr class="border-gray-200">

                        <!-- Semester & Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                <label for="editTotalSks" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Total SKS <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="editTotalSks" step="1" min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editPengisian" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal Pengisian <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="editPengisian" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>

                            <div>
                                <label for="editPersetujuan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal Persetujuan
                                </label>
                                <input type="date" id="editPersetujuan" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                        </div>

                        <!-- Status & Dosen -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editStatus" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="editStatus" 
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
                                <label for="editDosenId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Dosen Wali <span class="text-red-500">*</span>
                                </label>
                                <select id="editDosenId" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                                    <option value="">Pilih Dosen</option>
                                </select>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label for="editCatatan" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan</label>
                            <textarea id="editCatatan" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                placeholder="Catatan tambahan (opsional)"></textarea>
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
                    <button type="submit" form="formEditKrs"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>