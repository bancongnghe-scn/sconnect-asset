<?php

namespace App\Services\Inventory;

use App\Http\Resources\Inventory\PlanInventoryInfoResource;
use App\Http\Resources\ListPlanInventoryResource;
use App\Models\PlanInventoryAsset;
use App\Models\PlanMaintain;
use App\Models\PlanMaintainLog;
use App\Repositories\AssetRepository;
use App\Repositories\AssetTypeRepository;
use App\Repositories\Manage\PlanMaintainRepository;
use App\Repositories\PlanInventoryAssetRepository;
use App\Repositories\PlanInventoryFileRepository;
use App\Repositories\PlanMaintainLogRepository;
use App\Repositories\PlanMaintainOrganizationRepository;
use App\Services\PlanInventoryAssetService;
use App\Services\PlanMaintainAssetTypeService;
use App\Services\PlanMaintainChargeService;
use App\Services\PlanMaintainOrganizationService;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
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
        protected PlanInventoryFileRepository $planInventoryFileRepository,
        protected PlanMaintainOrganizationRepository $planMaintainOrganizationRepository,
    ) {
    }

    public function getPlanInventory($filters)
    {
        $filters['type'] = PlanMaintain::TYPE_INVENTORY;
        $data            = $this->planMaintainRepository->getListing($filters, with: [
            'planInventoryAsset',
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
            $data['asset_type_ids'] = PlanMaintain::TYPE_INVENTORY_NOT_AUTO == $data['type_inventory'] ? $data['asset_type_ids'] : PlanMaintain::ASSET_TYPE_INVENTORY_AUTO;
            $insert                 = resolve(PlanMaintainAssetTypeService::class)->insertPlanMaintainAssetType($planInventory->id, $data['asset_type_ids']);
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

            $insert = $this->planMaintainLogRepository->insertPlanMaintainLog(PlanMaintainLog::ACTION_START_PLAN_INVENTORY, $planInventory->id);
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
            if (PlanMaintain::STATUS_NEW == $planInventory->status) {
                // cap nhat don vi
                $update = resolve(PlanMaintainOrganizationService::class)->updatePlanMaintainOrganization($data['organization_ids'], $id);
                if (!$update['success']) {
                    DB::rollBack();

                    return $update;
                }

                // cap nhat loai tai san
                $data['asset_type_ids'] = PlanMaintain::TYPE_INVENTORY_NOT_AUTO == $data['type_inventory'] ? $data['asset_type_ids'] : PlanMaintain::ASSET_TYPE_INVENTORY_AUTO;
                $update                 = resolve(PlanMaintainAssetTypeService::class)
                    ->updatePlanMaintainAssetType($data['asset_type_ids'], $id);
                if (!$update['success']) {
                    DB::rollBack();

                    return $update;
                }

                $planInventory->type_inventory    = $data['type_inventory'];
                $planInventory->sent_notification = $data['sent_notification'];
            }

            if (!empty($data['user_ids'])) {
                $update = resolve(PlanMaintainChargeService::class)->updatePlanMaintainCharge($data['user_ids'], $id);
                if (!$update['success']) {
                    DB::rollBack();

                    return $update;
                }
            }

            $insert = $this->planMaintainLogRepository->insertPlanMaintainLog(PlanMaintainLog::ACTION_UPDATE_PLAN_INVENTORY, $planInventory->id, $data, $planInventory->toArray());
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2076,
                ];
            }


            $planInventory->fill([
                'name'       => $data['name'],
                'start_time' => $data['start_time'],
                'end_time'   => $data['end_time'],
                'note'       => $data['note'],
            ]);

            if (!$planInventory->save()) {
                DB::rollBack();

                return  [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2114,
                ];
            }

            if (PlanMaintain::STATUS_MAINTAINING == $planInventory->status && !empty($data['assets'])) {
                foreach ($data['assets'] as $assetInventory) {
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

    public function completePlanInventory($id)
    {
        $planInventory = $this->planMaintainRepository->find($id);
        if (empty($planInventory)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2113,
            ];
        }

        $planInventory->status = PlanMaintain::STATUS_COMPLETE_MAINTAIN;
        if (!$planInventory->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2114,
            ];
        }

        $insert = $this->planMaintainLogRepository->insertPlanMaintainLog(PlanMaintainLog::ACTION_COMPLETE_PLAN_INVENTORY, $planInventory->id);
        if (!$insert) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2076,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function deletePlanInventory($id)
    {
        $planInventory = $this->planMaintainRepository->getListing([
            'status' => [PlanMaintain::STATUS_MAINTAINING, PlanMaintain::STATUS_COMPLETE_MAINTAIN],
            'id'     => $id,
            'first'  => true,
        ]);
        if (!empty($planInventory)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2116,
            ];
        }

        if (!$this->planMaintainRepository->deleteMultipleByIds($id)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2119,
            ];
        }

        return [
            'success' => true,
        ];
    }

    /**
     * @param $planInventoryId
     * xu ly kiem ke voi file upload nhan su
     */
    public function uploadFileInventory($file, $planInventoryId)
    {
        $path     = $file->store('inventory', 'public');
        $contents = File::get(public_path('uploads').'/'.$path);
        $lines    = explode("\n", mb_convert_encoding($contents, 'UTF-8', 'UTF-16LE'));
        $data     = [];

        foreach ($lines as $line) {
            if (str_contains($line, ':')) {
                list($key, $value) = explode(':', $line, 2);
                $data[trim($key)]  = trim($value);
            }
        }

        $userId             = Auth::id();
        $listAssetInventory = $this->planInventoryAssetRepository->getListing([
            'plan_maintain_id' => $planInventoryId,
            'user_id'          => $userId,
        ], with: ['asset']);

        DB::beginTransaction();
        try {
            foreach ($listAssetInventory as $assetInventory) {
                $key = array_search($assetInventory->asset?->asset_type_id, PlanMaintain::ASSET_TYPE_INVENTORY_AUTO);
                if (false !== $key) {
                    $assetInventory->status = PlanInventoryAsset::STATUS_INVENTORIED;
                    if (Str::slug($data[$key]) != Str::slug($assetInventory->asset->config_info)) {
                        $assetInventory->config_info_present = $data[$key];
                    }
                    if (!$assetInventory->save()) {
                        return [
                            'success'    => false,
                            'error_code' => AppErrorCode::CODE_2117,
                        ];
                    }
                }
            }

            $insert = $this->planInventoryFileRepository->insert([
                'plan_maintain_id' => $planInventoryId,
                'file_url'         => $path,
                'file_name'        => $file->getClientOriginalName(),
                'user_id'          => $userId,
            ]);
            if (!$insert) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2111,
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

    public function getListPlanInventoryUser($filters)
    {
        $deptId = Auth::user()?->dept_id;
        if (empty($deptId)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2120,
            ];
        }
        $organizationParent        = $this->organizationRepository->getParentOrganization($deptId);
        $planInventoryOrganization = $this->planMaintainOrganizationRepository->getByOrganizationId($organizationParent->id);
        if ($planInventoryOrganization->isEmpty()) {
            return [
                'success'    => true,
                'data'       => [],
            ];
        }

        $planMaintainIds = $planInventoryOrganization->pluck('plan_maintain_id')->toArray();

        $filters = array_merge($filters, [
            'type'           => PlanMaintain::TYPE_INVENTORY,
            'id'             => $planMaintainIds,
            'status'         => [PlanMaintain::STATUS_MAINTAINING, PlanMaintain::STATUS_COMPLETE_MAINTAIN],
            'type_inventory' => PlanMaintain::TYPE_INVENTORY_AUTO,
        ]);
        $planInventory = $this->planMaintainRepository->getListing($filters);

        return [
            'success' => true,
            'data'    => $planInventory->toArray(),
        ];
    }

    public function getFileUploaded($id)
    {
        $file = $this->planInventoryFileRepository->getFileUploadedLast($id);
        if (empty($file)) {
            return [];
        }

        return $file->toArray();
    }
}
