<?php

namespace App\Repositories;

use App\Models\UserPermission;
use App\Repositories\Base\BaseRepository;

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
}
