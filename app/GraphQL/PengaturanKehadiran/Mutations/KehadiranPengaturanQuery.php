<?php
namespace App\GraphQL\PengaturanKehadiran\Mutations;

use App\Models\PengaturanKehadiran\PengaturanKehadiran;

class PengaturanKehadiranMutation
{
    public function restore($_, array $args): ?PengaturanKehadiran
    {
        return PengaturanKehadiran::withTrashed()->find($args['id'])?->restore()
            ? PengaturanKehadiran::find($args['id'])
            : null;
    }
    
    public function forceDelete($_, array $args): ?PengaturanKehadiran
    {
        $pengaturan = PengaturanKehadiran::withTrashed()->find($args['id']);
        if ($pengaturan) {
            $pengaturan->forceDelete();
            return $pengaturan;
        }
        return null;
    }
    
    public function getOrCreate($_, array $args): PengaturanKehadiran
    {
        return PengaturanKehadiran::getOrCreateForKelas($args['kelas_id']);
    }
}