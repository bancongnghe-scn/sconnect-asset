<?php

namespace App\Repositories;

use App\Models\Contract;
use App\Repositories\Base\BaseRepository;

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
}
