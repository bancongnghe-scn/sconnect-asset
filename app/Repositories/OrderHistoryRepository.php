<?php

namespace App\Repositories;

use App\Models\OrderHistory;
use App\Repositories\Base\BaseRepository;

class OrderHistoryRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return OrderHistory::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);

        if (!empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['first'])) {
            return $query->first();
        }

        return $query->get();
    }
}
