<x-layouts.dashboard title="Detail Dosen">
    <div class="max-w-7xl mx-auto">
        {{-- Header with Breadcrumb --}}
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                <a href="/dosen" class="hover:text-green-600 transition">Dosen</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Detail</span>
            </nav>
        </div>

        {{-- Loading State --}}
        <div id="loading" class="bg-white rounded-xl shadow-sm p-12">
            <div class="flex flex-col items-center justify-center">
                <div class="relative">
                    <div class="w-16 h-16 border-4 border-green-100 rounded-full"></div>
                    <div class="w-16 h-16 border-4 border-green-600 border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div>
                </div>
                <p class="mt-4 text-gray-600 font-medium">Memuat data dosen...</p>
            </div>
        </div>

        {{-- Content --}}
        <div id="content" class="hidden space-y-6">
            {{-- Profile Header Card --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                {{-- Gradient Header --}}
                <div class="h-32 bg-gradient-to-r from-green-500 via-green-600 to-emerald-600"></div>
                
                {{-- Profile Content --}}
                <div class="px-8 pb-8">
                    <div class="flex flex-col md:flex-row gap-6 -mt-16">
                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            <div class="w-32 h-32 bg-white rounded-2xl shadow-lg flex items-center justify-center border-4 border-white">
                                <span id="initial" class="text-5xl font-bold text-green-600"></span>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 pt-4">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                <div>
                                    <h1 class="text-3xl font-bold text-gray-900 mb-2" id="nama"></h1>
                                    <div class="flex flex-wrap gap-4 text-sm">
                                        <div class="flex items-center gap-2 text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                            </svg>
                                            <span>NIDN: <strong id="nidn"></strong></span>
                                        </div>
                                        <div id="statusHeaderBadge"></div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex gap-2">
                                    <button onclick="openEditModal(currentDosenId)" 
                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button onclick="confirmDelete(currentDosenId)" 
                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Arsipkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs Navigation --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="border-b border-gray-200">
                    <nav class="flex gap-1 px-6" aria-label="Tabs">
                        <button onclick="showTab('biodata')" id="tabBiodata" 
                                class="flex items-center gap-2 px-4 py-4 border-b-2 border-green-600 text-green-600 font-semibold text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Data Pribadi
                        </button>
                        <button onclick="showTab('kepegawaian')" id="tabKepegawaian" 
                                class="flex items-center gap-2 px-4 py-4 border-b-2 border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300 font-medium text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Kepegawaian
                        </button>
                        <button onclick="showTab('kontak')" id="tabKontak" 
                                class="flex items-center gap-2 px-4 py-4 border-b-2 border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300 font-medium text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Kontak
                        </button>
                    </nav>
                </div>

                {{-- Tab Content: Biodata --}}
                <div id="contentBiodata" class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</label>
                            <p class="text-base font-semibold text-gray-900" id="namaLengkap"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Gelar</label>
                            <p class="text-base font-semibold text-gray-900" id="gelar"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</label>
                            <p class="text-base font-semibold text-gray-900" id="jenisKelamin"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tempat Lahir</label>
                            <p class="text-base font-semibold text-gray-900" id="tempatLahir"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Lahir</label>
                            <p class="text-base font-semibold text-gray-900" id="tanggalLahir"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100 md:col-span-2">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</label>
                            <p class="text-base font-semibold text-gray-900" id="alamat"></p>
                        </div>
                    </div>
                </div>

                {{-- Tab Content: Kepegawaian --}}
                <div id="contentKepegawaian" class="p-8 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">NIDN</label>
                            <p class="text-base font-semibold text-gray-900" id="nidnKepegawaian"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</label>
                            <p class="text-base font-semibold text-gray-900" id="nip"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</label>
                            <p class="text-base font-semibold text-gray-900" id="jurusan"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status Kepegawaian</label>
                            <p class="text-base font-semibold text-gray-900" id="statusKepegawaian"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</label>
                            <p class="text-base font-semibold text-gray-900" id="jabatan"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</label>
                            <div id="status"></div>
                        </div>
                    </div>
                </div>

                {{-- Tab Content: Kontak --}}
                <div id="contentKontak" class="p-8 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">No. HP</label>
                            <p class="text-base font-semibold text-gray-900" id="noHp"></p>
                        </div>
                        <div class="space-y-1 p-4 rounded-lg bg-gray-50 border border-gray-100">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email Pribadi</label>
                            <p class="text-base font-semibold text-gray-900" id="emailPribadi"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Metadata Card --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4">Informasi Sistem</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Dibuat pada</label>
                            <p class="text-sm font-semibold text-gray-900 mt-1" id="createdAt"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">Terakhir diupdate</label>
                            <p class="text-sm font-semibold text-gray-900 mt-1" id="updatedAt"></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500">User ID</label>
                            <p class="text-sm font-semibold text-gray-900 mt-1" id="userId"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    @include('components.modal.dosen.modal-edit')

    {{-- Script --}}
    <script src="{{ asset('js/admin/dosen/dosen-detail.js') }}"></script>
    <script src="{{ asset('js/admin/dosen/dosen-edit.js') }}"></script>
    
    <script>
        // Enhanced renderDosenDetail untuk UI baru
        const originalRender = window.renderDosenDetail;
        window.renderDosenDetail = function(data) {
            // Format nama dengan gelar
            let namaLengkap = data.nama_lengkap;
            let namaWithGelar = namaLengkap;
            if (data.gelar_depan || data.gelar_belakang) {
                namaWithGelar = `${data.gelar_depan || ''} ${data.nama_lengkap} ${data.gelar_belakang || ''}`.trim();
            }

            // Header Section
            const initial = data.nama_lengkap.charAt(0).toUpperCase();
            document.getElementById('initial').textContent = initial;
            document.getElementById('nama').textContent = namaWithGelar;
            document.getElementById('nidn').textContent = data.nidn;
            
            // Status badge di header
            const statusBadge = getStatusBadge(data.status);
            document.getElementById('statusHeaderBadge').innerHTML = statusBadge;

            // Tab Biodata
            document.getElementById('namaLengkap').textContent = namaLengkap;
            document.getElementById('gelar').textContent = `${data.gelar_depan || '-'} / ${data.gelar_belakang || '-'}`;
            document.getElementById('jenisKelamin').textContent = data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
            document.getElementById('tanggalLahir').textContent = formatDate(data.tanggal_lahir);
            document.getElementById('tempatLahir').textContent = data.tempat_lahir || '-';
            document.getElementById('alamat').textContent = data.alamat || '-';

            // Tab Kepegawaian
            document.getElementById('nidnKepegawaian').textContent = data.nidn;
            document.getElementById('nip').textContent = data.nip || '-';
            document.getElementById('jurusan').textContent = data.jurusan?.nama_jurusan || '-';
            document.getElementById('statusKepegawaian').textContent = data.status_kepegawaian || '-';
            document.getElementById('jabatan').textContent = data.jabatan || '-';
            document.getElementById('status').innerHTML = statusBadge;

            // Tab Kontak
            document.getElementById('noHp').textContent = data.no_hp || '-';
            document.getElementById('emailPribadi').textContent = data.email_pribadi || '-';

            // Metadata
            document.getElementById('userId').textContent = data.user_id;
            document.getElementById('createdAt').textContent = formatDateTime(data.created_at);
            document.getElementById('updatedAt').textContent = formatDateTime(data.updated_at);
        };

        function getStatusBadge(status) {
            const badges = {
                'AKTIF': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Aktif</span>',
                'CUTI': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Cuti</span>',
                'PENSIUN': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Pensiun</span>',
                'NONAKTIF': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Nonaktif</span>'
            };
            return badges[status?.toUpperCase()] || `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">${status || '-'}</span>`;
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        function formatDateTime(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return date.toLocaleDateString('id-ID', options);
        }

        // Enhanced Tab Navigation
        window.showTab = function(tabName) {
            const tabs = {
                'biodata': {
                    btn: document.getElementById('tabBiodata'),
                    content: document.getElementById('contentBiodata')
                },
                'kepegawaian': {
                    btn: document.getElementById('tabKepegawaian'),
                    content: document.getElementById('contentKepegawaian')
                },
                'kontak': {
                    btn: document.getElementById('tabKontak'),
                    content: document.getElementById('contentKontak')
                }
            };

            Object.keys(tabs).forEach(key => {
                const { btn, content } = tabs[key];
                if (key === tabName) {
                    btn.className = 'flex items-center gap-2 px-4 py-4 border-b-2 border-green-600 text-green-600 font-semibold text-sm transition';
                    content.classList.remove('hidden');
                } else {
                    btn.className = 'flex items-center gap-2 px-4 py-4 border-b-2 border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300 font-medium text-sm transition';
                    content.classList.add('hidden');
                }
            });
        };
    </script>
</x-layouts.dashboard>