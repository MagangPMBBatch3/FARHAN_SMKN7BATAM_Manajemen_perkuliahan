<div id="modalEdit" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Edit Kehadiran</h2>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="formEditKehadiran" onsubmit="updateKehadiran(); return false;">
                    <input type="hidden" id="editId" name="id">
                    
                    <div class="space-y-4">
                        <!-- Info Alert -->
                        <div class="bg-amber-50 border-l-4 border-amber-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-700">
                                        Perubahan data kehadiran akan mempengaruhi rekap kehadiran mahasiswa.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Pertemuan -->
                        <div>
                            <label for="editPertemuan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Pertemuan <span class="text-red-500">*</span>
                            </label>
                            <select id="editPertemuan" name="pertemuan_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih Pertemuan</option>
                            </select>
                        </div>

                        <!-- Mahasiswa -->
                        <div>
                            <label for="editMahasiswa" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Mahasiswa <span class="text-red-500">*</span>
                            </label>
                            <select id="editMahasiswa" name="mahasiswa_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                onchange="loadKrsDetailByMahasiswaEdit(this.value, null)"
                                required>
                                <option value="">Pilih Mahasiswa</option>
                            </select>
                        </div>

                        <!-- KRS Detail -->
                        <div>
                            <label for="editKrsDetail" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kelas yang Diambil <span class="text-red-500">*</span>
                            </label>
                            <select id="editKrsDetail" name="krs_detail_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                                required>
                                <option value="">Pilih KRS Detail</option>
                            </select>
                        </div>

                        <!-- Status Kehadiran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Status Kehadiran <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-all">
                                    <input type="radio" id="editStatusKehadiran" name="status_kehadiran_edit" value="Hadir" 
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500" required>
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Hadir</span>
                                        <span class="block text-xs text-gray-500">Mahasiswa hadir</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-all">
                                    <input type="radio" name="status_kehadiran_edit" value="Izin" 
                                        class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Izin</span>
                                        <span class="block text-xs text-gray-500">Ada izin tertulis</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-yellow-500 transition-all">
                                    <input type="radio" name="status_kehadiran_edit" value="Sakit" 
                                        class="w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Sakit</span>
                                        <span class="block text-xs text-gray-500">Ada surat sakit</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-500 transition-all">
                                    <input type="radio" name="status_kehadiran_edit" value="Alpa" 
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
                            <label for="editKeterangan" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Keterangan
                            </label>
                            <textarea id="editKeterangan" name="keterangan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
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
                    <button type="submit" form="formEditKehadiran"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Update Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// // Helper function to set radio button value in edit modal
// function setEditStatusKehadiran(value) {
//     const radios = document.getElementsByName('status_kehadiran_edit');
//     radios.forEach(radio => {
//         if (radio.value === value) {
//             radio.checked = true;
//         }
//     });
// }

// // Update openEditModal di kehadiran-edit.js untuk set radio button
// const originalOpenEditModal = openEditModal;
// openEditModal = function(item) {
//     originalOpenEditModal(item);
//     setEditStatusKehadiran(item.status_kehadiran);
// };
</script>