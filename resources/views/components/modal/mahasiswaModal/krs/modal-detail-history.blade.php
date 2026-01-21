<!-- Modal Detail History KRS -->
<div id="modalDetailHistory" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-xl bg-white mb-10">
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                Detail KRS
            </h3>
            <button onclick="closeDetailModal()" 
                class="text-gray-400 hover:text-gray-600 transition-colors rounded-lg p-2 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <div class="mt-6 space-y-6">
            <!-- Info KRS -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Semester</p>
                    <p class="font-semibold text-gray-900" id="detailSemester">-</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Status</p>
                    <p class="font-semibold text-gray-900" id="detailStatus">-</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Total SKS</p>
                    <p class="font-semibold text-gray-900" id="detailTotalSks">-</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">IP Semester</p>
                    <p class="font-semibold text-gray-900" id="detailIpSemester">-</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Tanggal Pengisian</p>
                    <p class="font-semibold text-gray-900" id="detailTanggalPengisian">-</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Tanggal Persetujuan</p>
                    <p class="font-semibold text-gray-900" id="detailTanggalPersetujuan">-</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-500 mb-1">Dosen PA</p>
                    <p class="font-semibold text-gray-900" id="detailDosenPa">-</p>
                </div>
            </div>

            <!-- Catatan (if any) -->
            <div id="detailCatatanSection" class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                <p class="text-xs text-yellow-800 font-medium mb-1">Catatan:</p>
                <p class="text-sm text-yellow-900" id="detailCatatan">-</p>
            </div>

            <!-- Table Mata Kuliah -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                    <h4 class="font-semibold text-gray-900">Daftar Mata Kuliah</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mata Kuliah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kelas & Jadwal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dosen</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">SKS</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Nilai</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody" class="divide-y divide-gray-100">
                            <!-- Data will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Info -->
            <div class="p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Keterangan Nilai:</p>
                        <p>A = 4.00 | A- = 3.75 | B+ = 3.50 | B = 3.00 | B- = 2.75 | C+ = 2.50 | C = 2.00 | D = 1.00 | E = 0.00</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
            <button onclick="closeDetailModal()" 
                class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                Tutup
            </button>
            <button onclick="window.print()" 
                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak KRS
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #modalDetailHistory, #modalDetailHistory * {
        visibility: visible;
    }
    #modalDetailHistory {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white;
    }
    #modalDetailHistory button {
        display: none;
    }
}
</style>