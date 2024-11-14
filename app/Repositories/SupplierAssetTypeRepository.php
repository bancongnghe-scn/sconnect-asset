<?php

namespace App\Repositories;

use App\Models\SupplierAsseType;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class SupplierAssetTypeRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return SupplierAsseType::class;
    }

    public function getListing($filters = [], $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()
            ->select($columns)
            ->with($with);

        if (!empty($filters['supplier_id'])) {
            $query->whereIn('supplier_id', Arr::wrap($filters['supplier_id']));
        }

        return $query->get();
    }

    public function removeAssetTypeOfSupplier($assetTypeIds, $supplierId)
    {
        return $this->_model->where('supplier_id', $supplierId)
            ->whereIn('asset_type_id', Arr::wrap($assetTypeIds))->delete();
    }
}
