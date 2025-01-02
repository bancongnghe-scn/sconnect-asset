<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Org extends Model
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

    //code cũ
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

    public static function getAllChildIds($parent_id, $arr)
    {
        $result = [];

        $fetchChildren = function ($parentId) use (&$result, $arr, &$fetchChildren) {
            foreach ($arr as $item) {
                if ($item['parent_id'] == $parentId) {
                    $result[] = $item['id'];
                    $fetchChildren($item['id']);
                }
            }
        };

        $fetchChildren($parent_id);

        return $result;
    }
}
