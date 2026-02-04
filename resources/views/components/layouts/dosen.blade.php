@props(['title' => 'Dashboard Dosen'])

@php
    $sections = [
        [
            'title' => 'Utama',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'dosen.dashboard', 'icon' => 'fas fa-chart-line'],
                ['label' => 'Jadwal Mengajar', 'route' => 'dosen.jadwal', 'icon' => 'fas fa-calendar-alt'],
            ],
        ],
        [
            'title' => 'Perkuliahan',
            'items' => [
                ['label' => 'Pertemuan', 'route' => 'dosen.pertemuan', 'icon' => 'fas fa-chalkboard'],
                ['label' => 'Input Nilai', 'route' => 'dosen.nilai', 'icon' => 'fas fa-clipboard-check'],
            ],
        ],
    ];
@endphp

<x-layouts.staff :title="$title" role-title="Dosen" :sections="$sections">
    {{ $slot }}
</x-layouts.staff>
