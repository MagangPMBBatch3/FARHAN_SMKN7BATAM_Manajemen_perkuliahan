<x-layouts.mahasiswa title="KHS Saya">
    <div class="space-y-6">
        {{-- Header Section --}}
        <p id="headerNIM" class="text-white font-bold text-lg">-</p>
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-8">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Kartu Hasil Studi (KHS)</h1>
                        <p class="text-blue-100">Riwayat prestasi akademik Anda</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-4">
                        <p class="text-blue-100 text-sm">IPK Terakhir</p>
                        <p id="latestIPK" class="text-3xl font-bold text-white">-</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Mahasiswa --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Mahasiswa</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">NIM</p>
                    <p id="infoNIM" class="font-semibold text-gray-900">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nama Lengkap</p>
                    <p id="infoNama" class="font-semibold text-gray-900">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Program Studi</p>
                    <p id="infoProdi" class="font-semibold text-gray-900">-</p>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex px-6 -mb-px space-x-8">
                    <button onclick="showTab('khs')" id="tabKHS"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-blue-500 text-blue-600">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Riwayat KHS
                        </div>
                    </button>
                    <button onclick="showTab('transkrip')" id="tabTranskrip"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Transkrip Nilai
                        </div>
                    </button>
                </nav>
            </div>

            {{-- Content KHS --}}
            <div id="contentKHS" class="p-6">
                <div id="khsList" class="space-y-4">
                    <div id="loadingKHS" class="flex justify-center items-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    </div>
                </div>
            </div>

            {{-- Content Transkrip --}}
            <div id="contentTranskrip" class="p-6 hidden">
                <div class="mb-6 flex justify-end">
                    <button onclick="printTranskrip()"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak Transkrip
                    </button>
                </div>
                <div id="transkripContent"></div>
            </div>
        </div>
    </div>

    {{-- Modal Detail KHS --}}
    @include('components.modal.mahasiswaModal.modal-detail-khs')

    {{-- Scripts --}}
    <script>
        // Inject Mahasiswa ID dari Laravel Auth
        const MAHASISWA_ID = {{ Auth::user()->mahasiswa_id ?? 'null' }};
    </script>
    <script src="{{ asset('js/mahasiswa/khs/khs.js') }}"></script>
    <script src="{{ asset('js/admin/khs/khs-detail.js') }}"></script>
</x-layouts.dashboard>