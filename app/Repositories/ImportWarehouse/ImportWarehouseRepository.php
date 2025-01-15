<?php

namespace App\Repositories\ImportWarehouse;

use App\Models\ImportWarehouse;
use App\Repositories\Base\BaseRepository;

class ImportWarehouseRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ImportWarehouse::class;
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->select($columns)->with($with)->newQuery();

        if (!empty($filters['code'])) {
            $query->where('code', $filters['code']);
        }

        if (!empty($filters['id'])) {
            $query->where('id', $filters['id']);
        }

        if (!empty($filters['code_name'])) {
            $query->where('name', 'like', $filters['code_name'] . '%')
                ->orWhere('code', $filters['code_name']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['created_by'])) {
            $query->where('created_by', $filters['created_by']);
        }

        if (!empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
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
