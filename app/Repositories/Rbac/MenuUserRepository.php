<?php

namespace App\Repositories\Rbac;

use App\Models\Rbac\MenuUser;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class MenuUserRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return MenuUser::class;
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['menu_id'])) {
            $query->where('menu_id', $filters['menu_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->get();
    }

    public function deleteMenuUser($userIds, $menuIds)
    {
        return $this->_model->whereIn('menu_id', Arr::wrap($menuIds))->whereIn('user_id', Arr::wrap($userIds))->delete();
    }

    public function deleteByMenuId($menuId)
    {
        return $this->_model->whereIn('menu_id', Arr::wrap($menuId))->delete();
    }
}
