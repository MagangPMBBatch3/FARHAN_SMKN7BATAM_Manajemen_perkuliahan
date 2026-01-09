<?php
namespace App\GraphQL\Pertemuan\Mutations;

use App\Models\Pertemuan\Pertemuan;

class PertemuanMutation
{
    public function restore($_, array $args): ?Pertemuan
    {
        return Pertemuan::withTrashed()->find($args['id'])?->restore()
            ? Pertemuan::find($args['id'])
            : null;
    }
    
    public function forceDelete($_, array $args): ?Pertemuan
    {
        $pertemuan = Pertemuan::withTrashed()->find($args['id']);
        if ($pertemuan) {
            $pertemuan->forceDelete();
            return $pertemuan;
        }
        return null;
    }
    
    public function updateStatus($_, array $args): ?Pertemuan
    {
        $pertemuan = Pertemuan::find($args['id']);
        if ($pertemuan) {
            $pertemuan->status_pertemuan = $args['status'];
            $pertemuan->save();
            return $pertemuan;
        }
        return null;
    }
}