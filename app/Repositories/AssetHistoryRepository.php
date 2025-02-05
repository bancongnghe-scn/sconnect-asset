<?php

namespace App\Repositories;

use App\Models\Asset;
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
        $assets      = Asset::whereIn('id', $assetIds)
            ->get()
            ->load(['user' => function ($query) {
                $query->select('id', 'dept_id');
            }]);
        foreach ($assets as $asset) {
            $dataHistory[] = [
                'asset_id'              => $asset->id,
                'action'                => $status,
                'date'                  => new \DateTime(),
                'created_at'            => new \DateTime(),
                'created_by'            => Auth::id(),
                'org_id'                => $asset?->user?->getOrgLastParentAttribute()?->id ?? null,
            ];
        }

        if (!empty($dataHistory) && !$this->_model->insert($dataHistory)) {
            return false;
        }

        return true;
    }
}
