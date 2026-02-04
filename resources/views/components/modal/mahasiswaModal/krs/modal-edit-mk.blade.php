<!-- Modal Edit Mata Kuliah -->
<div id="modalEditMk" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-xl bg-white mb-10">
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                Edit Mata Kuliah
            </h3>
            <button onclick="closeEditMkModal()" 
                class="text-gray-400 hover:text-gray-600 transition-colors rounded-lg p-2 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="formEditMk" onsubmit="submitEditMk(event)" class="mt-6">
            <!-- Info Mata Kuliah (Read-only) -->
            <div class="mb-6 p-5 bg-gray-50 rounded-lg border border-gray-200">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    Mata Kuliah yang Dipilih
                </h4>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide font-medium">Nama Mata Kuliah</label>
                        <p id="editMkNamaMk" class="font-semibold text-gray-900 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide font-medium">Kode MK</label>
                        <p id="editMkKodeMk" class="font-semibold text-gray-900 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide font-medium">SKS</label>
                        <p id="editMkSksMk" class="font-semibold text-gray-900 mt-1">-</p>
                    </div>
                </div>
            </div>

            <!-- Kelas Saat Ini -->
            <div class="mb-6 p-5 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-200 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    Kelas Saat Ini
                </h4>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide font-medium">Nama Kelas</label>
                        <p id="editMkCurrentKelas" class="font-semibold text-gray-900 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide font-medium">Dosen</label>
                        <p id="editMkCurrentDosen" class="font-semibold text-gray-900 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide font-medium">Jadwal</label>
                        <p id="editMkCurrentJadwal" class="font-semibold text-gray-900 mt-1 text-sm">-</p>
                    </div>
                </div>
            </div>

            <!-- Pindah Kelas (Optional) -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pindah ke Kelas Lain
                    <span class="text-gray-500 font-normal text-xs ml-2">(opsional)</span>
                </label>
                <select 
                    id="editMkKelasId"
                    onchange="onEditMkKelasChange()"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all text-sm">
                    <option value="">-- Tetap di kelas saat ini --</option>
                </select>
                <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kosongkan jika tidak ingin pindah kelas
                </p>
            </div>

            <!-- Info Kelas Baru (Hidden by default) -->
            <div id="editMkNewKelasInfo" class="hidden mb-6 p-5 bg-green-50 rounded-lg border border-green-200">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-200 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Kelas Baru
                </h4>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide font-medium">Dosen</label>
                        <p id="editMkNewDosen" class="font-semibold text-gray-900 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide font-medium">Jadwal</label>
                        <p id="editMkNewJadwal" class="font-semibold text-gray-900 mt-1 text-sm">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide font-medium">Sisa Kuota</label>
                        <p id="editMkNewKuota" class="font-semibold text-gray-900 mt-1">-</p>
                    </div>
                </div>
            </div>

            <!-- Status Ambil -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Status Pengambilan <span class="text-red-500">*</span>
                </label>
                <select 
                    id="editMkStatusAmbil"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all text-sm"
                    required>
                    <option value="Baru">Baru</option>
                    <option value="Mengulang">Mengulang</option>
                    <option value="Perbaikan">Perbaikan</option>
                </select>
                <div class="mt-3 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg text-sm">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-yellow-800">
                            <p class="font-semibold mb-2">Keterangan Status:</p>
                            <ul class="list-disc list-inside space-y-1 ml-1">
                                <li><strong>Baru:</strong> Mata kuliah diambil untuk pertama kali</li>
                                <li><strong>Mengulang:</strong> Mengulang karena nilai sebelumnya D/E</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div id="editMkError" class="hidden mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-red-800"></span>
                </div>
            </div>

            <!-- Info Message -->
            <div id="editMkInfo" class="hidden mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-blue-800"></span>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
                <button 
                    type="button"
                    onclick="closeEditMkModal()"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Batal
                </button>
                <button 
                    type="submit"
                    id="btnSubmitEditMk"
                    class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
