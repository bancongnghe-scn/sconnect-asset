<?php

namespace App\Services\Manage;

use App\Models\Asset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Support\Constants\AppErrorCode;
use App\Repositories\AssetHistoryRepository;
use App\Repositories\Manage\AssetLostRepository;
use App\Http\Resources\Manage\AssetLostResource;

class AssetLostService
{
    public function __construct(
        protected AssetLostRepository $assetLostRepository,
        protected AssetHistoryRepository $assetHistoryRepository,
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
                'location',
            ],
            [
                'user:id,name',
                'assetHistory' => function ($query) {
                    $query->select('asset_id', 'date', 'description')
                        ->where('action', Asset::STATUS_LOST)
                        ->orderBy('date', 'desc');
                },
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
        DB::beginTransaction();
        try {
            foreach ($data as $asset) {
                $result = $this->updateOneAsset($asset);
                if (!$result['success']) {
                    DB::rollBack();

                    return [
                        'success'       => false,
                        'error_code'    => AppErrorCode::CODE_5000,
                    ];
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success'       => false,
                'error_code'    => AppErrorCode::CODE_5001,
            ];
        }

        return [
            'success' => true,
        ];

    }

    private function updateOneAsset($data)
    {
        $assetLost = $this->assetLostRepository->find($data['id']);
        if (is_null($assetLost)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5000,
            ];
        }
        $data['updated_by'] = Auth::id();
        $updateStatus       = ['status' => $data['status']];
        $assetLost->fill($updateStatus);
        if (!$assetLost->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5000,
            ];
        }

        // update asset history
        $dataHistory = [
            'asset_id'              => $data['id'],
            'action'                => $data['status'],
            'date'                  => $data['signing_date'] ?? new \DateTime(),
            'description'           => $data['description'] ?? '',
            'created_at'            => new \DateTime(),
            'created_by'            => Auth::id(),
        ];

        $historyAsset = $this->assetHistoryRepository->insert($dataHistory);
        if (!$historyAsset) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5011,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
