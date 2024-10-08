<?php

namespace App\Repositories;

use App\Models\RoleUser;
use App\Repositories\Base\BaseRepository;

class RoleUserRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return RoleUser::class;
    }

    public function deleteByRoleId($roleId)
    {
        return $this->_model->where('role_id', $roleId)->delete();
    }
}
