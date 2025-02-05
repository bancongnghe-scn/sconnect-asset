<?php

namespace App\Services\Inventory;

use App\Http\Resources\Manage\PlanMaintainResource;
use App\Http\Resources\PlanInventoryResource;
use App\Models\PlanMaintain;
use App\Models\PlanMaintainAsset;
use App\Models\PlanMaintainLog;
use App\Repositories\AssetTypeRepository;
use App\Repositories\Manage\PlanMaintainRepository;
use App\Services\PlanMaintainChargeService;
use App\Services\PlanMaintainOrganizationService;
use App\Services\PlanMaintainSupplierService;
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
    )
    {
    }

    public function getPlanInventory($filters)
    {
        $filters['type'] = PlanMaintain::TYPE_INVENTORY;
        $data = $this->planMaintainRepository->getListing($filters, with: [
            'planMaintainAsset',
            'planMaintainOrganizations' => ['organization'],
            'planMaintainAssetTypes' => ['assetType']
        ]);

        if (empty($data)) {
            return [];
        }

        return PlanInventoryResource::make($data)->resolve();
    }

    public function createPlanInventory($data)
    {
        $planMaintainLast = PlanMaintain::orderBy('created_at', 'desc')->first();
        if (empty($planMaintainLast)) {
            $code = 'KHKK1';
        } else {
            $code = 'KHKK'. $planMaintainLast->id + 1;
        }
        $userId             = Auth::id();
        $data['code']       = $code;
        $data['type']       = PlanMaintain::TYPE_INVENTORY;
        $data['status']     = PlanMaintain::STATUS_MAINTAINING;
        $data['created_by'] = $userId;

        DB::beginTransaction();
        try {
            $planMaintain = $this->planMaintainRepository->create($data);

            // gan don vi cho ke hoach
            $insert = resolve(PlanMaintainOrganizationService::class)->insertPlanMaintainOrganization($data['organization_ids'],$planMaintain->id);
            if (!$insert['success']) {
                DB::rollBack();

                return $insert;
            }


            if (!empty($data['user_ids'])) {
                // gan nha nguoi phu trach cho ke hoach
                $insert = resolve(PlanMaintainChargeService::class)->insertPlanMaintainCharge($data['user_ids'],$planMaintain->id);
                if (!$insert['success']) {
                    DB::rollBack();

                    return $insert;
                }
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
}
