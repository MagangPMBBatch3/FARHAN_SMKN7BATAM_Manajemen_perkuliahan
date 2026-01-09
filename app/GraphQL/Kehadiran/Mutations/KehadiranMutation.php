<?php
namespace App\GraphQL\Kehadiran\Mutations;

use App\Models\Kehadiran\Kehadiran;
use Illuminate\Support\Facades\DB;

class KehadiranMutation
{
    public function create($_, array $args)
    {
        $input = $args['input'];
        
        // Set waktu_input otomatis jika tidak disediakan
        if (!isset($input['waktu_input'])) {
            $input['waktu_input'] = now();
        }
        
        return Kehadiran::create($input);
    }
    
    public function restore($_, array $args): ?Kehadiran
    {
        return Kehadiran::withTrashed()->find($args['id'])?->restore()
            ? Kehadiran::find($args['id'])
            : null;
    }
    
    public function forceDelete($_, array $args): ?Kehadiran
    {
        $kehadiran = Kehadiran::withTrashed()->find($args['id']);
        if ($kehadiran) {
            $kehadiran->forceDelete();
            return $kehadiran;
        }
        return null;
    }
    
    public function bulkUpdate($_, array $args): array
    {
        $updated = [];
        
        DB::beginTransaction();
        try {
            foreach ($args['inputs'] as $input) {
                $kehadiran = Kehadiran::find($input['id']);
                if ($kehadiran) {
                    $kehadiran->status_kehadiran = $input['status_kehadiran'];
                    if (isset($input['keterangan'])) {
                        $kehadiran->keterangan = $input['keterangan'];
                    }
                    $kehadiran->save();
                    $updated[] = $kehadiran;
                }
            }
            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}