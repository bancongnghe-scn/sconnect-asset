<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class OrderRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Order::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function updateByCondition($filters, array $dataUpdate)
    {
        $query = $this->_model->newQuery();

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        return $query->update($dataUpdate) > 0;
    }
}
