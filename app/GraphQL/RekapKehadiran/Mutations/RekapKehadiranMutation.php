<?php
namespace App\GraphQL\RekapKehadiran\Mutations;

use App\Models\RekapKehadiran\RekapKehadiran;

class RekapKehadiranMutation
{
    public function restore($_, array $args): ?RekapKehadiran
    {
        return RekapKehadiran::withTrashed()->find($args['id'])?->restore()
            ? RekapKehadiran::find($args['id'])
            : null;
    }
    
    public function forceDelete($_, array $args): ?RekapKehadiran
    {
        $rekapKehadiran = RekapKehadiran::withTrashed()->find($args['id']);
        if ($rekapKehadiran) {
            $rekapKehadiran->forceDelete();
            return $rekapKehadiran;
        }
        return null;
    }
}