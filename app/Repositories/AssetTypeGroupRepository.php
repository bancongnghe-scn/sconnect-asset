<?php

namespace App\Repositories;

use App\Models\AssetTypeGroup;
use App\Repositories\Base\BaseRepository;

class AssetTypeGroupRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return AssetTypeGroup::class;
    }

    public function getListAssetTypeGroup(array $filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns)->orderBy('created_at', 'desc');

        if (!empty($filters['name'])) {
            $query->where('name', 'like',$filters['name'] . '%');
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function getAssetTypeByName($name)
    {
        return $this->_model->where('name', $name)->first();
    }
}
