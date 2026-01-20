<div id="modalDetail" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold">Detail Pertemuan</h2>
                        <p class="text-sm text-blue-100 mt-1" id="detailMataKuliah">-</p>
                    </div>
                    <button type="button" onclick="closeDetailModal()" 
                        class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <div class="space-y-5">
                    <!-- Informasi Mata Kuliah -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Informasi Mata Kuliah
                        </h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-600 mb-1">Kode MK</p>
                                <p id="detailKodeMK" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-1">Kelas</p>
                                <p id="detailKelas" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-1">Dosen Pengampu</p>
                                <p id="detailDosen" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600 mb-1">Semester</p>
                                <p id="detailSemester" class="font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pertemuan -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Jadwal & Waktu
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-32">
                                    <p class="text-sm text-gray-600">Pertemuan</p>
                                </div>
                                <div class="flex-1">
                                    <p id="detailPertemuan" class="text-sm font-semibold text-gray-900">-</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-32">
                                    <p class="text-sm text-gray-600">Tanggal</p>
                                </div>
                                <div class="flex-1">
                                    <p id="detailTanggal" class="text-sm font-semibold text-gray-900">-</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-32">
                                    <p class="text-sm text-gray-600">Waktu</p>
                                </div>
                                <div class="flex-1">
                                    <p id="detailWaktu" class="text-sm font-semibold text-gray-900">-</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-32">
                                    <p class="text-sm text-gray-600">Metode</p>
                                </div>
                                <div class="flex-1">
                                    <div id="detailMetode">-</div>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-32">
                                    <p class="text-sm text-gray-600">Status</p>
                                </div>
                                <div class="flex-1">
                                    <div id="detailStatus">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Materi -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Materi Perkuliahan
                        </h3>
                        <p id="detailMateri" class="text-sm text-gray-900">-</p>
                    </div>

                    <!-- Ruangan -->
                    <div id="detailRuanganContainer" class="bg-white border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Lokasi Ruangan
                        </h3>
                        <p id="detailRuangan" class="text-sm font-semibold text-gray-900">-</p>
                    </div>

                    <!-- Link Daring -->
                    <div id="detailLinkContainer" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Link Meeting Online
                        </h3>
                        <a id="detailLink" href="#" target="_blank" 
                            class="text-sm text-blue-600 hover:text-blue-800 hover:underline break-all">
                            -
                        </a>
                    </div>

                    <!-- Catatan -->
                    <div id="detailCatatanContainer" class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            Catatan
                        </h3>
                        <p id="detailCatatan" class="text-sm text-gray-900">-</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end">
                    <button type="button" onclick="closeDetailModal()" 
                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>