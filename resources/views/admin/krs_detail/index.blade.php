<x-layouts.dashboard title="Detail KRS">
    <div class="max-w-7xl mx-auto">
        {{-- Loading State --}}
        <div id="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
            <p class="mt-3 text-gray-600">Memuat data KRS...</p>
        </div>

        {{-- Content --}}
        <div id="content" class="hidden space-y-6">
            {{-- Header Section --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="/admin/krs" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <h1 class="text-2xl font-semibold text-gray-900">Kartu Rencana Studi</h1>
                    </div>
                    
                    {{-- Status Badge --}}
                    <div id="statusHeader"></div>
                </div>

                {{-- Student Info --}}
                <div class="flex items-start gap-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        <span id="initial"></span>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-semibold text-gray-900 mb-1" id="nama"></h2>
                        <p class="text-gray-500 text-sm mb-3">
                            <span id="nim"></span> • <span id="jurusan"></span>
                        </p>
                        <div class="flex gap-6 text-sm">
                            <div>
                                <span class="text-gray-500">Semester</span>
                                <p class="font-medium text-gray-900" id="semester"></p>
                            </div>
                            <div>
                                <span class="text-gray-500">Tanggal Pengisian</span>
                                <p class="font-medium text-gray-900" id="tanggalPengisian"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2">
                        <button onclick="approveKrs()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui
                        </button>
                        <button onclick="rejectKrs()" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-200 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak
                        </button>
                        <button onclick="confirmDelete()" class="px-4 py-2 bg-white hover:bg-red-50 text-red-600 text-sm font-medium rounded-lg border border-red-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total SKS</p>
                            <p class="text-3xl font-bold text-gray-900" id="totalSksBesar"></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Mata Kuliah</p>
                            <p class="text-3xl font-bold text-gray-900" id="totalMatakuliah"></p>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">IP Semester</p>
                            <p class="text-3xl font-bold text-gray-900" id="ipSemesterBesar"></p>
                        </div>
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="border-b border-gray-100">
                    <div class="flex px-6">
                        <button onclick="showTab('info')" id="tabInfo" class="px-4 py-4 border-b-2 border-blue-600 text-blue-600 font-medium text-sm transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Info KRS
                        </button>
                        <button onclick="showTab('matakuliah')" id="tabMatakuliah" class="px-4 py-4 border-b-2 border-transparent text-gray-600 hover:text-gray-900 font-medium text-sm transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            Daftar Mata Kuliah
                        </button>
                    </div>
                </div>

                {{-- Tab Content: Info KRS --}}
                <div id="contentInfo" class="p-6 space-y-6">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            Informasi Mahasiswa
                        </h3>
                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Nama Lengkap</label>
                                <p class="font-medium text-gray-900" id="mahasiswaNama"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">NIM</label>
                                <p class="font-medium text-gray-900" id="mahasiswaNim"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Jurusan</label>
                                <p class="font-medium text-gray-900" id="jurusanInfo"></p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            Detail KRS
                        </h3>
                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">ID KRS</label>
                                <p class="font-medium text-gray-900" id="krsId"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Semester</label>
                                <p class="font-medium text-gray-900 semester-display"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Tahun Ajaran</label>
                                <p class="font-medium text-gray-900" id="tahunAjaran"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Tanggal Pengisian</label>
                                <p class="font-medium text-gray-900 tanggal-pengisian-display"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Status KRS</label>
                                <div id="statusKrs"></div>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Total SKS</label>
                                <p class="font-medium text-gray-900" id="totalSks"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">IP Semester</label>
                                <p class="font-medium text-gray-900" id="ipSemester"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Tanggal Persetujuan</label>
                                <p class="font-medium text-gray-900" id="tanggalPersetujuan">-</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Dosen PA</label>
                                <p class="font-medium text-gray-900" id="dosenPa">-</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Catatan</label>
                                <p class="font-medium text-gray-900" id="catatan">-</p>
                            </div>
                        </div>
                    </div>

                    {{-- Metadata --}}
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-semibold text-gray-500 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Metadata
                        </h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Dibuat pada</label>
                                <p class="font-medium text-gray-900" id="createdAt"></p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Terakhir diupdate</label>
                                <p class="font-medium text-gray-900" id="updatedAt"></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab Content: Daftar Mata Kuliah --}}
                <div id="contentMatakuliah" class="hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900">Daftar Mata Kuliah</h3>
                        <button onclick="openAddKrsDetailModal()" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Mata Kuliah
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mata Kuliah</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kelas & Jadwal</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dosen</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">SKS</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Nilai</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="mataKuliahTableBody" class="divide-y divide-gray-100">
                                <!-- Data will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-blue-50 border-t border-blue-100">
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-sm text-blue-900">
                                <p class="font-medium mb-2">Keterangan Status Pengambilan:</p>
                                <ul class="space-y-1 text-blue-800">
                                    <li><strong>Baru:</strong> Mata kuliah diambil untuk pertama kali</li>
                                    <li><strong>Mengulang:</strong> Mata kuliah diambil kembali karena belum lulus (nilai D/E)</li>
                                    <li><strong>Perbaikan:</strong> Mata kuliah diambil untuk memperbaiki nilai yang sudah lulus</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('components.modal.krsDetail.modal-add')
    @include('components.modal.krsDetail.modal-edit')

    {{-- Scripts --}}
    <script src="{{ asset('js/admin/detailKrs/detailKrs.js') }}"></script>
    <script src="{{ asset('js/admin/detailKrs/detailKrs-create.js') }}"></script>
    <script src="{{ asset('js/admin/detailKrs/detailKrs-edit.js') }}"></script>
</x-layouts.dashboard>