<?php

namespace App\Repositories;

use App\Models\ShoppingPlanCompany;
use App\Repositories\Base\BaseRepository;

class ShoppingPlanCompanyRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ShoppingPlanCompany::class;
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['time'])) {
            $query->where('time', $filters['time']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }
}
