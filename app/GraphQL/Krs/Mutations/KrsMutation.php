<?php

namespace App\GraphQL\Krs\Mutations;

use App\Models\Krs\Krs;

class KrsMutation 
{
    public function forceDelete($rootValue, array $args)
    {
        $krs = Krs::withTrashed()->find($args['id']);
        
        if ($krs) {
            $krs->forceDelete();
            return $krs;
        }
        
        return null;
    }
    public function restore($rootValue, array $args)
    {
        $krs = Krs::onlyTrashed()->find($args['id']);
        
        if ($krs) {
            $krs->restore();
            return $krs;
        }
        
        return null;
    }
}   