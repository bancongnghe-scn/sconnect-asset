<?php

namespace App\Repositories\Rbac;

use App\Models\Rbac\Permission;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class PermissionRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Permission::class;
    }

    public function getFirst(array $filters, array $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['id'])) {
            $query->where('id', $filters['id']);
        }

        if (!empty($filters['name'])) {
            $query->where('name', $filters['name']);
        }

        return $query->first();
    }

    public function getListing(array $filters, array $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with)->orderBy('created_at', 'DESC');

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', $filters['name'] . '%');
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }
}
