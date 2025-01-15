<?php

namespace App\Services;

use App\Http\Resources\ListAssetMaintainingResource;
use App\Http\Resources\ListAssetNeedMaintainResource;
use App\Http\Resources\ListPlanMaintainResource;
use App\Models\PlanMaintain;
use App\Models\PlanMaintainAsset;
use App\Repositories\AssetRepository;
use App\Repositories\Manage\PlanMaintainAssetRepository;
use App\Repositories\Manage\PlanMaintainRepository;
use App\Repositories\PlanMaintainChargeRepository;
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
        $filters['status'] = PlanMaintainAsset::STATUS_MAINTAINING;
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
            $dataInsert = [];
            foreach ($data['organization_ids'] as $organizationId) {
                $dataInsert[] = [
                    'plan_maintain_id' => $planMaintain->id,
                    'organization_id'  => $organizationId,
                ];
            }
            if (!empty($dataInsert)) {
                $insert = $this->planMaintainOrganizationRepository->insert($dataInsert);
                if (!$insert) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2096,
                    ];
                }
            }

            // gan nha cung cap cho ke hoach
            $dataInsert = [];
            foreach ($data['supplier_ids'] as $supplierId) {
                $dataInsert[] = [
                    'plan_maintain_id' => $planMaintain->id,
                    'supplier_id'      => $supplierId,
                ];
            }
            if (!empty($dataInsert)) {
                $insert = $this->planMaintainSupplierRepository->insert($dataInsert);
                if (!$insert) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2097,
                    ];
                }
            }

            if (!empty($data['user_ids'])) {
                // gan nha nguoi phu trach cho ke hoach
                $dataInsert = [];
                foreach ($data['user_ids'] as $userId) {
                    $dataInsert[] = [
                        'plan_maintain_id' => $planMaintain->id,
                        'user_id'          => $userId,
                    ];
                }
                if (!empty($dataInsert)) {
                    $insert = $this->planMaintainChargeRepository->insert($dataInsert);
                    if (!$insert) {
                        DB::rollBack();

                        return [
                            'success'    => false,
                            'error_code' => AppErrorCode::CODE_2098,
                        ];
                    }
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

            DB::commit();

            return [
                'success' => true,
            ];

        } catch (\Throwable $exception) {
            dd($exception);
            DB::rollBack();
            report($exception);

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }
}
