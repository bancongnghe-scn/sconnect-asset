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

    public function checkExistAsset($ids)
    {
        $count = $this->_model->whereIn('id', $ids)->count();
        if ($count === count($ids)) {
            return true;
        }

        return false;
    }

    public function getElementAsset($ids, $columns = ['*'])
    {
        return $this->_model->whereIn('id', $ids)
            ->select($columns)
            ->get();
    }
}
