<x-layouts.dashboard title="Kartu Rencana Studi (KRS) Saya">
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Kartu Rencana Studi</h1>
                    <p class="text-blue-100">Kelola rencana studi Anda untuk semester ini</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-100">Semester Aktif</p>
                    <p class="text-xl font-semibold" id="semesterAktif">-</p>
                </div>
            </div>
        </div>
        <p id="headerNIM" class="text-white font-bold text-lg">-</p>

        {{-- Info Mahasiswa & Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Profile Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:col-span-2">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                        <span id="initialMhs"></span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900" id="namaMhs"></h3>
                        <p class="text-sm text-gray-500 mt-1">
                            <span id="nimMhs"></span> • <span id="jurusanMhs"></span>
                        </p>
                        <div class="flex gap-4 mt-3 text-sm">
                            <div>
                                <span class="text-gray-500">Semester</span>
                                <p class="font-semibold text-gray-900" id="semesterMhs"></p>
                            </div>
                            <div>
                                <span class="text-gray-500">IPK</span>
                                <p class="font-semibold text-gray-900" id="ipkMhs"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total SKS Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500">Total SKS</p>
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900" id="totalSksKrs">0</p>
                <p class="text-xs text-gray-500 mt-1">Maks: <span id="maxSksKrs">24</span> SKS</p>
            </div>

            {{-- Total Matakuliah Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-500">Mata Kuliah</p>
                    <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900" id="totalMkKrs">0</p>
                <p class="text-xs text-gray-500 mt-1">Terdaftar</p>
            </div>
        </div>

        {{-- Alert Status KRS --}}
        <div id="alertStatusKrs" class="hidden"></div>

        {{-- Main Content --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Action Bar --}}
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Mata Kuliah</h2>
                        <p class="text-sm text-gray-500 mt-1">Kelola mata kuliah yang akan Anda ambil</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="openAddMkModal()" id="btnAddMk"
                            class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Mata Kuliah
                        </button>
                        <button onclick="submitKrs()" id="btnSubmitKrs"
                            class="px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ajukan KRS
                        </button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mata Kuliah</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dosen</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jadwal</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">SKS</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableKrsBody" class="divide-y divide-gray-100">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            {{-- Empty State --}}
            <div id="emptyState" class="hidden p-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Mata Kuliah</h3>
                    <p class="text-gray-500 mb-6">Mulai tambahkan mata kuliah yang ingin Anda ambil</p>
                    <button onclick="openAddMkModal()" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Mata Kuliah
                    </button>
                </div>
            </div>
        </div>

        {{-- Info Panel --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Panduan --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Panduan Pengisian KRS
                </h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Pilih mata kuliah sesuai semester rekomendasi</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Perhatikan batas maksimal SKS berdasarkan IPK Anda</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Pastikan tidak ada jadwal yang bentrok</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Ajukan KRS sebelum batas waktu yang ditentukan</span>
                    </li>
                </ul>
            </div>

            {{-- Status Keterangan --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Keterangan Status
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-semibold">DRAFT</span>
                        <span class="text-gray-600">Masih dalam proses penyusunan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">DIAJUKAN</span>
                        <span class="text-gray-600">Menunggu persetujuan dosen PA</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">DISETUJUI</span>
                        <span class="text-gray-600">Sudah disetujui dosen PA</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">DITOLAK</span>
                        <span class="text-gray-600">Ditolak, perlu revisi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('components.modal.mahasiswaModal.krs.modal-add-mk')
    @include('components.modal.mahasiswaModal.krs.modal-edit-mk')

    {{-- Scripts --}}
    <script src="{{ asset('js/mahasiswa/krs/krs.js') }}"></script>
    <script src="{{ asset('js/mahasiswa/krs/krs-manage.js') }}"></script>
</x-layouts.dashboard>