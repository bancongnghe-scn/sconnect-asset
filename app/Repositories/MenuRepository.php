<?php

namespace App\Repositories;

use App\Models\Rbac\Menu;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class MenuRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Menu::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);
        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        return $query->get();
    }

    public function getFirst($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);
        if (!empty($filters['name'])) {
            $query->where('name', $filters['name']);
        }

        if (!empty($filters['id'])) {
            $query->where('id', $filters['id']);
        }

        return $query->first();
    }

    public function getListMenuByFilters($filters)
    {
        $query = $this->_model->newQuery()->select(
            'menus.id',
            'menus.name',
            'menus.description',
            DB::raw('GROUP_CONCAT(menu_roles.role_id ORDER BY menu_roles.role_id ASC) as role_ids')
        )
            ->join('menu_roles', 'menu_roles.menu_id', 'menus.id')
            ->orderBy('menus.created_at', 'desc')
            ->groupBy('menus.id', 'menus.name', 'menus.description');

        if (!empty($filters['name'])) {
            $query->where('menus.name', 'like', $filters['name'].'%');
        }

        if (!empty($filters['role_ids'])) {
            $query->whereIn('menu_roles.role_id', Arr::wrap($filters['role_ids']));
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function getListMenuParent()
    {
        return $this->_model->whereNull('parent_id')->get();
    }
}
