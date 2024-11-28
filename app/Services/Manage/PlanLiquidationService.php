<?php

namespace App\Services\Manage;

use App\Models\Asset;
use App\Models\PlanMaintain;
use App\Support\AppErrorCode;
use App\Models\PlanMaintainAsset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\AssetRepository;
use App\Http\Resources\Manage\PlanMaintainResource;
use App\Repositories\Manage\PlanMaintainRepository;
use App\Repositories\Manage\PlanMaintainAssetRepository;

class PlanLiquidationService
{
    public function __construct(
        protected PlanMaintainRepository $planMaintainRepository,
        protected PlanMaintainAssetRepository $planMaintainAssetRepository,
        protected AssetRepository $assetRepository,
    ) {
    }

    public function createPlanLiquidatoin($data)
    {
        DB::beginTransaction();
        try {
            $dataPlanLiquidation = [
                'name'              => $data['name'],
                'code'              => $data['code'],
                'note'              => $data['note'],
                'status'            => PlanMaintain::STATUS_NEW,
                'type'              => PlanMaintain::TYPE_LIQUIDATION,
                'asset_quantity'    => !empty($data['assets_id']) ? count($data['assets_id']) : 0,
                'created_at'        => new \DateTime(),
                'created_by'        => Auth::id(),
            ];
            // Thanh lý, Bảo dưỡng lưu ở plan_maintain
            $planLiquidation = $this->planMaintainRepository->create($dataPlanLiquidation);

            if (empty($planLiquidation)) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2006,
                ];
            }

            $asset_ids                = [];
            $dataPlanLiquidationAsset = [];
            if (!empty($data['assets_id'])) {
                foreach ($data['assets_id'] as $asset) {
                    $dataPlanLiquidationAsset[] = [
                        'plan_maintain_id'  => $planLiquidation->id,
                        'asset_id'          => $asset['id'],
                        'price'             => $asset['price_liquidation'],
                        'status'            => PlanMaintainAsset::STATUS_NEW,
                    ];
                }

                $asset_ids = array_column($data['assets_id'], 'id');

                // Bảng chung gian plan_maintain_asset giữa kế hoạch thanh lý và tài sản
                $planLiquidationAsset = $this->planMaintainAssetRepository->insert($dataPlanLiquidationAsset);
                if (!$planLiquidationAsset) {
                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2006,
                    ];
                }

                // Chuyển tài sản sang trạng thái Đang thanh lý để đợi xét duyệt trong plan
                if (!empty($asset_ids)) {
                    $updateAssets = $this->assetRepository->changeStatusAsset($asset_ids, Asset::STATUS_IN_LIQUIDATION);
                    if (!$updateAssets) {
                        return [
                            'success'    => false,
                            'error_code' => AppErrorCode::CODE_2006,
                        ];
                    }
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
                'error_code' => AppErrorCode::CODE_2006,
            ];
        }
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
                'asset_quantity',
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
                'asset_quantity',
            ]
        )->load([
            'planMaintainAsset:id,asset_id,plan_maintain_id,price,status',
            'planMaintainAsset.asset:id,name,code,reason',
            'user:id,name',
        ]);

        if (empty($planLiquidation)) {
            return [];
        }

        return $planLiquidation->toArray();
    }

    public function updateAssetToPlanLiquidation($data)
    {
        DB::beginTransaction();
        try {
            $planId   = $data['plan_id'];
            $assetIds = $data['asset_ids'];

            $dataPlanLiquidationAsset = [];
            $assets                   = $this->assetRepository->getElementAsset($assetIds, ['id', 'price_liquidation']);

            foreach ($assets as $asset) {
                $dataPlanLiquidationAsset[] = [
                    'plan_maintain_id'  => $planId,
                    'asset_id'          => $asset->id,
                    'price'             => $asset->price_liquidation,
                    'status'            => PlanMaintainAsset::STATUS_NEW,
                ];
            }
            $this->planMaintainAssetRepository->insert($dataPlanLiquidationAsset);

            $updateAssets = $this->assetRepository->changeStatusAsset($assetIds, Asset::STATUS_IN_LIQUIDATION);
            if (!$updateAssets) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2006,
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
                'error_code' => AppErrorCode::CODE_2006,
            ];
        }
    }

    public function deleteAssetFromPlanLiquidation($planMaintainAssetId)
    {
        DB::beginTransaction();
        try {
            $planMaintainAsset = $this->planMaintainAssetRepository->find($planMaintainAssetId);
            if (is_null($planMaintainAsset)) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2002,
                ];
            }

            // Chuyển trạng thái tài sản về đề nghị thanh lý
            $assetId      = $planMaintainAsset->asset_id;
            $updateAssets = $this->assetRepository->changeStatusAsset($assetId, Asset::STATUS_PROPOSAL_LIQUIDATION);
            if (!$updateAssets) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2006,
                ];
            }

            if (!$planMaintainAsset->delete()) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2003,
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
                'error_code' => AppErrorCode::CODE_2006,
            ];
        }
    }

    public function deleteMultiPlan($ids)
    {
        try {
            DB::beginTransaction();

            // TODO: Remove plan liquidation and revert asset to status STATUS_PROPOSAL_LIQUIDATION

            $assetIds = $this->planMaintainAssetRepository->getAssetOfPlanMaintain($ids, ['asset_id'])
                ->pluck('asset_id')
                ->toArray();
            if (!empty($assetIds)) {
                $updateAssets = $this->assetRepository->changeStatusAsset($assetIds, Asset::STATUS_PROPOSAL_LIQUIDATION);

                if (!$updateAssets) {
                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2006,
                    ];
                }
            }

            $result = $this->planMaintainRepository->deleteMultipleByIds($ids);

            if (!$result) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2005,
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
                'error_code' => AppErrorCode::CODE_2006,
            ];
        }
    }

    public function updatePlan($id, $dataUpdate)
    {
        $planMaintain = $this->planMaintainRepository->find($id);
        if (is_null($planMaintain)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2002,
            ];
        }

        $planMaintain->fill($dataUpdate);
        if (!$planMaintain->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2003,
            ];
        }

        if (!empty($dataUpdate['status'])) {
            if (in_array($dataUpdate['status'], [PlanMaintain::STATUS_APPROVAL, PlanMaintain::STATUS_REJECT])) {

                $assetIds = $this->planMaintainAssetRepository->getAssetOfPlanMaintain($id, ['asset_id', 'status']);
                if (PlanMaintain::STATUS_APPROVAL == $dataUpdate['status']) {

                    $statusActions = [
                        PlanMaintain::STATUS_APPROVAL => Asset::STATUS_LIQUIDATED,
                        PlanMaintain::STATUS_REJECT   => Asset::STATUS_PROPOSAL_LIQUIDATION,
                    ];

                    foreach ($statusActions as $filterStatus => $newAssetStatus) {
                        $filteredAssets = $assetIds->filter(fn ($item) => $item->status == $filterStatus);
                        if ($filteredAssets->isNotEmpty()) {
                            $assetIdsToUpdate  = $filteredAssets->pluck('asset_id')->toArray();
                            $changeStatusAsset = $this->assetRepository->changeStatusAsset($assetIdsToUpdate, $newAssetStatus);
                            if (!$changeStatusAsset) {
                                return [
                                    'success'    => false,
                                    'error_code' => AppErrorCode::CODE_2006,
                                ];
                            }
                        }
                    }
                } else {
                    if ($assetIds->isNotEmpty()) {
                        $changeStatusAsset = $this->assetRepository->changeStatusAsset($assetIds->pluck('asset_id')->toArray(), Asset::STATUS_PROPOSAL_LIQUIDATION);
                        if (!$changeStatusAsset) {
                            return [
                                'success'    => false,
                                'error_code' => AppErrorCode::CODE_2006,
                            ];
                        }
                    }
                }
            }
        }

        return [
            'success' => true,
        ];
    }

    public function updatePlanMaintainAsset($id, $dataUpdate)
    {
        $planMaintainAsset = $this->planMaintainAssetRepository->find($id);
        if (is_null($planMaintainAsset)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2002,
            ];
        }

        $planMaintainAsset->fill($dataUpdate);
        if (!$planMaintainAsset->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2003,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updateMultiPlanMaintainAsset($ids, $dataUpdate)
    {
        $planMaintainAsset = $this->planMaintainAssetRepository->updateMulti($ids, $dataUpdate);
        if (is_null($planMaintainAsset)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2002,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
