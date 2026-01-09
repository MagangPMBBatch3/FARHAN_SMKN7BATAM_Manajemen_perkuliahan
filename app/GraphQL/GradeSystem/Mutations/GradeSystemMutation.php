<?php
namespace App\GraphQL\GradeSystem\Mutations;

use App\Models\GradeSystem\GradeSystem;

class GradeSystemMutation
{
    public function restore($_, array $args): ?GradeSystem
    {
        return GradeSystem::withTrashed()->find($args['id'])?->restore()
            ? GradeSystem::find($args['id'])
            : null;
    }
    
    public function forceDelete($_, array $args): ?GradeSystem
    {
        $gradeSystem = GradeSystem::withTrashed()->find($args['id']);
        if ($gradeSystem) {
            $gradeSystem->forceDelete();
            return $gradeSystem;
        }
        return null;
    }
}