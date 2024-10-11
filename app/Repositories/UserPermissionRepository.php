<?php

namespace App\Repositories;

use App\Models\UserPermission;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class UserPermissionRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return UserPermission::class;
    }

    public function deleteByPermissionId($permissionId)
    {
        return $this->_model->where('permission_id', $permissionId)->delete();
    }

    public function deleteUserPermission($userId, $permissionId)
    {
        return $this->_model->whereIn('user_id', Arr::wrap($userId))->whereIn('permission_id', Arr::wrap($permissionId))->delete();
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);
        if (!empty($filters['permission_id'])) {
            $query->where('permission_id', $filters['permission_id']);
        }

        return $query->get();
    }
}
