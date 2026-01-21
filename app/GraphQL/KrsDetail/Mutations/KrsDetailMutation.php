<?php

namespace App\GraphQL\KrsDetail\Mutations;

use App\Models\KrsDetail\KrsDetail;

class KrsDetailMutation 
{
    
    public function restore($_, array $args): ?KrsDetail
    {
        return KrsDetail::withTrashed()->find($args['id'])?->restore()
        ? KrsDetail::find($args['id'])
        : null;
    }

    public function forceDelete($_, array $args): ?KrsDetail
    {
        $KrsDetail = KrsDetail::withTrashed()->find($args['id']);
        if ($KrsDetail) {
            $KrsDetail->forceDelete();
            return $KrsDetail;
        }
        return null;
    }
}   