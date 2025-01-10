<?php

namespace App\Services\Manage;

use App\Models\Asset;
use App\Models\PlanMaintain;
use App\Support\Constants\AppErrorCode;
use App\Models\PlanMaintainAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AssetRepository;
use App\Http\Resources\Manage\PlanMaintainResource;
use App\Repositories\AssetHistoryRepository;
use App\Repositories\Manage\PlanMaintainRepository;
use App\Repositories\Manage\PlanMaintainAssetRepository;

class PlanLiquidationService
{
    public function __construct(
        protected PlanMaintainRepository $planMaintainRepository,
        protected PlanMaintainAssetRepository $planMaintainAssetRepository,
        protected AssetRepository $assetRepository,
        protected AssetHistoryRepository $assetHistoryRepository,
    ) {
    }

    public function createPlanLiquidatoin($data)
    {
        try {
            DB::beginTransaction();

            $dataPlanLiquidation = [
                'name'           => $data['name'],
                'code'           => $data['code'],
                'note'           => $data['note'] ?? '',
                'status'         => PlanMaintain::STATUS_NEW,
                'type'           => PlanMaintain::TYPE_LIQUIDATION,
                'created_at'     => new \DateTime(),
                'created_by'     => Auth::id() ?? 1,
            ];
            // Thanh lý, Bảo dưỡng lưu ở plan_maintain
            $planLiquidation = $this->planMaintainRepository->create($dataPlanLiquidation);

            $asset_ids                = [];
            $dataPlanLiquidationAsset = [];
            if (!empty($data['assets_id'])) {
                foreach ($data['assets_id'] as $asset) {
                    $dataPlanLiquidationAsset[] = [
                        'plan_maintain_id'                  => $planLiquidation->id,
                        'asset_id'                          => $asset['id'],
                        'price'                             => $asset['price_liquidation'] ?? 1,
                        'status'                            => PlanMaintainAsset::STATUS_NEW,
                        'created_at'                        => new \DateTime(),
                        'created_by'                        => Auth::id() ?? 1,
                    ];
                }

                $asset_ids = array_column($data['assets_id'], 'id');
            }

            // Bảng chung gian plan_maintain_asset giữa kế hoạch thanh lý và tài sản
            if (!empty($dataPlanLiquidationAsset)) {
                $planLiquidationAsset = $this->planMaintainAssetRepository->insert($dataPlanLiquidationAsset);
                if (!$planLiquidationAsset) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_5002,
                    ];
                }
            }

            // Chuyển tài sản sang trạng thái Đang thanh lý để đợi xét duyệt trong plan
            if (!empty($asset_ids)) {
                $updateAssets = $this->assetRepository->changeStatusAsset($asset_ids, Asset::STATUS_IN_LIQUIDATION);
                if (!$updateAssets) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_5003,
                    ];
                }
            }
            DB::commit();
        } catch (\Throwable $exception) {
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

    public function listPlanLiquidation(array $filters = [])
    {
        $filters['type'] = PlanMaintain::TYPE_LIQUIDATION;
        $data            = $this->planMaintainRepository->getListing(
            $filters,
            [
                'id',
                'name',
                'code',
                'created_at',
                'status',
            ],
            [
                'planMaintainAsset:id,plan_maintain_id,price',
            ]
        );

        if ($data->isEmpty()) {
            return [];
        }

        return PlanMaintainResource::make($data)->resolve();
    }

    public function findPlanLiquidation($id)
    {
        $planLiquidation = $this->planMaintainRepository->find(
            $id,
            [
                'id',
                'name',
                'code',
                'note',
                'status',
                'created_at',
                'created_by',
            ]
        )->load([
            'planMaintainAsset:id,asset_id,plan_maintain_id,price,status,note',
            'planMaintainAsset.asset:id,name,code',
            'planMaintainAsset.asset.assetHistory' => function ($query) {
                $query->select('asset_id', 'date', 'description')
                    ->where('action', Asset::STATUS_PROPOSAL_LIQUIDATION)
                    ->orderBy('date', 'desc');
            },
            'user:id,name',
        ]);

        if (empty($planLiquidation)) {
            return [];
        }

        return $planLiquidation->toArray();
    }

    public function updateAssetToPlanLiquidation($data)
    {

        $planId   = $data['plan_id'];
        $assetIds = $data['asset_ids'];

        $dataPlanLiquidationAsset = [];
        $assets                   = $this->assetRepository->getElementAssetByIds(
            $assetIds,
            ['id'],
            ['assetHistory' => function ($query) {
                $query->where('action', Asset::STATUS_PROPOSAL_LIQUIDATION)
                    ->orderBy('date', 'desc');
            },
            ]
        );

        foreach ($assets as $asset) {
            $dataPlanLiquidationAsset[] = [
                'plan_maintain_id' => $planId,
                'asset_id'         => $asset->id,
                'price'            => $asset->assetHistory->first()->price ?? 1,
                'status'           => PlanMaintainAsset::STATUS_NEW,
                'created_by'       => Auth::id() ?? 1,
            ];
        }
        try {
            DB::beginTransaction();
            $insertplanLiquidationAsset = $this->planMaintainAssetRepository->insert($dataPlanLiquidationAsset);
            if (!$insertplanLiquidationAsset) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_5002,
                ];
            }

            $updateAssets = $this->assetRepository->changeStatusAsset($assetIds, Asset::STATUS_IN_LIQUIDATION);
            if (!$updateAssets) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_5003,
                ];
            }
            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5004,
            ];
        }
    }

    public function deleteAssetFromPlanLiquidation($planMaintainAssetId)
    {
        $planMaintainAsset = $this->planMaintainAssetRepository->find($planMaintainAssetId);
        if (is_null($planMaintainAsset)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5006,
            ];
        }

        // Chuyển trạng thái tài sản về đề nghị thanh lý
        $assetId = $planMaintainAsset->asset_id;

        DB::beginTransaction();
        try {
            $updateAssets = $this->assetRepository->changeStatusAsset($assetId, Asset::STATUS_PROPOSAL_LIQUIDATION);
            if (!$updateAssets) {

                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_5001,
                ];
            }

            if (!$planMaintainAsset->delete()) {

                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_5005,
                ];
            }
            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5005,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function deleteMultiPlan($ids)
    {
        // TODO: Remove plan liquidation and revert asset to status STATUS_PROPOSAL_LIQUIDATION
        $assetIds = $this->planMaintainAssetRepository
            ->getAssetOfPlanMaintain($ids, ['asset_id'])
            ->pluck('asset_id')
            ->toArray();

        DB::beginTransaction();
        try {
            if (!empty($assetIds)) {
                $updateAssets = $this->assetRepository->changeStatusAsset($assetIds, Asset::STATUS_PROPOSAL_LIQUIDATION);

                if (!$updateAssets) {

                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_5001,
                    ];
                }
            }

            $result = $this->planMaintainRepository->deleteMultipleByIds($ids);
            if (!$result) {

                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_5007,
                ];
            }

            DB::commit();
        } catch (\Exception $e) {

            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5007,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updatePlan($id, $dataUpdate)
    {
        $planMaintain = $this->planMaintainRepository->find($id);
        if (is_null($planMaintain)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5006,
            ];
        }

        $status = $dataUpdate['status'] ?? '';

        DB::beginTransaction();
        try {

            $planMaintain->fill($dataUpdate);
            if (!$planMaintain->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_5003,
                ];
            }

            $assetIds = $this->planMaintainAssetRepository->getAssetOfPlanMaintain($id, ['asset_id', 'status']);

            // Approval/Reject
            if (!empty($status) && in_array($status, [PlanMaintain::STATUS_APPROVAL, PlanMaintain::STATUS_REJECT])) {
                $statusActions = [
                    PlanMaintain::STATUS_APPROVAL => Asset::STATUS_LIQUIDATED,
                    PlanMaintain::STATUS_REJECT   => Asset::STATUS_PROPOSAL_LIQUIDATION,
                ];

                $historyAsset = [];

                if (PlanMaintain::STATUS_APPROVAL === $status) {
                    foreach ($statusActions as $filterStatus => $newAssetStatus) {
                        $filteredAssets = $assetIds->filter(fn ($item) => $item->status == $filterStatus);

                        if ($filteredAssets->isEmpty()) {
                            continue;
                        }

                        $assetIdsToUpdate = $filteredAssets->pluck('asset_id')->toArray();

                        // Cập nhật trạng thái tài sản
                        if (!$this->assetRepository->changeStatusAsset($assetIdsToUpdate, $newAssetStatus)) {
                            DB::rollBack();

                            return [
                                'success'    => false,
                                'error_code' => AppErrorCode::CODE_5003,
                            ];
                        }

                        foreach ($assetIdsToUpdate as $assetId) {
                            $historyAsset[] = [
                                'asset_id'              => $assetId,
                                'action'                => $newAssetStatus,
                                'date'                  => new \DateTime(),
                                'created_at'            => new \DateTime(),
                                'created_by'            => Auth::id(),
                            ];
                        }
                    }
                } else {
                    $newAssetStatus = Asset::STATUS_PROPOSAL_LIQUIDATION;

                    if ($assetIds->isNotEmpty()) {
                        $assetIdsToUpdate = $assetIds->pluck('asset_id')->toArray();

                        // update status asset
                        if (!$this->assetRepository->changeStatusAsset($assetIdsToUpdate, $newAssetStatus)) {
                            DB::rollBack();

                            return [
                                'success'    => false,
                                'error_code' => AppErrorCode::CODE_5003,
                            ];
                        }

                        foreach ($assetIdsToUpdate as $assetId) {
                            $historyAsset[] = [
                                'asset_id'              => $assetId,
                                'action'                => $newAssetStatus,
                                'date'                  => new \DateTime(),
                                'created_at'            => new \DateTime(),
                                'created_by'            => Auth::id(),
                            ];
                        }
                    }
                }

                // insert history
                $historyAsset = $this->assetHistoryRepository->insert($historyAsset);
                if (!$historyAsset) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_5011,
                    ];
                }
            }


            // send plan wait approval/reject
            if (!empty($status) && PlanMaintain::STATUS_PENDING == $status) {

                $assetIds     = $assetIds->pluck('asset_id')->toArray();
                $historyAsset = $this->assetHistoryRepository->insertHistoryAsset($assetIds, Asset::STATUS_IN_LIQUIDATION);
                if (!$historyAsset) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_5011,
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5008,
            ];
        }
    }

    public function updatePlanMaintainAsset($id, $status, $note = '')
    {
        $planMaintainAsset = $this->planMaintainAssetRepository->find($id);
        if (is_null($planMaintainAsset)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5006,
            ];
        }

        $updateStatus = ['status' => $status];
        $planMaintainAsset->fill($updateStatus);

        if (!empty($note)) {
            $planMaintainAsset->fill(['note' => $note]);
        }

        if (!$planMaintainAsset->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5008,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updateMultiPlanMaintainAsset($ids, $status, $note = '')
    {
        $updateStatus = [
            'status' => $status,
            'note'   => $note,
        ];

        DB::beginTransaction();
        try {
            $planMaintainAsset = $this->planMaintainAssetRepository->updateMulti($ids, $updateStatus);
            if (is_null($planMaintainAsset)) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_5008,
                ];
            }
            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_5008,
            ];
        }
    }
}
