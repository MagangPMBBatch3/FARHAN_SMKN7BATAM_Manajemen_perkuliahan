<div id="modalAdd" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Tambah KHS</h2>
                    <button type="button" onclick="closeAddModal()" 
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formAddKhs" onsubmit="createKhs(); return false;">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mahasiswa <span class="text-red-500">*</span>
                            </label>
                            <select id="addMahasiswaId" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       focus:border-transparent transition-all">
                                <option value="">Pilih Mahasiswa</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Semester <span class="text-red-500">*</span>
                            </label>
                            <select id="addSemesterId" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       focus:border-transparent transition-all">
                                <option value="">Pilih Semester</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    SKS Semester
                                </label>
                                <input type="number" id="addSksSemester" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-blue-500
                                           focus:border-transparent transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    SKS Kumulatif
                                </label>
                                <input type="number" id="addSksKumulatif" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-blue-500
                                           focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    IP Semester
                                </label>
                                <input type="number" step="0.01" min="0" max="4" id="addIpSemester" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-blue-500
                                           focus:border-transparent transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    IPK
                                </label>
                                <input type="number" step="0.01" min="0" max="4" id="addIPK" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                           focus:outline-none focus:ring-2 focus:ring-blue-500
                                           focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeAddModal()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white
                               border border-gray-300 rounded-lg hover:bg-gray-50
                               focus:outline-none focus:ring-2 focus:ring-offset-2
                               focus:ring-gray-500 transition-all duration-200">
                        Batal
                    </button>

                    <button type="submit" form="formAddKhs"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600
                               rounded-lg hover:bg-blue-700 focus:outline-none
                               focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                               transition-all duration-200 shadow-sm hover:shadow-md">
                        Simpan Data
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
