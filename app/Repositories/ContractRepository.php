<?php

namespace App\Repositories;

use App\Models\Contract;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class ContractRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Contract::class;
    }

    public function getFirst(array $filters, array $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);
        if (!empty($filters['code'])) {
            $query->where('code', $filters['code']);
        }

        return $query->first();
    }

    public function getListing(array $filters, array $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['name_code'])) {
            $query->where('code', $filters['name_code'])
                ->orWhere('name', 'LIKE', $filters['name_code'] . '%');
        }

        if (!empty($filters['type'])) {
            $query->whereIn('type', Arr::wrap($filters['type']));
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        if (!empty($filters['signing_date'])) {
            $query->whereDate('signing_date', $filters['signing_date']);
        }

        if (!empty($filters['from'])) {
            $query->whereDate('from', $filters['from']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }
}
