<x-layouts.dashboard title="Dashboard">
    
    {{-- Header Section --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Akademik</h1>
        <p class="text-gray-600">Sistem Informasi Akademik - {{ now()->format('d F Y') }}</p>
    </div>

    {{-- Main Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- Total Mahasiswa --}}
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition-all p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Mahasiswa</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalMahasiswa ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-graduate text-white text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Dosen --}}
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition-all p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Dosen</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalDosen ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Mata Kuliah --}}
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition-all p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Mata Kuliah</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalMataKuliah ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book text-white text-2xl"></i>
                </div>
            </div>
        </div>

        {{-- KRS Pending --}}
        <div class="bg-white rounded-xl shadow hover:shadow-lg transition-all p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">KRS Pending</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $krsPending ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- KRS Statistics --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        {{-- KRS Status Overview --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4">
                <i class="fas fa-chart-pie text-emerald-600 mr-2"></i>
                Status KRS
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                        <span class="font-semibold text-gray-700">Disetujui</span>
                    </div>
                    <span class="text-xl font-bold text-emerald-600">{{ $krsDisetujui ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-clock text-amber-600 text-xl"></i>
                        <span class="font-semibold text-gray-700">Pending</span>
                    </div>
                    <span class="text-xl font-bold text-amber-600">{{ $krsPending ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        <span class="font-semibold text-gray-700">Ditolak</span>
                    </div>
                    <span class="text-xl font-bold text-red-600">{{ $krsDitolak ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Statistik Mahasiswa --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4">
                <i class="fas fa-users text-sky-600 mr-2"></i>
                Statistik Mahasiswa
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Rata-rata IPK</span>
                    <span class="text-xl font-bold text-gray-800">{{ number_format($avgIpk ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total SKS Terambil</span>
                    <span class="text-xl font-bold text-gray-800">{{ number_format($totalSksAmbil ?? 0) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Mahasiswa L/P</span>
                    <span class="text-xl font-bold text-gray-800">{{ $mahasiswaL ?? 0 }}/{{ $mahasiswaP ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Statistik Dosen --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="font-bold text-lg text-gray-800 mb-4">
                <i class="fas fa-user-tie text-cyan-600 mr-2"></i>
                Statistik Dosen
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Dosen Tetap</span>
                    <span class="text-xl font-bold text-gray-800">{{ $dosenTetap ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Dosen Kontrak</span>
                    <span class="text-xl font-bold text-gray-800">{{ $dosenKontrak ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Pembimbing PA</span>
                    <span class="text-xl font-bold text-gray-800">{{ $totalDosenPA ?? 0 }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- {{-- Data Tables Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        {{-- KRS Terbaru --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-gray-800">
                    <i class="fas fa-file-alt text-emerald-600 mr-2"></i>
                    KRS Terbaru
                </h3>
                <a href="/admin/krs" class="text-sm text-emerald-600 hover:text-emerald-700 font-semibold">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-2">
                @forelse($krsTerbaru ?? [] as $krs)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-all border border-gray-100">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $krs->mahasiswa->nama_lengkap }}</p>
                        <p class="text-xs text-gray-500">{{ $krs->mahasiswa->nim }} • {{ $krs->total_sks }} SKS</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full font-semibold
                        @if($krs->status == 'disetujui') bg-emerald-100 text-emerald-700
                        @elseif($krs->status == 'pending') bg-amber-100 text-amber-700
                        @else bg-red-100 text-red-700
                        @endif">
                        {{ ucfirst($krs->status) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p class="text-sm">Belum ada data KRS</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Mata Kuliah Populer (Berdasarkan KRS Detail) --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-gray-800">
                    <i class="fas fa-fire text-red-500 mr-2"></i>
                    Mata Kuliah Populer
                </h3>
                <a href="{{ route('admin.mata_kuliah') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-semibold">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-2">
                @forelse($mataKuliahPopuler ?? [] as $mk)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition-all border border-gray-100">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $mk->nama_mk }}</p>
                        <p class="text-xs text-gray-500">{{ $mk->kode_mk }} • {{ $mk->sks }} SKS • {{ $mk->jenis }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-emerald-600">{{ $mk->jumlah_pengambil ?? 0 }}</p>
                        <p class="text-xs text-gray-500">mahasiswa</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p class="text-sm">Belum ada data</p>
                </div>
                @endforelse
            </div>
        </div>

    </div> -->
    <!-- //!nanti disesuaikan lagi -->

    <!-- {{-- Recent Activities Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Mahasiswa Terbaru --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-gray-800">
                    <i class="fas fa-user-graduate text-sky-600 mr-2"></i>
                    Mahasiswa Terdaftar Terbaru
                </h3>
                <a href="/admin/mahasiswa" class="text-sm text-emerald-600 hover:text-emerald-700 font-semibold">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-2">
                @forelse($mahasiswaTerbaru ?? [] as $mhs)
                <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-all border border-gray-100">
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-100 to-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-sky-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $mhs->nama_lengkap }}</p>
                        <p class="text-xs text-gray-600">{{ $mhs->nim }} • Angkatan {{ $mhs->angkatan }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Semester {{ $mhs->semester_saat_ini }} • IPK: {{ number_format($mhs->ipk, 2) }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p class="text-sm">Belum ada data mahasiswa</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Dosen Terbaru --}}
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-gray-800">
                    <i class="fas fa-chalkboard-teacher text-teal-600 mr-2"></i>
                    Dosen Terdaftar Terbaru
                </h3>
                <a href="/admin/dosen" class="text-sm text-emerald-600 hover:text-emerald-700 font-semibold">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-2">
                @forelse($dosenTerbaru ?? [] as $dosen)
                <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-all border border-gray-100">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-tie text-teal-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">
                            @if($dosen->gelar_depan){{ $dosen->gelar_depan }}. @endif
                            {{ $dosen->nama_lengkap }}
                            @if($dosen->gelar_belakang), {{ $dosen->gelar_belakang }}@endif
                        </p>
                        <p class="text-xs text-gray-600">NIDN: {{ $dosen->nidn }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $dosen->jabatan ?? 'Dosen' }} • {{ ucfirst($dosen->status_kepegawaian) }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p class="text-sm">Belum ada data dosen</p>
                </div>
                @endforelse
            </div>
        </div>

    </div> -->

    <!-- //!nanti disesuaikan lagi -->

</x-layouts.dashboard>