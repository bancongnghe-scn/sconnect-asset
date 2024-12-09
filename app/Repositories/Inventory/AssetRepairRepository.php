<?php

namespace App\Repositories\Inventory;

use App\Models\AssetRepair;
use App\Repositories\Base\BaseRepository;

class AssetRepairRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return AssetRepair::class;
    }

    public function getListAssetRepair(array $filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()->select($columns)->with($with);

        if (!empty($filters['name_code'])) {
            $query->whereHas('asset', function ($q) use ($filters) {
                $q->where('assets.code', 'LIKE', $filters['name_code'] . '%')
                  ->orWhere('assets.name', 'LIKE', $filters['name_code'] . '%');
            });
        }

        if (!empty($filters['limit'])) {
            return $query->paginate($filters['limit'], page: $filters['page'] ?? 1);
        }

        return $query->get();
    }

    public function updateAssetRepaired($id, $dataUpdate)
    {
        return $this->_model->newQuery()->where('id', $id)->update($dataUpdate);
    }

    public function getMultiAssetRepairById($ids, $columns = ['*'], $with = [])
    {
        return $this->_model->newQuery()->select($columns)->whereIn('id', $ids)->with($with)->get();
    }
}
