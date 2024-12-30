<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $connection = 'db_dev';

    protected $table = 'organizations';

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function deptType()
    {
        return $this->belongsTo(Config::class, 'dept_type_id');
    }

    public static function getLastParentId($parent_id, $arr, $rootOrgId)
    {
        $department = $arr->first(function ($item) use ($parent_id) {
            return $item['id'] === $parent_id;
        });
        if ($department && $department['parent_id'] == $rootOrgId) {
            return $department['id'];
        }
        return self::getLastParentId($department['parent_id'], $arr, $rootOrgId);
    }
}
