<?php

namespace App\Repositories;

use App\Models\ShoppingPlanLog;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class ShoppingPlanLogRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ShoppingPlanLog::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->select($columns)->newQuery()->orderBy('created_at', 'desc');

        if (!empty($filters['record_id'])) {
            $query->whereIn('record_id', Arr::wrap($filters['record_id']));
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }
}