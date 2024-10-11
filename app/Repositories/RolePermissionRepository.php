<?php

namespace App\Repositories;

use App\Models\RolePermission;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class RolePermissionRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return RolePermission::class;
    }

    public function deleteByRoleId($roleId)
    {
        return $this->_model->where('role_id', $roleId)->delete();
    }

    public function deleteByPermissionId($permissionId)
    {
        return $this->_model->where('permission_id', $permissionId)->delete();
    }

    public function deleteRolePermissions($permissionIds, $roleIds)
    {
        return $this->_model->whereIn('permission_id', Arr::wrap($permissionIds))->whereIn('role_id', Arr::wrap($roleIds))->delete();
    }

    public function getListing(array $filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['role_id'])) {
            $query->whereIn('role_id', Arr::wrap($filters['role_id']));
        }

        if (!empty($filters['permission_id'])) {
            $query->whereIn('permission_id', Arr::wrap($filters['permission_id']));
        }

        return $query->get();
    }
}
