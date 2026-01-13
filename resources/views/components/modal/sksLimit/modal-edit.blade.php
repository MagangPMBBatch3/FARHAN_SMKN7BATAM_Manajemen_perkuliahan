<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Batas SKS</h2>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-6">
                <form id="formEditSksLimit" onsubmit="updateSksLimit(); return false;">
                    <input type="hidden" id="editId">
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    IPK Minimal <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="editMinIpk" step="0.01" min="0" max="4" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    IPK Maksimal <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="editMaxIpk" step="0.01" min="0" max="4" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                SKS Maksimal <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="editMaxSks" min="1" max="30" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" 
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Keterangan
                            </label>
                            <textarea id="editKeterangan" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end gap-3">
                    <button onclick="closeEditModal()" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" form="formEditSksLimit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>