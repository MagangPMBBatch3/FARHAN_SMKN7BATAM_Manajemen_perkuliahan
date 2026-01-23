<!-- File: resources/views/admin/kelas/detail.blade.php -->
<x-layouts.dashboard title="Detail Kelas">
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="/admin/kelas" class="submenu-item {{ request()->is('admin/kelas') ? 'active' : '' }}"
                           class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div class="h-6 w-px bg-gray-300"></div>
                        <h1 class="text-2xl font-bold text-gray-900" id="kelasTitle">Detail Kelas</h1>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openEditModalFromDetail()" 
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Kelas
                        </button>
                        <button onclick="printDetail()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </div>

            {{-- Informasi Umum Kelas --}}
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Kelas
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Kode Kelas</p>
                        <p class="text-lg font-semibold text-gray-900" id="detailKodeKelas">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Nama Kelas</p>
                        <p class="text-lg font-semibold text-gray-900" id="detailNamaKelas">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Mata Kuliah</p>
                        <p class="text-lg font-semibold text-gray-900" id="detailMataKuliah">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Dosen Pengampu</p>
                        <p class="text-lg font-semibold text-gray-900" id="detailDosen">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Semester</p>
                        <p class="text-lg font-semibold text-gray-900" id="detailSemester">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <p class="text-lg font-semibold" id="detailStatus">-</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-blue-600 mb-1">Kapasitas</p>
                        <p class="text-lg font-semibold text-blue-900" id="detailKapasitas">-</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-green-600 mb-1">Kuota Terisi</p>
                        <p class="text-lg font-semibold text-green-900" id="detailKuotaTerisi">-</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-sm text-purple-600 mb-1">Sisa Kuota</p>
                        <p class="text-lg font-semibold text-purple-900" id="detailSisaKuota">-</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs untuk Jadwal, Mahasiswa, Pertemuan --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex px-6 -mb-px space-x-8">
                    <button onclick="showDetailTab('jadwal')" id="tabJadwal"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Jadwal Kuliah
                        </div>
                    </button>
                    <button onclick="showDetailTab('mahasiswa')" id="tabMahasiswa"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Daftar Mahasiswa
                        </div>
                    </button>
                    <button onclick="showDetailTab('pertemuan')" id="tabPertemuan"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Pertemuan
                        </div>
                    </button>
                </nav>
            </div>

            {{-- Tab Content: Jadwal --}}
            <div id="contentJadwal" class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Jadwal Kuliah</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruangan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="tableJadwal" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab Content: Mahasiswa --}}
            <div id="contentMahasiswa" class="p-6 hidden">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Mahasiswa</h3>
                    <div class="text-sm text-gray-600">
                        Total: <span id="totalMahasiswa" class="font-semibold">0</span> mahasiswa
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mahasiswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jurusan</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tableMahasiswa" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tab Content: Pertemuan --}}
            <div id="contentPertemuan" class="p-6 hidden">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Pertemuan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Pertemuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tablePertemuan" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit (reuse dari index) --}}
    @include('components.modal.kelas.modal-edit')

    {{-- Scripts --}}
    <script src="{{ asset('js/admin/kelas/kelas-detail.js') }}"></script>
    <script src="{{ asset('js/admin/kelas/kelas-edit.js') }}"></script>

    <script>
        // Get kelas ID dari URL
        const kelasId = window.location.pathname.split('/').pop();

        function showDetailTab(tab) {
            // Reset semua tabs
            ['jadwal', 'mahasiswa', 'pertemuan'].forEach(t => {
                document.getElementById(`tab${t.charAt(0).toUpperCase() + t.slice(1)}`).classList.remove('border-blue-500', 'text-blue-600');
                document.getElementById(`tab${t.charAt(0).toUpperCase() + t.slice(1)}`).classList.add('border-transparent', 'text-gray-500');
                document.getElementById(`content${t.charAt(0).toUpperCase() + t.slice(1)}`).classList.add('hidden');
            });

            // Aktifkan tab yang dipilih
            const tabBtn = document.getElementById(`tab${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
            const content = document.getElementById(`content${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
            
            tabBtn.classList.add('border-blue-500', 'text-blue-600');
            tabBtn.classList.remove('border-transparent', 'text-gray-500');
            content.classList.remove('hidden');
        }

        function printDetail() {
            window.print();
        }

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            loadKelasDetail(kelasId);
        });
    </script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</x-layouts.dashboard>