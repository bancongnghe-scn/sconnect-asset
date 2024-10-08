<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Base\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Role::class;
    }

    public function getFirst(array $filters, array $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['name'])) {
            $query->where('name', $filters['name']);
        }

        return $query->first();
    }

    public function getListing(array $filters, array $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', $filters['name'] . '%');
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }
}
