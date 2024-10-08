<?php

namespace App\Repositories;

use App\Models\RolePermission;
use App\Repositories\Base\BaseRepository;

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
}
