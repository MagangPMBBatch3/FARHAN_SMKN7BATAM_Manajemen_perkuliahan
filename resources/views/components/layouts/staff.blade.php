@props([
    'title' => 'Dashboard',
    'roleTitle' => 'Staff',
    'sections' => [],
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Manajemen Perkuliahan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        .sidebar-wrapper {
            position: relative;
            background: linear-gradient(145deg, #0f766e 0%, #0284c7 100%);
        }

        .sidebar-wrapper::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.45;
        }

        .menu-item {
            transition: all 0.2s ease;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.18);
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateX(4px);
        }

        .icon-box {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-slate-100">
    <div class="flex min-h-screen">
        <aside class="w-72 sidebar-wrapper text-white flex flex-col shadow-2xl">
            <div class="relative z-10 flex-1 flex flex-col">
                <div class="px-6 py-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold leading-tight">SIAKAD</h1>
                            <p class="text-xs text-white/70">{{ $roleTitle }}</p>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 px-4 space-y-5">
                    @foreach ($sections as $section)
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-white/60 mb-2">
                                {{ $section['title'] ?? 'Menu' }}
                            </p>
                            <div class="space-y-1">
                                @foreach ($section['items'] ?? [] as $item)
                                    @php
                                        $isActive = isset($item['route']) && request()->routeIs($item['route']);
                                    @endphp
                                    <a href="{{ isset($item['route']) ? route($item['route']) : '#' }}"
                                       class="menu-item {{ $isActive ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold">
                                        <span class="icon-box">
                                            <i class="{{ $item['icon'] ?? 'fas fa-circle' }} text-xs"></i>
                                        </span>
                                        <span>{{ $item['label'] ?? 'Menu' }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>

                <div class="px-4 pb-6">
                    <div class="bg-white/15 rounded-xl p-4 mb-3">
                        <p class="text-xs text-white/70">Masuk sebagai</p>
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->name ?? Auth::user()->username ?? 'Staff' }}</p>
                        <p class="text-[11px] text-white/70 truncate">{{ Auth::user()->email ?? '-' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn w-full flex items-center justify-center px-3 py-2.5 rounded-xl font-bold text-xs">
                            <i class="fas fa-power-off mr-2 text-[10px]"></i>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
