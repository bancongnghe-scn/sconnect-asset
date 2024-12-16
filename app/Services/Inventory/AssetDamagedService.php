<?php

namespace App\Services\Inventory;

use App\Models\Asset;
use App\Http\Resources\Inventory\AssetDamagedResource;
use App\Repositories\AssetHistoryRepository;
use App\Repositories\Inventory\AssetDamagedRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetDamagedService
{
    public function __construct(
        protected AssetDamagedRepository $assetDamagedRepository,
        protected AssetHistoryRepository $assetHistoryRepository,
    ) {

    }

    public function list(array $filters = [])
    {
        // Tài sản hỏng
        $filters['status'] = Asset::STATUS_DAMAGED;

        $data = $this->assetDamagedRepository->getListing(
            $filters,
            [
                'id',
                'name',
                'code',
                'status',
                'user_id',
                'price',
                'location',
            ],
            [
                'user:id,name',
                'assetHistory' => function ($query) {
                    $query->select('asset_id', 'date', 'description')
                        ->where('action', Asset::STATUS_CANCEL)
                        ->orderBy('date', 'desc');
                },
            ]
        );

        if ($data->isEmpty()) {
            return [];
        }

        return AssetDamagedResource::make($data)->resolve();
    }

    public function updateMultiAsset($data, $status)
    {
        DB::beginTransaction();
        try {
            $dataHistory = [];
            foreach ($data as $asset) {
                $result = $this->updateAsset($asset, $status);
                if (!$result['success']) {
                    DB::rollBack();

                    return [
                        'success'       => false,
                        'error_code'    => AppErrorCode::CODE_5001,
                    ];
                }

                $dataHistory[] = [
                    'asset_id'              => $asset['id'],
                    'action'                => $status,
                    'date'                  => new \DateTime(),
                    'description'           => $asset['reason'] ?? '',
                    'created_at'            => new \DateTime(),
                    'created_by'            => Auth::id() ?? 1,
                ];
            }

            $historyAsset = $this->assetHistoryRepository->insert($dataHistory);
            if (!$historyAsset) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_5011,
                ];
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

    public function updateAsset($data, $status)
    {
        $asset = $this->assetDamagedRepository->find($data['id']);

        if (is_null($asset)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5001,
            ];
        }
        $data['updated_by'] = Auth::id();
        $data['status']     = $status;
        $updateStatus       = Arr::except($data, ['id']);

        $asset->fill($updateStatus);
        if (!$asset->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5001,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
