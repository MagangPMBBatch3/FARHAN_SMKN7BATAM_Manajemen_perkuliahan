<?php

namespace App\GraphQL\SksLimit\Mutations;

use App\Models\SksLimit\SksLimit;

class SksLimitMutation 
{
    public function restore($_, array $args): ?SksLimit
    {
        return SksLimit::withTrashed()->find($args['id'])?->restore()
        ? SksLimit::find($args['id'])
        : null;
    }

    public function forceDelete($_, array $args): ?SksLimit
    {
        $SksLimit = SksLimit::withTrashed()->find($args['id']);
        if ($SksLimit) {
            $SksLimit->forceDelete();
            return $SksLimit;
        }
        return null;
    }
}   