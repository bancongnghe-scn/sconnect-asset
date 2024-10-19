<?php

namespace App\Repositories;

use App\Models\ShoppingPlanCompany;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

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
            $query->whereIn('time', Arr::wrap($filters['time']));
        }

        if (!empty($filters['type'])) {
            $query->whereIn('type', Arr::wrap($filters['type']));
        }

        if (!empty($filters['plan_year_id'])) {
            $query->whereIn('plan_year_id', Arr::wrap($filters['plan_year_id']));
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', $filters['name'].'%');
        }

        if (!empty($filters['from'])) {
            $query->where('start_time', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->where('end_time', '<=', $filters['to']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function getFirst($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);
        if (!empty($filters['time'])) {
            $query->where('time', $filters['time']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->first();
    }
}
