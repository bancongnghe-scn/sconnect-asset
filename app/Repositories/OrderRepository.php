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
        $query = $this->_model->newQuery()->select($columns)->orderBy('created_at', 'desc');

        if (!empty($filters['code_name'])) {
            $query->where('name', 'like', $filters['code_name'] . '%')
                ->orWhere('code', $filters['code_name']);
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        if (!empty($filters['shopping_plan_company_id'])) {
            $query->where('shopping_plan_company_id', $filters['shopping_plan_company_id']);
        }

        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (!empty($filters['first'])) {
            return $query->first();
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

    public function getLateOrder()
    {
        return $this->_model->latest()->first();
    }
}