<?php

namespace App\Services\Manage;

use App\Repositories\Manage\AssetLostRepository;
use App\Http\Resources\Manage\AssetLostResource;
use App\Models\Asset;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetLostService
{
    public function __construct(
        protected AssetLostRepository $assetLostRepository,
    ) {

    }

    public function getListAssetLost(array $filters = [])
    {
        $filters['status'] = Asset::STATUS_LOST;
        $data              = $this->assetLostRepository->getListAssetLost(
            $filters,
            [
                'id',
                'name',
                'code',
                'status',
                'user_id',
                'date',
                'location',
                'reason',
                'price_liquidation',
            ],
            [
                'user:id,name',
            ]
        );

        if ($data->isEmpty()) {
            return [];
        }

        return AssetLostResource::make($data)->resolve();
    }

    public function findAssetLost($id)
    {
        $assetLost = $this->assetLostRepository->find(
            $id,
            [
                'id',
                'name',
                'code',
                'asset_type_id',
                'created_at',
                'warranty_months',
                'price',
                'status',
                'user_id',
            ]
        )->load('user:id,name');

        if (empty($assetLost)) {
            return [];
        }

        return $assetLost->toArray();
    }

    public function updateAssetLost(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data as $asset) {
                $result = $this->updateOneAsset($asset);
                if (!$result['success']) {
                    DB::rollBack();

                    return [
                        'success' => false,
                    ];
                }
            }

            // Update signing_date,description to history
            // ...

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Exception $e) {

            return [
                'success' => false,
            ];
        }
    }

    private function updateOneAsset($data)
    {
        $assetLost = $this->assetLostRepository->find($data['id']);
        if (is_null($assetLost)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2004,
            ];
        }
        $data['updated_by'] = Auth::id();
        $updateStatus       = ['status' => $data['status']];
        $assetLost->fill($updateStatus);
        if (!$assetLost->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2008,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
