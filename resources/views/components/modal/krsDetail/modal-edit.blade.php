<!-- Modal Edit KRS Detail -->
<div id="modalEditKrsDetail" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white mb-10">
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Mata Kuliah KRS
            </h3>
            <button onclick="closeEditKrsDetailModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="formEditKrsDetail" onsubmit="submitEditKrsDetail(event)" class="mt-6">
            <!-- Info Mata Kuliah (Read-only) -->
            <div class="mb-6 p-5 bg-gray-50 rounded-lg border border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Mata Kuliah
                </h4>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Nama Mata Kuliah</label>
                        <p id="editKrsDetailNamaMk" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Kode MK</label>
                        <p id="editKrsDetailKodeMk" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">SKS</label>
                        <p id="editKrsDetailSksMk" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                </div>
            </div>

            <!-- Kelas Saat Ini -->
            <div class="mb-6 p-5 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Kelas Saat Ini
                </h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Nama Kelas</label>
                        <p id="editKrsDetailCurrentKelas" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Dosen</label>
                        <p id="editKrsDetailCurrentDosen" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                </div>
            </div>

            <!-- Pindah Kelas (Optional) -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pindah ke Kelas Lain (opsional)
                </label>
                <select 
                    id="editKrsDetailKelasId"
                    onchange="onEditKelasChange()"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">-- Tetap di kelas saat ini --</option>
                </select>
                <p class="mt-2 text-sm text-gray-500">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kosongkan jika tidak ingin pindah kelas
                </p>
            </div>

            <!-- Info Kelas Baru (Hidden by default) -->
            <div id="editKrsDetailNewKelasInfo" class="hidden mb-6 p-5 bg-green-50 rounded-lg border border-green-200">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kelas Baru
                </h4>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Dosen</label>
                        <p id="editKrsDetailNewDosen" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Jadwal</label>
                        <p id="editKrsDetailNewJadwal" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Sisa Kuota</label>
                        <p id="editKrsDetailNewKuota" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                </div>
            </div>

            <!-- Status Ambil -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Status Pengambilan <span class="text-red-500">*</span>
                </label>
                <select 
                    id="editKrsDetailStatusAmbil"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    required>
                    <option value="BARU">Baru</option>
                    <option value="MENGULANG">Mengulang</option>
                </select>
                <div class="mt-3 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-sm text-yellow-800">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-semibold mb-1">Keterangan Status:</p>
                            <ul class="list-disc list-inside space-y-1 ml-1">
                                <li><strong>Baru:</strong> Mata kuliah diambil untuk pertama kali</li>
                                <li><strong>Mengulang:</strong> Mengulang karena nilai sebelumnya D/E</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div id="editKrsDetailError" class="hidden mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm"></span>
                </div>
            </div>

            <!-- Info Message -->
            <div id="editKrsDetailInfo" class="hidden mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 rounded">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm"></span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <button 
                    type="button"
                    onclick="closeEditKrsDetailModal()"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Batal
                </button>
                <button 
                    type="submit"
                    id="btnSubmitEditKrsDetail"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>