<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class UserRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return User::class;
    }

    public function getListing(array $filers, array $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with)->where('status', User::STATUS_ACTIVE);

        if (!empty($filers['name'])) {
            $query->where('name', 'like', $filers['name'] . '%');
        }

        if (!empty($filers['dept_id'])) {
            $query->whereIn('dept_id', Arr::wrap($filers['dept_id']));
        }

        if (!empty($filers['id'])) {
            $query->whereIn('id', Arr::wrap($filers['id']));
        }

        if (!empty($filers['limit'])) {
            $query->paginate($filers['limit'], page: $filers['page'] ?? 1);
        }

        return $query->get();
    }
}
