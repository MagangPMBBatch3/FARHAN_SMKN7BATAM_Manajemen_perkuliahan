<x-layouts.dashboard title="Detail KRS">
    <div class="bg-white p-6 rounded shadow w-full">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Detail Kartu Rencana Studi (KRS)</h1>
            <a href="/krs" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Kembali
            </a>
        </div>

        {{-- Loading State --}}
        <div id="loading" class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
            <p class="mt-2 text-gray-600">Memuat data KRS...</p>
        </div>

        {{-- Content --}}
        <div id="content" class="hidden">
            {{-- Photo & Quick Info --}}
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 mb-6 text-white">
                <div class="flex items-center gap-6">
                    <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center text-blue-600 text-4xl font-bold">
                        <span id="initial"></span>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold mb-2" id="nama"></h2>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="opacity-80">NIM</p>
                                <p class="font-semibold text-lg" id="nim"></p>
                            </div>
                            <div>
                                <p class="opacity-80">Status KRS</p>
                                <p class="font-semibold text-lg" id="statusHeader"></p>
                            </div>
                        </div>
                    </div>
                    <div class="text-right space-y-2">
                        <button onclick="approveKrs()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 block w-full transition duration-200">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Setujui KRS
                        </button>
                        <button onclick="rejectKrs()" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 block w-full transition duration-200">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tolak KRS
                        </button>
                        <button onclick="confirmDelete()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 block w-full transition duration-200">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus KRS
                        </button>
                    </div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200 transform transition hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600 mb-1 font-medium">Total SKS</p>
                            <p class="text-4xl font-bold text-blue-700" id="totalSksBesar"></p>
                        </div>
                        <div class="w-16 h-16 bg-blue-200 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg border border-green-200 transform transition hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600 mb-1 font-medium">Jumlah Mata Kuliah</p>
                            <p class="text-4xl font-bold text-green-700" id="totalMatakuliah"></p>
                        </div>
                        <div class="w-16 h-16 bg-green-200 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg border border-purple-200 transform transition hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-600 mb-1 font-medium">IP Semester</p>
                            <p class="text-4xl font-bold text-purple-700" id="ipSemesterBesar"></p>
                        </div>
                        <div class="w-16 h-16 bg-purple-200 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="border-b mb-6">
                <div class="flex gap-4">
                    <button onclick="showTab('info')" id="tabInfo" class="px-4 py-2 border-b-2 border-blue-500 text-blue-600 font-semibold transition">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Info KRS
                    </button>
                    <button onclick="showTab('matakuliah')" id="tabMatakuliah" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Daftar Mata Kuliah
                    </button>
                </div>
            </div>

            {{-- Tab Content: Info KRS --}}
            <div id="contentInfo" class="space-y-6">
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Mahasiswa
                    </h3>
                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">Nama Lengkap</label>
                            <p class="font-semibold text-gray-800" id="mahasiswaNama"></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">NIM</label>
                            <p class="font-semibold text-gray-800" id="mahasiswaNim"></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">Jurusan</label>
                            <p class="font-semibold text-gray-800" id="jurusan"></p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Detail KRS
                    </h3>
                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">ID KRS</label>
                            <p class="font-semibold text-gray-800" id="krsId"></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">Semester</label>
                            <p class="font-semibold text-gray-800" id="semester"></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">Tahun Ajaran</label>
                            <p class="font-semibold text-gray-800" id="tahunAjaran"></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">Tanggal Pengisian</label>
                            <p class="font-semibold text-gray-800" id="tanggalPengisian"></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">Status KRS</label>
                            <div id="statusKrs"></div>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">Total SKS</label>
                            <p class="font-semibold text-gray-800" id="totalSks"></p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 block mb-1">IP Semester</label>
                            <p class="font-semibold text-gray-800" id="ipSemester"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Content: Daftar Mata Kuliah --}}
            <div id="contentMatakuliah" class="space-y-6 hidden">
                <div class="bg-white rounded-lg border shadow-sm">
                    <div class="p-4 bg-gray-50 border-b">
                        <h3 class="text-lg font-semibold text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Mata Kuliah yang Diambil
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Mata Kuliah</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kelas & Jadwal</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Dosen</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">SKS</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Nilai</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="mataKuliahTableBody" class="bg-white divide-y divide-gray-200">
                                <!-- Data will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-2">Keterangan Status Pengambilan:</p>
                            <ul class="space-y-1 ml-4">
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span><strong>Baru:</strong> Mata kuliah diambil untuk pertama kali</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span><strong>Mengulang:</strong> Mata kuliah diambil kembali karena belum lulus (nilai D/E)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span><strong>Perbaikan:</strong> Mata kuliah diambil untuk memperbaiki nilai yang sudah lulus</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metadata --}}
            <div class="mt-8 pt-6 border-t">
                <div class="flex items-center text-sm text-gray-500 mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">Metadata</span>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <label class="block mb-1 flex items-center text-gray-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Dibuat pada
                        </label>
                        <p class="font-semibold text-gray-800" id="createdAt"></p>
                    </div>
                    <div>
                        <label class="block mb-1 flex items-center text-gray-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Terakhir diupdate
                        </label>
                        <p class="font-semibold text-gray-800" id="updatedAt"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Input/Edit Nilai --}}
    <div id="nilaiModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4 pb-3 border-b">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Input Nilai Mata Kuliah
                </h3>
                <button onclick="closeNilaiModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-4">
                {{-- Info Mata Kuliah --}}
                <div class="bg-blue-50 p-4 rounded-lg mb-6 border border-blue-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <label class="text-gray-600">Mata Kuliah</label>
                            <p class="font-semibold text-gray-900" id="modalMataKuliah"></p>
                        </div>
                        <div>
                            <label class="text-gray-600">Kode MK</label>
                            <p class="font-semibold text-gray-900" id="modalKodeMK"></p>
                        </div>
                        <div>
                            <label class="text-gray-600">Kelas</label>
                            <p class="font-semibold text-gray-900" id="modalKelas"></p>
                        </div>
                        <div>
                            <label class="text-gray-600">SKS</label>
                            <p class="font-semibold text-gray-900" id="modalSKS"></p>
                        </div>
                    </div>
                </div>

                {{-- Form Input Nilai --}}
                <form id="nilaiForm" class="space-y-4">
                    <input type="hidden" id="nilaiKrsDetailId">
                    <input type="hidden" id="nilaiId">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="nilaiAngka" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nilai Angka (0-100)
                                <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                id="nilaiAngka" 
                                name="nilaiAngka"
                                min="0" 
                                max="100" 
                                step="0.01"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Contoh: 85.5"
                                required
                            >
                            <p class="text-xs text-gray-500 mt-1">Masukkan nilai angka (desimal diperbolehkan)</p>
                        </div>

                        <div>
                            <label for="nilaiHuruf" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nilai Huruf
                                <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="nilaiHuruf" 
                                name="nilaiHuruf"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            >
                                <option value="">Pilih Nilai Huruf</option>
                                <option value="A">A (85-100)</option>
                                <option value="A-">A- (80-84)</option>
                                <option value="B+">B+ (75-79)</option>
                                <option value="B">B (70-74)</option>
                                <option value="B-">B- (65-69)</option>
                                <option value="C+">C+ (60-64)</option>
                                <option value="C">C (55-59)</option>
                                <option value="D">D (50-54)</option>
                                <option value="E">E (0-49)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Atau pilih nilai huruf langsung</p>
                        </div>
                    </div>

                    <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <p class="font-semibold mb-1">Konversi Otomatis:</p>
                                <p>Sistem akan otomatis menyesuaikan nilai huruf berdasarkan nilai angka yang dimasukkan.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button 
                            type="button" 
                            onclick="closeNilaiModal()"
                            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script src="{{ asset('js/admin/detailKrs/detailKrs.js') }}"></script>
</x-layouts.dashboard>