<?php

namespace App\Repositories;

use App\Models\SupplierAssetIndustry;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class SupplierAssetIndustryRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return SupplierAssetIndustry::class;
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->newQuery()
            ->select($columns)
            ->with($with);

        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        return $query->get();
    }

    public function removeIndustriesOfSupplier($industriesIds, $supplierId)
    {
        return $this->_model->where('supplier_id', $supplierId)
            ->whereIn('industries_id', Arr::wrap($industriesIds))->delete();
    }
}
