<?php

namespace App\Repositories;

use App\Models\AssetType;
use App\Repositories\Base\BaseRepository;

class AssetTypeRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return AssetType::class;
    }

    public function getListAssetType(array $filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', $filters['name'] . '%');
        }

        if (!empty($filters['asset_type_group_id'])) {
            $query->whereIn('asset_type_group_id', $filters['asset_type_group_id']);
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function findAssetTypeByName($name)
    {
        return $this->_model->where('name', $name)->first();
    }
}
