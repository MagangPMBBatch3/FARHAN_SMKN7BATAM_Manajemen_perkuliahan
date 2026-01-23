<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Manajemen Perkuliahan</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Sidebar Modern Design */
        .sidebar-wrapper {
            position: relative;
            background: linear-gradient(145deg, #059669 0%, #0891b2 100%);
        }

        .sidebar-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.4;
        }

        .sidebar-content {
            position: relative;
            z-index: 1;
        }

        /* Menu Item Styles */
        .menu-item {
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 70%;
            background: linear-gradient(90deg, rgba(255,255,255,0.9), transparent);
            border-radius: 0 4px 4px 0;
            transition: width 0.3s ease;
        }

        .menu-item:hover::before,
        .menu-item.active::before {
            width: 4px;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.18);
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.1);
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateX(4px);
        }

        /* Icon Container */
        .icon-box {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            transition: all 0.3s ease;
            backdrop-filter: blur(8px);
        }

        .menu-item:hover .icon-box,
        .menu-item.active .icon-box {
            background: rgba(255, 255, 255, 0.25);
            transform: rotate(5deg) scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Dropdown Styles */
        .dropdown-trigger {
            cursor: pointer;
        }

        .dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-content.open {
            max-height: 800px;
        }

        .dropdown-arrow {
            transition: transform 0.3s ease;
            font-size: 10px;
        }

        .dropdown-arrow.rotate {
            transform: rotate(180deg);
        }

        /* Submenu Styles */
        .submenu-item {
            position: relative;
            padding-left: 3.5rem;
            transition: all 0.2s ease;
        }

        .submenu-item::before {
            content: '';
            position: absolute;
            left: 2.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .submenu-item:hover::before,
        .submenu-item.active::before {
            width: 6px;
            height: 6px;
            background: white;
            box-shadow: 0 0 8px rgba(255,255,255,0.8);
        }

        .submenu-item::after {
            content: '';
            position: absolute;
            left: 2rem;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .submenu-item.active {
            background: rgba(255, 255, 255, 0.1);
        }

        /* User Profile Card */
        .user-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .user-card:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #10b981, #06b6d4);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        /* Scrollbar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin: 8px 0;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Logout Button */
        .logout-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
            transform: translateY(-2px);
        }

        .logout-btn:active {
            transform: translateY(0);
        }

        /* Logo Animation */
        .logo-icon {
            animation: pulse 2s ease-in-out infinite;
        }

        /* Divider */
        .menu-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            margin: 8px 0;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 font-inter">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-60 sidebar-wrapper text-white flex flex-col shadow-2xl" style="animation: slideIn 0.5s ease-out;">
            <div class="sidebar-content flex flex-col h-full">
                
                {{-- Logo Section --}}
                <div class="px-4 py-4 border-b border-white/20">
                    <div class="flex items-center space-x-2.5">
                        <div class="icon-box logo-icon">
                            <i class="fas fa-graduation-cap text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-sm font-bold">SIAKAD</h1>
                            <p class="text-[9px] text-white/80 font-medium">Manajemen Akademik</p>
                        </div>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-2.5 py-3 space-y-0.5 overflow-y-auto sidebar-scroll">
                    
                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center px-3 py-2.5 rounded-lg group">
                        <div class="icon-box mr-2.5">
                            <i class="fas fa-chart-pie text-xs"></i>
                        </div>
                        <span class="font-semibold text-xs tracking-wide">Dashboard</span>
                    </a>

                    <div class="menu-divider"></div>

                    {{-- Master Data Dropdown --}}
                    <div>
                        <button onclick="toggleDropdown('masterData')" class="menu-item dropdown-trigger w-full flex items-center justify-between px-3 py-2.5 rounded-lg group">
                            <div class="flex items-center">
                                <div class="icon-box mr-2.5">
                                    <i class="fas fa-database text-xs"></i>
                                </div>
                                <span class="font-semibold text-xs tracking-wide">Master Data</span>
                            </div>
                            <i class="fas fa-chevron-down dropdown-arrow" id="arrow-masterData"></i>
                        </button>
                        <div class="dropdown-content mt-1" id="dropdown-masterData">
                            <a href="{{ route('admin.user') }}" class="submenu-item {{ request()->routeIs('admin.user') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Users Management</span>
                            </a>
                            <a href="{{ route('admin.role') }}" class="submenu-item {{ request()->routeIs('admin.role') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Roles & Permission</span>
                            </a>
                            <a href="/admin/dosen" class="submenu-item {{ request()->is('admin/dosen*') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Data Dosen</span>
                            </a>
                            <a href="{{ route('admin.mahasiswa') }}" class="submenu-item {{ request()->routeIs('admin.mahasiswa') || request()->is('admin/mahasiswa_detail/*') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Data Mahasiswa</span>
                            </a>
                            <a href="{{ route('admin.fakultas') }}" class="submenu-item {{ request()->routeIs('admin.fakultas') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Fakultas</span>
                            </a>
                            <a href="{{ route('admin.jurusan') }}" class="submenu-item {{ request()->routeIs('admin.jurusan') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Program Studi</span>
                            </a>
                            <a href="{{ route('admin.mata_kuliah') }}" class="submenu-item {{ request()->routeIs('admin.mata_kuliah') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Mata Kuliah</span>
                            </a>
                            <a href="/admin/ruangan" class="submenu-item {{ request()->is('admin/ruangan') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Ruangan</span>
                            </a>
                            <a href="/admin/kelas" class="submenu-item {{ request()->is('admin/kelas') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Kelas</span>
                            </a>
                            <a href="/admin/semester" class="submenu-item {{ request()->is('admin/semester') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Semester</span>
                            </a>
                        </div>
                    </div>

                    {{-- Perkuliahan Dropdown --}}
                    <div>
                        <button onclick="toggleDropdown('perkuliahan')" class="menu-item dropdown-trigger w-full flex items-center justify-between px-3 py-2.5 rounded-lg group">
                            <div class="flex items-center">
                                <div class="icon-box mr-2.5">
                                    <i class="fas fa-book-reader text-xs"></i>
                                </div>
                                <span class="font-semibold text-xs tracking-wide">Perkuliahan</span>
                            </div>
                            <i class="fas fa-chevron-down dropdown-arrow" id="arrow-perkuliahan"></i>
                        </button>
                        <div class="dropdown-content mt-1" id="dropdown-perkuliahan">
                            <!-- <a href="{{ route('admin.jadwal') }}" class="submenu-item {{ request()->routeIs('admin.jadwal') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Jadwal Kuliah</span>
                            </a> -->
                            <a href="/admin/pertemuan" class="submenu-item {{ request()->is('admin/pertemuan') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Pertemuan</span>
                            </a>
                            <a href="/admin/krs" class="submenu-item {{ request()->is('admin/krs*') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Kartu Rencana Studi</span>
                            </a>
                            <a href="/admin/sks-limit" class="submenu-item {{ request()->is('admin/sks-limit') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Batas SKS</span>
                            </a>
                        </div>
                    </div>

                    {{-- Kehadiran Dropdown --}}
                    <div>
                        <button onclick="toggleDropdown('kehadiran')" class="menu-item dropdown-trigger w-full flex items-center justify-between px-3 py-2.5 rounded-lg group">
                            <div class="flex items-center">
                                <div class="icon-box mr-2.5">
                                    <i class="fas fa-clipboard-check text-xs"></i>
                                </div>
                                <span class="font-semibold text-xs tracking-wide">Kehadiran</span>
                            </div>
                            <i class="fas fa-chevron-down dropdown-arrow" id="arrow-kehadiran"></i>
                        </button>
                        <div class="dropdown-content mt-1" id="dropdown-kehadiran">
                            <a href="/admin/kehadiran" class="submenu-item {{ request()->is('admin/kehadiran') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Data Kehadiran</span>
                            </a>
                            <a href="/admin/rekap-kehadiran" class="submenu-item {{ request()->is('admin/rekap-kehadiran') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Rekap Kehadiran</span>
                            </a>
                            <!-- <a href="/admin/pengaturan-kehadiran" class="submenu-item {{ request()->is('admin/pengaturan-kehadiran') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Pengaturan Kehadiran</span>
                            </a> -->
                        </div>
                    </div>

                    {{-- Penilaian Dropdown --}}
                    <div>
                        <button onclick="toggleDropdown('penilaian')" class="menu-item dropdown-trigger w-full flex items-center justify-between px-3 py-2.5 rounded-lg group">
                            <div class="flex items-center">
                                <div class="icon-box mr-2.5">
                                    <i class="fas fa-star text-xs"></i>
                                </div>
                                <span class="font-semibold text-xs tracking-wide">Penilaian</span>
                            </div>
                            <i class="fas fa-chevron-down dropdown-arrow" id="arrow-penilaian"></i>
                        </button>
                        <div class="dropdown-content mt-1" id="dropdown-penilaian">
                            <a href="{{ route('admin.nilai') }}" class="submenu-item {{ request()->routeIs('admin.nilai') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Input Nilai</span>
                            </a>
                            <a href="/admin/bobot-nilai" class="submenu-item {{ request()->is('admin/bobot-nilai') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Bobot Nilai</span>
                            </a>
                            <a href="/admin/grade-system" class="submenu-item {{ request()->is('admin/grade-system') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Sistem Grade</span>
                            </a>
                            <a href="/admin/khs" class="submenu-item {{ request()->is('admin/khs') ? 'active' : '' }} flex items-center py-2 rounded-lg hover:bg-white/5 transition-all text-xs">
                                <span class="font-medium">Kartu Hasil Studi</span>
                            </a>
                        </div>
                    </div>

                </nav>

                {{-- User Profile & Logout --}}
                <div class="p-3 border-t border-white/20 space-y-2">
                    <div class="user-card rounded-xl p-3">
                        <div class="flex items-center space-x-2.5">
                            <div class="user-avatar">
                                <i class="fas fa-user-shield text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-xs truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                                <p class="text-[9px] text-white/80 truncate font-medium">{{ Auth::user()->email ?? 'admin@siakad.ac.id' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn w-full flex items-center justify-center px-3 py-2.5 rounded-xl font-bold text-xs group">
                            <i class="fas fa-power-off mr-2 text-[10px] group-hover:rotate-180 transition-transform duration-500"></i>
                            Sign Out
                        </button>
                    </form>
                </div>

            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">

            {{-- Page Content --}}
            <main class="flex-1 p-6 overflow-y-auto">
                <div style="animation: fadeIn 0.7s ease-out;">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>

    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById('dropdown-' + id);
            const arrow = document.getElementById('arrow-' + id);
            
            dropdown.classList.toggle('open');
            arrow.classList.toggle('rotate');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const activeSubmenus = document.querySelectorAll('.submenu-item.active');
            activeSubmenus.forEach(submenu => {
                const dropdown = submenu.closest('.dropdown-content');
                if (dropdown) {
                    const dropdownId = dropdown.id.replace('dropdown-', '');
                    const arrow = document.getElementById('arrow-' + dropdownId);
                    
                    dropdown.classList.add('open');
                    if (arrow) arrow.classList.add('rotate');
                }
            });
        });
    </script>

</body>
</html>