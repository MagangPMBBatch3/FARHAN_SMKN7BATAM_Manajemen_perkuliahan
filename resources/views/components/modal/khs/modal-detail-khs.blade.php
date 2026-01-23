<!-- Modal Detail KHS -->
<div id="modalDetail" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-600 to-green-700 print:hidden">
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
                <div class="print-section px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-b-2 border-blue-600">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-2 print:w-8 print:h-8">
                                <svg class="w-6 h-6 text-white print:w-5 print:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 print:text-base">POLITEKNIK BATAM</h3>
                                <p class="text-xs text-gray-600 print:text-[10px]">Kartu Hasil Studi</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-600">Tanggal Cetak:</p>
                            <p id="printDate" class="text-xs font-semibold text-gray-900 print:text-[10px]">-</p>
                        </div>
                    </div>

                    <!-- Info Mahasiswa -->
                    <div class="bg-white rounded-lg p-2 grid grid-cols-2 gap-2 print:p-1.5">
                        <div>
                            <div class="mb-1">
                                <span class="text-[10px] text-gray-600">NIM</span>
                                <p id="detailNIM" class="text-xs font-semibold text-gray-900 print:text-[10px]">-</p>
                            </div>
                            <div class="mb-1">
                                <span class="text-[10px] text-gray-600">Nama Mahasiswa</span>
                                <p id="detailNama" class="text-xs font-semibold text-gray-900 print:text-[10px]">-</p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-1">
                                <span class="text-[10px] text-gray-600">Program Studi</span>
                                <p id="detailProdi" class="text-xs font-semibold text-gray-900 print:text-[10px]">-</p>
                            </div>
                            <div class="mb-1">
                                <span class="text-[10px] text-gray-600">Semester</span>
                                <p id="detailSemester" class="text-xs font-semibold text-gray-900 print:text-[10px]">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Nilai -->
                <div class="print-section px-4 py-3">
                    <h4 class="text-sm font-semibold text-gray-800 mb-2 print:text-xs print:mb-1">Daftar Nilai Mata Kuliah</h4>
                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 print:text-[9px]">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-2 py-1.5 text-left text-[10px] font-medium text-gray-500 uppercase tracking-wider print:px-1 print:py-1 print:text-[8px]">
                                        No</th>
                                    <th class="px-2 py-1.5 text-left text-[10px] font-medium text-gray-500 uppercase tracking-wider print:px-1 print:py-1 print:text-[8px]">
                                        Kode MK</th>
                                    <th class="px-2 py-1.5 text-left text-[10px] font-medium text-gray-500 uppercase tracking-wider print:px-1 print:py-1 print:text-[8px]">
                                        Mata Kuliah</th>
                                    <th class="px-2 py-1.5 text-center text-[10px] font-medium text-gray-500 uppercase tracking-wider print:px-1 print:py-1 print:text-[8px]">
                                        SKS</th>
                                    <th class="px-2 py-1.5 text-center text-[10px] font-medium text-gray-500 uppercase tracking-wider print:px-1 print:py-1 print:text-[8px]">
                                        Nilai Angka</th>
                                    <th class="px-2 py-1.5 text-center text-[10px] font-medium text-gray-500 uppercase tracking-wider print:px-1 print:py-1 print:text-[8px]">
                                        Nilai Huruf</th>
                                    <th class="px-2 py-1.5 text-center text-[10px] font-medium text-gray-500 uppercase tracking-wider print:px-1 print:py-1 print:text-[8px]">
                                        Bobot</th>
                                    <th class="px-2 py-1.5 text-center text-[10px] font-medium text-gray-500 uppercase tracking-wider print:px-1 print:py-1 print:text-[8px]">
                                        SKS × Bobot</th>
                                </tr>
                            </thead>
                            <tbody id="detailTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                            <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                                <tr>
                                    <td colspan="3" class="px-2 py-1.5 text-right text-xs font-bold text-gray-900 print:px-1 print:py-1 print:text-[9px]">TOTAL:</td>
                                    <td class="px-2 py-1.5 text-center text-xs font-bold text-gray-900 print:px-1 print:py-1 print:text-[9px]" id="totalSKS">0</td>
                                    <td colspan="3"></td>
                                    <td class="px-2 py-1.5 text-center text-xs font-bold text-gray-900 print:px-1 print:py-1 print:text-[9px]" id="totalBobot">0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="print-section px-4 py-2">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <div class="bg-blue-50 border border-blue-200 rounded p-2 text-center print:p-1">
                            <p class="text-[10px] text-blue-700 mb-0.5 print:text-[8px]">SKS Semester</p>
                            <p id="summarySksSemester" class="text-lg font-bold text-blue-600 print:text-sm">0</p>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded p-2 text-center print:p-1">
                            <p class="text-[10px] text-green-700 mb-0.5 print:text-[8px]">IP Semester</p>
                            <p id="summaryIpSemester" class="text-lg font-bold text-green-600 print:text-sm">0.00</p>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded p-2 text-center print:p-1">
                            <p class="text-[10px] text-purple-700 mb-0.5 print:text-[8px]">Total SKS</p>
                            <p id="summarySksKumulatif" class="text-lg font-bold text-purple-600 print:text-sm">0</p>
                        </div>
                        <div class="bg-orange-50 border border-orange-200 rounded p-2 text-center print:p-1">
                            <p class="text-[10px] text-orange-700 mb-0.5 print:text-[8px]">IPK</p>
                            <p id="summaryIPK" class="text-lg font-bold text-orange-600 print:text-sm">0.00</p>
                        </div>
                    </div>

                    <!-- Predikat -->
                    <div class="mt-2 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-300 rounded p-2 print:mt-1 print:p-1.5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-gray-600 mb-0.5 print:text-[8px]">Predikat Kelulusan:</p>
                                <p id="predikat" class="text-base font-bold text-gray-900 print:text-xs">-</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-600 print:text-[8px]">Status:</p>
                                <span id="statusLulus" class="inline-block px-2 py-1 rounded-full text-[10px] font-semibold print:px-1.5 print:py-0.5 print:text-[8px]">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="print-section px-4 py-3 print:py-2">
                    <div class="border-t border-gray-300 pt-3 grid grid-cols-3 gap-4 text-center print:pt-2 print:gap-2">
                        <div>
                            <p class="text-[10px] text-gray-600 mb-8 print:text-[8px] print:mb-6">Mengetahui,</p>
                            <div class="border-t border-gray-400 pt-1">
                                <p class="text-xs font-semibold print:text-[9px]">Ketua Program Studi</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-600 mb-8 print:text-[8px] print:mb-6">Menyetujui,</p>
                            <div class="border-t border-gray-400 pt-1">
                                <p class="text-xs font-semibold print:text-[9px]">Dosen Pembimbing Akademik</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-600 mb-8 print:text-[8px] print:mb-6">Mahasiswa,</p>
                            <div class="border-t border-gray-400 pt-1">
                                <p id="footerNama" class="text-xs font-semibold print:text-[9px]">-</p>
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
        /* Reset semua elemen */
        body * {
            visibility: hidden;
        }

        /* Tampilkan hanya area yang ingin dicetak */
        #printableArea,
        #printableArea * {
            visibility: visible;
        }

        /* Posisikan area cetak */
        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0.5cm;
        }

        /* Sembunyikan elemen dengan class print:hidden */
        .print\\:hidden {
            display: none !important;
        }

        /* Atur ukuran halaman A4 dengan margin minimal */
        @page {
            size: A4;
            margin: 0.8cm 0.6cm;
        }

        /* Optimasi font sizes untuk print */
        .print\\:text-\\[8px\\] {
            font-size: 8px !important;
            line-height: 1.2 !important;
        }

        .print\\:text-\\[9px\\] {
            font-size: 9px !important;
            line-height: 1.3 !important;
        }

        .print\\:text-\\[10px\\] {
            font-size: 10px !important;
            line-height: 1.3 !important;
        }

        .print\\:text-xs {
            font-size: 11px !important;
            line-height: 1.4 !important;
        }

        .print\\:text-sm {
            font-size: 12px !important;
            line-height: 1.4 !important;
        }

        .print\\:text-base {
            font-size: 13px !important;
            line-height: 1.4 !important;
        }

        /* Optimasi padding untuk print */
        .print\\:p-1 {
            padding: 0.25rem !important;
        }

        .print\\:p-1\\.5 {
            padding: 0.375rem !important;
        }

        .print\\:px-1 {
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
        }

        .print\\:py-1 {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }

        .print\\:py-2 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .print\\:px-1\\.5 {
            padding-left: 0.375rem !important;
            padding-right: 0.375rem !important;
        }

        .print\\:py-0\\.5 {
            padding-top: 0.125rem !important;
            padding-bottom: 0.125rem !important;
        }

        /* Optimasi margin untuk print */
        .print\\:mb-1 {
            margin-bottom: 0.25rem !important;
        }

        .print\\:mt-1 {
            margin-top: 0.25rem !important;
        }

        .print\\:mb-6 {
            margin-bottom: 1.5rem !important;
        }

        .print\\:gap-2 {
            gap: 0.5rem !important;
        }

        /* Optimasi size untuk print */
        .print\\:w-8 {
            width: 2rem !important;
        }

        .print\\:h-8 {
            height: 2rem !important;
        }

        .print\\:w-5 {
            width: 1.25rem !important;
        }

        .print\\:h-5 {
            height: 1.25rem !important;
        }

        /* Hindari page break di dalam elemen tertentu */
        .print-section {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Pastikan tabel tidak terputus di tengah */
        table {
            page-break-inside: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Paksa page break sebelum elemen tertentu jika diperlukan */
        .page-break-before {
            page-break-before: always;
            break-before: page;
        }

        /* Paksa page break setelah elemen tertentu jika diperlukan */
        .page-break-after {
            page-break-after: always;
            break-after: page;
        }

        /* Hindari widow dan orphan */
        p, h1, h2, h3, h4, h5, h6 {
            orphans: 3;
            widows: 3;
        }

        /* Pastikan header dan konten selalu bersama */
        h1, h2, h3, h4, h5, h6 {
            page-break-after: avoid;
            break-after: avoid;
        }

        /* Kompres spacing untuk tabel */
        table td, table th {
            padding: 2px 4px !important;
        }

        /* Reduce border spacing */
        .border-t-2 {
            border-top-width: 1px !important;
        }

        .border-b-2 {
            border-bottom-width: 1px !important;
        }
    }
</style>

<script>
// Update script untuk render table body dengan ukuran yang lebih kecil
function renderTableRow(nilai, index) {
    const sks = nilai.krsDetail.mataKuliah.sks;
    const mutu = parseFloat(nilai.nilai_mutu || 0);
    const sksXBobot = sks * mutu;

    return `
        <tr class="hover:bg-gray-50">
            <td class="px-2 py-2 text-xs text-gray-900 print:px-1 print:py-1 print:text-[9px]">${index + 1}</td>
            <td class="px-2 py-2 text-xs font-medium text-gray-900 print:px-1 print:py-1 print:text-[9px]">${nilai.krsDetail.mataKuliah.kode_mk}</td>
            <td class="px-2 py-2 text-xs text-gray-900 print:px-1 print:py-1 print:text-[9px]">${nilai.krsDetail.mataKuliah.nama_mk}</td>
            <td class="px-2 py-2 text-xs text-center font-semibold text-gray-900 print:px-1 print:py-1 print:text-[9px]">${sks}</td>
            <td class="px-2 py-2 text-xs text-center text-gray-900 print:px-1 print:py-1 print:text-[9px]">${nilai.nilai_akhir || '-'}</td>
            <td class="px-2 py-2 text-xs text-center print:px-1 print:py-1">
                <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded-full print:px-1 print:py-0 print:text-[8px] ${getGradeColorForDetail(nilai.nilai_huruf)}">
                    ${nilai.nilai_huruf || '-'}
                </span>
            </td>
            <td class="px-2 py-2 text-xs text-center font-semibold text-gray-900 print:px-1 print:py-1 print:text-[9px]">${mutu.toFixed(2)}</td>
            <td class="px-2 py-2 text-xs text-center font-bold text-blue-600 print:px-1 print:py-1 print:text-[9px]">${sksXBobot.toFixed(2)}</td>
        </tr>
    `;
}
</script>