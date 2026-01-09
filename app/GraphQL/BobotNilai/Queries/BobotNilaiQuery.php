<?php
namespace App\GraphQL\BobotNilai\Mutations;

use App\Models\BobotNilai\BobotNilai;

class BobotNilaiMutation
{
    public function restore($_, array $args): ?BobotNilai
    {
        return BobotNilai::withTrashed()->find($args['id'])?->restore()
            ? BobotNilai::find($args['id'])
            : null;
    }
    
    public function forceDelete($_, array $args): ?BobotNilai
    {
        $bobotNilai = BobotNilai::withTrashed()->find($args['id']);
        if ($bobotNilai) {
            $bobotNilai->forceDelete();
            return $bobotNilai;
        }
        return null;
    }
}
   