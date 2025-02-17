<?php

namespace App\Services;

use App\Http\Resources\InfoPlanMaintainResource;
use App\Http\Resources\ListAssetMaintainingResource;
use App\Http\Resources\ListAssetNeedMaintainResource;
use App\Http\Resources\ListPlanMaintainResource;
use App\Models\PlanMaintain;
use App\Models\PlanMaintainAsset;
use App\Models\PlanMaintainLog;
use App\Repositories\AssetRepository;
use App\Repositories\AssetTypeRepository;
use App\Repositories\Manage\PlanMaintainAssetRepository;
use App\Repositories\Manage\PlanMaintainRepository;
use App\Repositories\PlanMaintainChargeRepository;
use App\Repositories\PlanMaintainLogRepository;
use App\Repositories\PlanMaintainOrganizationRepository;
use App\Repositories\PlanMaintainSupplierRepository;
use App\Repositories\SupplierRepository;
use App\Support\Constants\AppErrorCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Service\Repositories\OrganizationRepository;

class MaintainService
{
    public function __construct(
        protected AssetRepository $assetRepository,
        protected PlanMaintainAssetRepository $planMaintainAssetRepository,
        protected PlanMaintainRepository $planMaintainRepository,
        protected SupplierRepository $supplierRepository,
        protected OrganizationRepository $organizationRepository,
        protected PlanMaintainOrganizationRepository $planMaintainOrganizationRepository,
        protected PlanMaintainSupplierRepository $planMaintainSupplierRepository,
        protected PlanMaintainChargeRepository $planMaintainChargeRepository,
        protected AssetTypeRepository $assetTypeRepository,
        protected PlanMaintainLogRepository $planMaintainLogRepository,
    ) {

    }

    public function getAssetNeedMaintain($filters)
    {
        $assetMaintaining    = $this->planMaintainAssetRepository->getListing(['status' => PlanMaintainAsset::STATUS_MAINTAINING]);
        $assetMaintainingIds = $assetMaintaining->pluck('asset_id')->toArray();
        $data                = $this->assetRepository->getAssetNeedMaintain($filters, $assetMaintainingIds);

        if ($data->isEmpty()) {
            return [];
        }

        return ListAssetNeedMaintainResource::make($data)->resolve();
    }

    public function getAssetNeedMaintainWithMonth($time)
    {
        $time        = Carbon::createFromFormat('m/Y', $time);
        $daysInMonth = $time->daysInMonth;
        $assets      = $this->assetRepository->getAssetNeedMaintainWithMonth($time->format('Y-m'))->groupBy('next_maintenance_date');

        $data = [];
        foreach ($assets as $date => $asset) {
            $day        = Carbon::parse($date)->day;
            $data[$day] = $asset->count();
        }

        for ($day = 1; $day <= $daysInMonth; ++$day) {
            if (empty($data[$day])) {
                $data[$day] = 0;
            }
        }

        ksort($data);

        return array_chunk($data, 7, true);
    }

    public function getAssetMaintaining($filters)
    {
        $result            = $this->planMaintainAssetRepository->getListing($filters);
        if ($result->isEmpty()) {
            return [];
        }

        return ListAssetMaintainingResource::make($result)->resolve();
    }

    public function getPlanMaintain($filters)
    {
        $data = $this->planMaintainRepository->getListPlanMaintain($filters, [
            'plan_maintain.id',
        ]);

        if ($data->isEmpty()) {
            return [];
        }

        $planMaintainIds = $data->pluck('id')->toArray();
        $data            = $this->planMaintainRepository->getListing([
            'id'    => array_unique($planMaintainIds),
            'page'  => $filters['page'] ?? null,
            'limit' => $filters['limit'] ?? null,
        ], with: [
            'planMaintainOrganizations' => ['organization'],
            'planMaintainSuppliers'     => ['supplier'],
        ]);

        return ListPlanMaintainResource::make($data)->resolve();
    }

    public function createPlanMaintain($data)
    {
        $planMaintainLast = PlanMaintain::orderBy('created_at', 'desc')->first();
        if (empty($planMaintainLast)) {
            $code = 'KHBD1';
        } else {
            $code = 'KHBD'. $planMaintainLast->id + 1;
        }
        $userId             = Auth::id();
        $data['code']       = $code;
        $data['type']       = PlanMaintain::TYPE_MAINTAIN;
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

            // gan nha cung cap cho ke hoach
            $insert = resolve(PlanMaintainSupplierService::class)->insertPlanMaintainSupplier($data['supplier_ids'],$planMaintain->id);
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

            $dataInsert = [];
            foreach ($data['assets_maintain'] as $assetMaintain) {
                $dataAssetMaintain = [
                    'asset_id'               => $assetMaintain['id'],
                    'plan_maintain_id'       => $planMaintain->id,
                    'start_date_maintain'    => $planMaintain->start_time,
                    'complete_date_maintain' => $planMaintain->end_time,
                    'status'                 => PlanMaintainAsset::STATUS_MAINTAINING,
                    'created_by'             => $userId,
                ];
                unset($assetMaintain['id']);
                $dataInsert[] = array_merge($dataAssetMaintain, $assetMaintain);
            }
            if (!empty($dataInsert)) {
                $insert = $this->planMaintainAssetRepository->insert($dataInsert);
                if (!$insert) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2099,
                    ];
                }
            }

            $insertLog = $this->planMaintainLogRepository->insertPlanMaintainLog(
                PlanMaintainLog::ACTION_CREATE_PLAN_MAINTAIN,
                $planMaintain->id,
            );
            if (!$insertLog) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2105,
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

    public function getInfoPlanMaintain($id)
    {
        $planMaintain = $this->planMaintainRepository->find($id)->load([
            'planMaintainAsset',
            'planMaintainOrganizations',
            'planMaintainSuppliers',
            'planMaintainCharge',
        ]);

        if (empty($planMaintain)) {
            return [];
        }

        return InfoPlanMaintainResource::make($planMaintain)->resolve();
    }

    public function completeAssetMaintain($configs)
    {
        DB::beginTransaction();
        try {
            foreach ($configs as $config) {
                $this->planMaintainAssetRepository->update($config['id'], [
                    'status' => PlanMaintainAsset::STATUS_COMPLETE_MAINTAINING,
                    'note'   => $config['note'] ?? null,
                ]);
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

    public function updatePlanMaintain($id, $data)
    {
        $planMaintain = $this->planMaintainRepository->find($id);
        if (empty($planMaintain)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2100,
            ];
        }

        $planMaintain->fill($data);
        $insertLog = $this->planMaintainLogRepository->insertPlanMaintainLog(
            PlanMaintainLog::ACTION_UPDATE_PLAN_MAINTAIN,
            $planMaintain->id,
            $planMaintain->getAttributes(),
            $planMaintain->getOriginal(),
        );
        if (!$insertLog) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2105,
            ];
        }
        DB::beginTransaction();
        try {
            if (!$planMaintain->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2101,
                ];
            }

            $updatePlanMaintainSupplier = resolve(PlanMaintainSupplierService::class)
                ->updatePlanMaintainSupplier($data['supplier_ids'], $planMaintain->id);
            if (!$updatePlanMaintainSupplier['success']) {
                DB::rollBack();

                return $updatePlanMaintainSupplier;
            }

            $updatePlanMaintainCharge = resolve(PlanMaintainChargeService::class)
                ->updatePlanMaintainCharge($data['user_ids'] ?? [], $planMaintain->id);
            if (!$updatePlanMaintainCharge['success']) {
                DB::rollBack();

                return $updatePlanMaintainCharge;
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

    public function completePlanMaintain($id)
    {
        $planMaintain = $this->planMaintainRepository->find($id);
        if (empty($planMaintain)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2100,
            ];
        }

        $planMaintain->status = PlanMaintain::STATUS_COMPLETE_MAINTAIN;
        DB::beginTransaction();
        try {
            if (!$planMaintain->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2101,
                ];
            }

            $this->planMaintainAssetRepository->deleteByCondition(['plan_maintain_id' => $id, 'status' => PlanMaintainAsset::STATUS_MAINTAINING]);
            $assetMaintainComplete = $this->planMaintainAssetRepository->getListing([
                'plan_maintain_id' => $id,
                'status'           => PlanMaintainAsset::STATUS_COMPLETE_MAINTAINING,
            ]);
            $assetIds = $assetMaintainComplete->pluck('asset_id')->toArray();
            if (!empty($assetIds)) {
                $assets       = $this->assetRepository->getListing(['id' => $assetIds]);
                $assetTypeIds = $assets->pluck('asset_type_id')->toArray();
                $assetTypes   = [];
                if (!empty($assetTypeIds)) {
                    $assetTypes = $this->assetTypeRepository->getListAssetType(['id' => $assetTypeIds])->keyBy('id')->toArray();
                }
                foreach ($assets as $asset) {
                    $asset->recent_maintenance_date = Carbon::now();
                    $asset->next_maintenance_date   = Carbon::now()->addMonths($assetTypes[$asset->asset_type_id]['maintenance_months'] ?? 3);
                    if (!$asset->save()) {
                        DB::rollBack();

                        return [
                            'success'    => false,
                            'error_code' => AppErrorCode::CODE_2104,
                        ];
                    }
                }
            }

            $insertLog = $this->planMaintainLogRepository->insertPlanMaintainLog(
                PlanMaintainLog::ACTION_COMPLETE_PLAN_MAINTAIN,
                $planMaintain->id,
            );
            if (!$insertLog) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2105,
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

    public function deletePlanMaintain($id)
    {
        $planMaintain = $this->planMaintainRepository->find($id);
        if (empty($planMaintain)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2100,
            ];
        }

        if (PlanMaintain::STATUS_MAINTAINING !== $planMaintain->status) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2103,
            ];
        }
        DB::beginTransaction();
        try {
            if (!$planMaintain->delete()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2102,
                ];
            }

            $this->planMaintainSupplierRepository->deleteByCondition(['plan_maintain_id' => $id]);
            $this->planMaintainOrganizationRepository->deleteByCondition(['plan_maintain_id' => $id]);
            $this->planMaintainChargeRepository->deleteByCondition(['plan_maintain_id' => $id]);
            $this->planMaintainAssetRepository->deleteByCondition(['plan_maintain_id' => $id]);
            $insertLog = $this->planMaintainLogRepository->insertPlanMaintainLog(
                PlanMaintainLog::ACTION_DELETE_PLAN_MAINTAIN,
                $planMaintain->id,
            );
            if (!$insertLog) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2105,
                ];
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
