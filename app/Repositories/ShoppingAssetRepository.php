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
}
