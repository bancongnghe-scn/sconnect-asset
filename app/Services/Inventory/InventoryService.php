<?php

namespace App\Services\Inventory;

use App\Http\Resources\Inventory\PlanInventoryInfoResource;
use App\Http\Resources\ListPlanInventoryResource;
use App\Models\PlanMaintain;
use App\Models\PlanMaintainLog;
use App\Repositories\AssetRepository;
use App\Repositories\AssetTypeRepository;
use App\Repositories\Manage\PlanMaintainRepository;
use App\Repositories\PlanInventoryAssetRepository;
use App\Repositories\PlanMaintainLogRepository;
use App\Services\PlanInventoryAssetService;
use App\Services\PlanMaintainAssetTypeService;
use App\Services\PlanMaintainChargeService;
use App\Services\PlanMaintainOrganizationService;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Service\Repositories\OrganizationRepository;

class InventoryService
{
    public function __construct(
        protected PlanMaintainRepository $planMaintainRepository,
        protected OrganizationRepository $organizationRepository,
        protected AssetTypeRepository $assetTypeRepository,
        protected PlanMaintainLogRepository $planMaintainLogRepository,
        protected AssetRepository $assetRepository,
        protected PlanInventoryAssetRepository $planInventoryAssetRepository,
    ) {
    }

    public function getPlanInventory($filters)
    {
        $filters['type'] = PlanMaintain::TYPE_INVENTORY;
        $data            = $this->planMaintainRepository->getListing($filters, with: [
            'planMaintainAsset',
            'planMaintainOrganizations' => ['organization'],
            'planMaintainAssetTypes'    => ['assetType'],
        ]);

        if (empty($data)) {
            return [];
        }

        return ListPlanInventoryResource::make($data)->resolve();
    }

    public function createPlanInventory($data)
    {
        $planInventoryLast   = PlanMaintain::orderBy('created_at', 'desc')->first();
        $data['code']        = empty($planInventoryLast) ? 'KHKK1' : 'KHKK'. $planInventoryLast->id + 1;
        $data['type']        = PlanMaintain::TYPE_INVENTORY;
        $data['status']      = PlanMaintain::STATUS_NEW;
        $data['created_by']  = Auth::id();

        DB::beginTransaction();
        try {
            $planInventory = $this->planMaintainRepository->create($data);

            // gan don vi cho ke hoach
            $insert = resolve(PlanMaintainOrganizationService::class)->insertPlanMaintainOrganization($data['organization_ids'], $planInventory->id);
            if (!$insert['success']) {
                DB::rollBack();

                return $insert;
            }

            // gan loai tai san cho ke hoach
            $insert = resolve(PlanMaintainAssetTypeService::class)->insertPlanMaintainAssetType($planInventory->id, $data['asset_type_ids']);
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2112,
                ];
            }

            if (!empty($data['user_ids'])) {
                // gan nha nguoi phu trach cho ke hoach
                $insert = resolve(PlanMaintainChargeService::class)->insertPlanMaintainCharge($data['user_ids'], $planInventory->id);
                if (!$insert['success']) {
                    DB::rollBack();

                    return $insert;
                }
            }

            $insert = $this->planMaintainLogRepository->insertPlanMaintainLog(PlanMaintainLog::ACTION_CREATE_PLAN_INVENTORY, $planInventory->id);
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2076,
                ];
            }
            DB::commit();

            return [
                'success' => true,
            ];

        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function findPlanInventory($id)
    {
        $planInventory = $this->planMaintainRepository->find($id);
        if (empty($planInventory)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2113,
            ];
        }

        return [
            'success' => true,
            'data'    => PlanInventoryInfoResource::make($planInventory)->resolve(),
        ];
    }

    public function startPlanInventory($id)
    {
        $planInventory = $this->planMaintainRepository->find($id);
        if (empty($planInventory)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2113,
            ];
        }

        if (PlanMaintain::STATUS_NEW !== $planInventory->status) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2116,
            ];
        }

        $planInventory->status = PlanMaintain::STATUS_MAINTAINING;
        DB::beginTransaction();
        try {
            if (!$planInventory->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2114,
                ];
            }

            $listAsset = $this->assetRepository->getListing([
                'organization_id' => $planInventory->planMaintainOrganizations->pluck('organization_id')->toArray(),
                'asset_type_id'   => $planInventory->planMaintainAssetTypes->pluck('asset_type_id')->toArray(),
            ]);

            $insert = resolve(PlanInventoryAssetService::class)->generalPlanInventoryAsset($listAsset, $planInventory->id);
            if (!$insert['success']) {
                DB::rollBack();

                return $insert;
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();
            report($exception);

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function updatePlanInventory($id, $data)
    {
        $planInventory = $this->planMaintainRepository->find($id);
        if (empty($planInventory)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2113,
            ];
        }

        if (PlanMaintain::STATUS_COMPLETE_MAINTAIN == $planInventory->staus) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2116,
            ];
        }

        DB::beginTransaction();
        try {
            if (PlanMaintain::STATUS_NEW == $planInventory->status && !empty($data['organization_ids'])) {
                $update = resolve(PlanMaintainOrganizationService::class)
                    ->updatePlanMaintainOrganization($data['organization_ids'], $id);
                if (!$update['success']) {
                    DB::rollBack();

                    return $update;
                }
            }

            if (PlanMaintain::STATUS_NEW == $planInventory->status && !empty($data['asset_type_ids'])) {
                $update = resolve(PlanMaintainAssetTypeService::class)
                    ->updatePlanMaintainAssetType($data['asset_type_ids'], $id);
                if (!$update['success']) {
                    DB::rollBack();

                    return $update;
                }
            }

            if (!empty($data['user_ids'])) {
                $update = resolve(PlanMaintainChargeService::class)->updatePlanMaintainCharge($data['user_ids'], $id);
                if (!$update['success']) {
                    DB::rollBack();

                    return $update;
                }
            }

            $planInventory->fill([
                'name'       => $data['name'],
                'start_time' => $data['start_time'],
                'end_time'   => $data['end_time'],
                'note'       => $data['note'],
            ]);
            if (PlanMaintain::STATUS_NEW == $planInventory->status) {
                $planInventory->type_inventory    = $data['type_inventory'];
                $planInventory->sent_notification = $data['sent_notification'];
            }

            if (!$planInventory->save()) {
                DB::rollBack();

                return  [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2114,
                ];
            }

            if (PlanMaintain::STATUS_MAINTAINING == $planInventory->status && !empty($data['assets'])) {
                $planInventoryAsset = $data['assets']['inventory'] ?? [];
                foreach ($planInventoryAsset as $assetInventory) {
                    $update = $this->planInventoryAssetRepository->updatePlanInventoryAssetById($assetInventory['id'], $assetInventory);
                    if (!$update) {
                        DB::rollBack();

                        return [
                            'success'    => false,
                            'error_code' => AppErrorCode::CODE_2117,
                        ];
                    }
                }
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            report($exception);
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }
}
