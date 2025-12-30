<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Edit KHS</h2>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formEditKhs" onsubmit="updateKhs(); return false;">
                    @csrf
                    <input type="hidden" id="editId" name="id">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="editMahasiswaId" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mahasiswa <span class="text-red-500">*</span>
                            </label>
                            <select id="editMahasiswaId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Mahasiswa</option> 
                            </select>
                        </div>

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

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="editSksSemester" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    SKS Semester <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="editSksSemester" name="sks_semester" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>

                            <div>
                                <label for="editSksKumulatif" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    SKS Kumulatif <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="editSksKumulatif" name="sks_kumulatif" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="editIpSemester" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    IP Semester <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="editIpSemester" name="ip_semester" step="0.01" min="0" max="4" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>

                            <div>
                                <label for="editIPK" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    IPK <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="editIPK" name="ipk" step="0.01" min="0" max="4" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                    required>
                            </div>
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
                    <button type="submit" form="formEditKhs"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>