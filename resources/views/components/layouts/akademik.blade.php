@props(['title' => 'Dashboard Akademik'])

@php
    $sections = [
        [
            'title' => 'Utama',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'akademik.dashboard', 'icon' => 'fas fa-chart-pie'],
                ['label' => 'Data Mahasiswa', 'route' => 'akademik.mahasiswa', 'icon' => 'fas fa-user-graduate'],
                ['label' => 'Data Dosen', 'route' => 'akademik.dosen', 'icon' => 'fas fa-user-tie'],
            ],
        ],
        [
            'title' => 'Akademik',
            'items' => [
                ['label' => 'Jadwal Kuliah', 'route' => 'akademik.jadwal', 'icon' => 'fas fa-calendar-check'],
                ['label' => 'KRS', 'route' => 'akademik.krs', 'icon' => 'fas fa-file-signature'],
                ['label' => 'KHS', 'route' => 'akademik.khs', 'icon' => 'fas fa-file-alt'],
            ],
        ],
    ];
@endphp

<x-layouts.staff :title="$title" role-title="Akademik" :sections="$sections">
    {{ $slot }}
</x-layouts.staff>
