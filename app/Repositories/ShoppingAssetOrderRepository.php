<?php

namespace App\Repositories;

use App\Models\ShoppingAssetOrder;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class ShoppingAssetOrderRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ShoppingAssetOrder::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);

        if (!empty($filters['order_id'])) {
            $query->whereIn('order_id', Arr::wrap($filters['order_id']));
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        if (!empty($filters['first'])) {
            return $query->first();
        }

        return $query->get();
    }
}
