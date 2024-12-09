<?php

namespace App\Repositories\Inventory;

use App\Models\Asset;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class AssetDamagedRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Asset::class;
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with)->orderBy('created_at', 'DESC');

        if (!empty($filters['name_code'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('code', 'LIKE', '%' . $filters['name_code'] . '%')
                  ->orWhere('name', 'LIKE', '%' . $filters['name_code'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }
}
