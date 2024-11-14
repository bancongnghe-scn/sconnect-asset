<?php

namespace App\Repositories;

use App\Models\Rbac\MenuRole;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class MenuRoleRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return MenuRole::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);

        if (!empty($filters['role_id'])) {
            $query->whereIn('role_id', Arr::wrap($filters['role_id']));
        }

        if (!empty($filters['menu_id'])) {
            $query->whereIn('menu_id', Arr::wrap($filters['menu_id']));
        }

        return $query->get();
    }

    public function deleteByMenuId($menuId)
    {
        return $this->_model->whereIn('menu_id', Arr::wrap($menuId))->delete();
    }

    public function deleteMenuRole($roleIds, $menuIds)
    {
        return $this->_model->whereIn('menu_id', Arr::wrap($menuIds))->whereIn('role_id', Arr::wrap($roleIds))->delete();
    }
}
