<?php

namespace App\Repositories;

use App\Models\Industry;
use App\Repositories\Base\BaseRepository;

class IndustryRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Industry::class;
    }

    public function getListIndustry(array $filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()
            ->select($columns)
            ->with($with)
            ->orderBy('created_at', 'desc');

        if (!empty($filters['name'])) {
            $query->where('name', 'like', $filters['name'] . '%');
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function findIndustryByName($name)
    {
        return $this->_model->where('name', $name)->first();
    }
}
