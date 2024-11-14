<?php

namespace App\Repositories;

use App\Models\AssetType;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class AssetTypeRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return AssetType::class;
    }

    public function getListAssetType(array $filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()
            ->select($columns)
            ->with($with)
            ->orderBy('created_at', 'desc');

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

    public function deleteMultipleByIds($ids)
    {
        return $this->_model->whereIn('id', Arr::wrap($ids))->delete();
    }
}
