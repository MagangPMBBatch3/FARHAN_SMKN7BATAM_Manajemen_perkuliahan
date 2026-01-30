<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Portal Mahasiswa</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    
    <style>
        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .sidebar-link {
            position: relative;
            overflow: hidden;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: white;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            transform: scaleY(1);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .gradient-text {
            background: linear-gradient(135deg, #10b981 0%, #0ea5e9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-inter">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-72 bg-gradient-to-br from-emerald-600 via-emerald-700 to-sky-600 text-white flex flex-col shadow-2xl" style="animation: slideIn 0.5s ease-out;">
            
            {{-- Logo Section --}}
            <div class="p-6 border-b border-white/20">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Portal Mahasiswa</h2>
                        <p class="text-xs text-white/70">Sistem Akademik</p>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="/mahasiswa/dashboard" class="sidebar-link flex items-center px-4 py-3 rounded-xl hover:bg-white/20 transition-all duration-300 group">
                    <div class="bg-white/20 p-2 rounded-lg mr-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-home text-sm"></i>
                    </div>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="/mahasiswa/jadwal" class="sidebar-link flex items-center px-4 py-3 rounded-xl hover:bg-white/20 transition-all duration-300 group">
                    <div class="bg-white/0 group-hover:bg-white/20 p-2 rounded-lg mr-3 group-hover:scale-110 transition-all">
                        <i class="fas fa-calendar-alt text-sm"></i>
                    </div>
                    <span class="font-medium">Jadwal Kuliah</span>
                </a>
                
                <a href="/mahasiswa/krs " class="sidebar-link flex items-center px-4 py-3 rounded-xl hover:bg-white/20 transition-all duration-300 group">
                    <div class="bg-white/0 group-hover:bg-white/20 p-2 rounded-lg mr-3 group-hover:scale-110 transition-all">
                        <i class="fas fa-file-alt text-sm"></i>
                    </div>
                    <span class="font-medium">KRS</span>
                </a>
                
                <a href="/mahasiswa/khs" class="sidebar-link flex items-center px-4 py-3 rounded-xl hover:bg-white/20 transition-all duration-300 group">
                    <div class="bg-white/0 group-hover:bg-white/20 p-2 rounded-lg mr-3 group-hover:scale-110 transition-all">
                        <i class="fas fa-chart-line text-sm"></i>
                    </div>
                    <span class="font-medium">KHS</span>
                </a>

                <a href="/mahasiswa/nilai" class="sidebar-link flex items-center px-4 py-3 rounded-xl hover:bg-white/20 transition-all duration-300 group">
                    <div class="bg-white/0 group-hover:bg-white/20 p-2 rounded-lg mr-3 group-hover:scale-110 transition-all">
                        <i class="fas fa-star text-sm"></i>
                    </div>
                    <span class="font-medium">Nilai</span>
                </a>

                <div class="pt-4 mt-4 border-t border-white/20">
                    <p class="text-xs text-white/50 px-4 mb-2 uppercase tracking-wider">Lainnya</p>
                    
                  
                    <a href="" class="sidebar-link flex items-center px-4 py-3 rounded-xl hover:bg-white/20 transition-all duration-300 group">
                        <div class="bg-white/0 group-hover:bg-white/20 p-2 rounded-lg mr-3 group-hover:scale-110 transition-all">
                            <i class="fas fa-user-circle text-sm"></i>
                        </div>
                        <span class="font-medium">Profil Saya</span>
                    </a>
                </div>
            </nav>

            {{-- User Profile & Logout --}}
            <div class="p-4 border-t border-white/20">
                <div class="bg-white/10 rounded-xl p-4 mb-3 backdrop-blur-sm">
                    <div class="flex items-center space-x-3">
                        <div class="bg-gradient-to-br from-emerald-400 to-sky-400 p-2 rounded-lg">
                            <i class="fas fa-user text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm truncate">{{ Auth::user()->name ?? 'Mahasiswa' }}</p>
                            <p class="text-xs text-white/70 truncate">{{ Auth::user()->mahasiswa->nim ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                
                <form method="POST"  action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-3 rounded-xl bg-red-500/90 hover:bg-red-600 transition-all duration-300 font-medium group">
                        <i class="fas fa-sign-out-alt mr-2 group-hover:translate-x-1 transition-transform"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Page Content --}}
            <main class="flex-1 p-8 overflow-y-auto">
                <div style="animation: fadeIn 0.7s ease-out;">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>

</body>
</html>