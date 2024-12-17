<?php

namespace App\Repositories;

use App\Models\ShoppingAsset;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class ShoppingAssetRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ShoppingAsset::class;
    }

    public function deleteByIds($ids)
    {
        return $this->_model->whereIn('id', $ids)->delete();
    }

    public function updateShoppingAsset($filters, $dataUpdate)
    {
        $query = $this->_model->newQuery();

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        return $query->update($dataUpdate) > 0;
    }

    public function getListing($filters, $columns = ['*'], $first = false)
    {
        $query = $this->_model->select($columns)->newQuery();
        if (!empty($filters['shopping_plan_company_id'])) {
            $query->whereIn('shopping_plan_company_id', Arr::wrap($filters['shopping_plan_company_id']));
        }

        if (!empty($filters['id'])) {
            $query->whereIn('id', Arr::wrap($filters['id']));
        }

        if (!empty($filters['status_other'])) {
            $query->whereNotIn('status', Arr::wrap($filters['status_other']));
        }

        if (!empty($filters['supplier_id'])) {
            $query->whereIn('supplier_id', Arr::wrap($filters['supplier_id']));
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', Arr::wrap($filters['status']));
        }

        if ($first) {
            return $query->first();
        }

        return $query->get();
    }
}
