<!-- Modal Add KRS Detail -->
<div id="modalAddKrsDetail" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-lg bg-white mb-10">
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Mata Kuliah ke KRS
            </h3>
            <button onclick="closeAddKrsDetailModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form id="formAddKrsDetail" onsubmit="submitAddKrsDetail(event)" class="mt-6">
            <!-- Pilih Mata Kuliah -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Mata Kuliah <span class="text-red-500">*</span>
                </label>
                <select 
                    id="addKrsDetailMataKuliahId" 
                    onchange="onMataKuliahChange()"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    required>
                    <option value="">-- Pilih Mata Kuliah --</option>
                </select>
                <p class="mt-2 text-sm text-gray-500">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Hanya menampilkan mata kuliah sesuai semester rekomendasi dan belum diambil
                </p>
            </div>

            <!-- Info Mata Kuliah (Hidden by default) -->
            <div id="addKrsDetailInfoSection" class="hidden mb-6 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Mata Kuliah
                </h4>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Kode Mata Kuliah</label>
                        <p id="addKrsDetailKodeMk" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">SKS</label>
                        <p id="addKrsDetailSksMk" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                </div>

                <!-- Pilih Kelas -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="addKrsDetailKelasId"
                        onchange="onKelasChange()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required
                        disabled>
                        <option value="">-- Pilih Mata Kuliah Terlebih Dahulu --</option>
                    </select>
                </div>

                <!-- Detail Kelas -->
                <div class="grid grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Dosen Pengampu</label>
                        <p id="addKrsDetailDosen" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Jadwal</label>
                        <p id="addKrsDetailJadwal" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-600 uppercase tracking-wide">Sisa Kuota</label>
                        <p id="addKrsDetailKuota" class="font-semibold text-gray-800 mt-1">-</p>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div id="addKrsDetailError" class="hidden mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm"></span>
                </div>
            </div>

            <!-- Info Message -->
            <div id="addKrsDetailInfo" class="hidden mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 rounded">
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
                    onclick="closeAddKrsDetailModal()"
                    class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Batal
                </button>
                <button 
                    type="submit"
                    id="btnSubmitAddKrsDetail"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambahkan ke KRS
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slide-out {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

.animate-slide-in {
    animation: slide-in 0.3s ease-out;
}

.animate-slide-out {
    animation: slide-out 0.3s ease-in;
}
</style>