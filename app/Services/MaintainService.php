<?php

namespace App\Services;

use App\Http\Resources\ListAssetMaintainingResource;
use App\Http\Resources\ListAssetNeedMaintainResource;
use App\Http\Resources\ListPlanMaintainResource;
use App\Models\PlanMaintainAsset;
use App\Repositories\AssetRepository;
use App\Repositories\Manage\PlanMaintainAssetRepository;
use App\Repositories\Manage\PlanMaintainRepository;
use App\Repositories\SupplierRepository;
use Carbon\Carbon;
use Modules\Service\Repositories\OrganizationRepository;

class MaintainService
{
    public function __construct(
        protected AssetRepository $assetRepository,
        protected PlanMaintainAssetRepository $planMaintainAssetRepository,
        protected PlanMaintainRepository $planMaintainRepository,
        protected SupplierRepository $supplierRepository,
        protected OrganizationRepository $organizationRepository,
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
        $filters['status'] = PlanMaintainAsset::STATUS_NEW;
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
}
