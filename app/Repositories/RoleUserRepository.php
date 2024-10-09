<?php

namespace App\Repositories;

use App\Models\RoleUser;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

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

    public function deleteRoleUsers($userIds, $roleId)
    {
        return $this->_model->whereIn('user_id', Arr::wrap($userIds))->where('role_id', $roleId)->delete();
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (isset($filters['role_id'])) {
            $query->whereIn('role_id', Arr::wrap($filters['role_id']));
        }

        return $query->get();
    }
}
