<?php

namespace App\Repositories;

use App\Models\AssetHistory;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Auth;

class AssetHistoryRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return AssetHistory::class;
    }

    public function insertHistoryAsset($assetIds, $status)
    {
        $dataHistory = [];
        foreach ($assetIds as $asset_id) {
            $dataHistory[] = [
                'asset_id'              => $asset_id,
                'action'                => $status,
                'date'                  => new \DateTime(),
                'created_at'            => new \DateTime(),
                'created_by'            => Auth::id(),
            ];
        }

        if (!empty($dataHistory)) {
            $historyAsset = $this->_model->insert($dataHistory);
            if (!$historyAsset) {

                return false;
            }
        }

        return true;
    }
}
