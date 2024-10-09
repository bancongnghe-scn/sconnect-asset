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

    public function deleteByUserIdsAndPermissionId($userIds, $permissionId)
    {
        return $this->_model->whereIn('user_id', Arr::wrap($userIds))->where('permission_id', $permissionId)->delete();
    }

    public function getListing(array $filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['permission_id'])) {
            $query->whereIn('permission_id', Arr::wrap($filters['permission_id']));
        }

        return $query->get();
    }
}
