<?php

namespace App\Services\Manage;

use App\Repositories\Manage\AssetLostRepository;
use App\Http\Resources\Manage\AssetLostResource;
use App\Support\AppErrorCode;

class AssetLostService
{
    public function __construct(
        protected AssetLostRepository $assetLostRepository,
    ) {

    }

    public function list(array $filters = [])
    {
        // Thêm trạng thái tài sản mất
        $filters['status'] = 4;

        $data = $this->assetLostRepository->getListing(
            $filters,
            [
                'id',
                'name',
                'code',
                'status',
                'user_id',
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

        $multi = array_reduce($data, function ($carry, $item) {
            return $carry && is_array($item);
        }, true);

        if ($multi) {
            foreach ($data as $asset) {
                $this->updateOneAsset($asset);
            }
        } else {
            $this->updateOneAsset($data);
        }


        // Update signing_date,description
        // ...

        return [
            'success' => true,
        ];
    }

    private function updateOneAsset($data)
    {
        $assetLost = $this->assetLostRepository->find($data['id']);
        // dd($assetLost);
        if (empty($assetLost)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2028,
            ];
        }

        $this->assetLostRepository->update($assetLost, ['status' => $data['status']]);
    }
}
