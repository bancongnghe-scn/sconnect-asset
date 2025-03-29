<?php

namespace App\Repositories;

use App\Models\ShoppingArise;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class ShoppingAriseRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ShoppingArise::class;
    }

    public function getListing($filters = [], $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns)->orderBy('created_at', 'desc');

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['start_time']) && !empty($filters['end_time'])) {
            $query->whereBetween('created_at', [$filters['start_time'], $filters['end_time']]);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function deleteByIds($ids)
    {
        return $this->_model->whereIn('id', $ids)->delete();
    }
}
