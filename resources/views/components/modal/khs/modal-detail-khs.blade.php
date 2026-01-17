<!-- Modal Detail KHS -->
<div id="modalDetail" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-white">Kartu Hasil Studi (KHS)</h2>
                            <p class="text-green-100 text-sm">Detail nilai per mata kuliah</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="printKHS()"
                            class="px-4 py-2 text-sm font-medium text-green-700 bg-white rounded-lg hover:bg-green-50 focus:outline-none transition-all">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Print
                        </button>
                        <button type="button" onclick="closeDetailModal()"
                            class="text-green-100 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto" id="printableArea">
                <!-- Header KHS -->
                <div class="px-6 py-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b-4 border-blue-600">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">POLITEKNIK BATAM</h3>
                                <p class="text-sm text-gray-600">Kartu Hasil Studi</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Tanggal Cetak:</p>
                            <p id="printDate" class="font-semibold text-gray-900">-</p>
                        </div>
                    </div>

                    <!-- Info Mahasiswa -->
                    <div class="bg-white rounded-lg p-4 grid grid-cols-2 gap-4">
                        <div>
                            <div class="mb-2">
                                <span class="text-xs text-gray-600">NIM</span>
                                <p id="detailNIM" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div class="mb-2">
                                <span class="text-xs text-gray-600">Nama Mahasiswa</span>
                                <p id="detailNama" class="font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-2">
                                <span class="text-xs text-gray-600">Program Studi</span>
                                <p id="detailProdi" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div class="mb-2">
                                <span class="text-xs text-gray-600">Semester</span>
                                <p id="detailSemester" class="font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Nilai -->
                <div class="px-6 py-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Daftar Nilai Mata Kuliah</h4>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode MK</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Mata Kuliah</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        SKS</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai Angka</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai Huruf</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Bobot</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        SKS × Bobot</th>
                                </tr>
                            </thead>
                            <tbody id="detailTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                            <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-900">TOTAL:</td>
                                    <td class="px-4 py-3 text-center font-bold text-gray-900" id="totalSKS">0</td>
                                    <td colspan="3"></td>
                                    <td class="px-4 py-3 text-center font-bold text-gray-900" id="totalBobot">0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="px-6 pb-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-blue-700 mb-1">SKS Semester</p>
                            <p id="summarySksSemester" class="text-2xl font-bold text-blue-600">0</p>
                        </div>
                        <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-green-700 mb-1">IP Semester</p>
                            <p id="summaryIpSemester" class="text-2xl font-bold text-green-600">0.00</p>
                        </div>
                        <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-purple-700 mb-1">Total SKS</p>
                            <p id="summarySksKumulatif" class="text-2xl font-bold text-purple-600">0</p>
                        </div>
                        <div class="bg-orange-50 border-2 border-orange-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-orange-700 mb-1">IPK</p>
                            <p id="summaryIPK" class="text-2xl font-bold text-orange-600">0.00</p>
                        </div>
                    </div>

                    <!-- Predikat -->
                    <div
                        class="mt-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Predikat Kelulusan:</p>
                                <p id="predikat" class="text-2xl font-bold text-gray-900">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600">Status:</p>
                                <span id="statusLulus"
                                    class="inline-block px-4 py-2 rounded-full text-sm font-semibold">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 pb-6">
                    <div class="border-t-2 border-gray-300 pt-6 grid grid-cols-3 gap-8 text-center">
                        <div>
                            <p class="text-sm text-gray-600 mb-12">Mengetahui,</p>
                            <div class="border-t-2 border-gray-400 pt-2">
                                <p class="font-semibold">Ketua Program Studi</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-12">Menyetujui,</p>
                            <div class="border-t-2 border-gray-400 pt-2">
                                <p class="font-semibold">Dosen Pembimbing Akademik</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-12">Mahasiswa,</p>
                            <div class="border-t-2 border-gray-400 pt-2">
                                <p id="footerNama" class="font-semibold">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 print:hidden">
                <div class="flex justify-end">
                    <button type="button" onclick="closeDetailModal()"
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        Tutup
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #printableArea,
        #printableArea * {
            visibility: visible;
        }

        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .print\\:hidden {
            display: none !important;
        }
    }
</style>