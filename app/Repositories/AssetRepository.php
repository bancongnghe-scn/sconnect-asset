<?php

namespace App\Repositories;

use App\Models\Asset;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Arr;

class AssetRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return Asset::class;
    }

    public function changeStatusAsset($ids, $status)
    {
        $updatedRows = $this->_model->whereIn('id', Arr::wrap($ids))->update([
            'status' => $status,
        ]);

        return $updatedRows > 0;
    }

    public function getElementAssetByIds($ids, $columns = ['*'])
    {
        return $this->_model->whereIn('id', $ids)
            ->select($columns)
            ->get();
    }

    public function getListing($filters, $columns = ['*'], $with = [])
    {
        $query = $this->_model->select($columns)->with($with)->newQuery();

        if (!empty($filters['import_warehouse_id'])) {
            $query->where('import_warehouse_id', $filters['import_warehouse_id']);
        }

        return $query->get();
    }
}
