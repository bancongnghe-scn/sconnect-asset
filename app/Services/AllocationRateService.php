<?php

namespace App\Services;

use App\Http\Resources\AllocationRate\ListAllocationRateOrganizationResource;
use App\Http\Resources\AllocationRate\ListAllocationRatePositionResource;
use App\Models\AllocationRate;
use App\Repositories\AllocationRateRepository;
use App\Repositories\AssetTypeRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Service\Repositories\JobTitleRepository;
use Modules\Service\Repositories\OrganizationRepository;

class AllocationRateService
{
    public function __construct(
        protected AllocationRateRepository $allocationRateRepository,
        protected AssetTypeRepository $assetTypeRepository,
        protected JobTitleRepository $jobTitleRepository,
        protected OrganizationRepository $organizationRepository,
    ) {

    }

    public function getListAllocationRate($filters)
    {
        $data = $this->allocationRateRepository->getList($filters);
        if ($data->isEmpty()) {
            return [];
        }

        $ids = [];
        foreach ($data as $item) {
            $ids = array_merge($ids, explode(',', $item->ids));
        }

        $data           = $data->toArray();
        $allocationRate = $this->allocationRateRepository->getList(['id' => $ids]);

        $assetTypeIds = $allocationRate->pluck('asset_type_id')->toArray();
        $assetTypes   = collect();
        if (!empty($assetTypeIds)) {
            $assetTypes = $this->assetTypeRepository->getListAssetType(['id' => array_unique($assetTypeIds)]);
        }

        $organizationIds = $allocationRate->pluck('organization_id')->toArray();
        $organizations   = collect();
        if (!empty($organizationIds)) {
            $organizations = $this->organizationRepository->getInfoOrganizationByFilters(['id' => array_unique($organizationIds)]);
        }

        if (AllocationRate::TYPE_POSITION == $filters['type']) {
            $positionIds = $allocationRate->pluck('position_id')->toArray();
            $positions   = collect();
            if (!empty($positionIds)) {
                $positions = $this->jobTitleRepository->getJobs(['id' => array_unique($positionIds)]);
            }
            $listAllocationPosition = ListAllocationRatePositionResource::make($allocationRate)->additional([
                'positions'     => $positions->keyBy('id')->toArray(),
                'assetTypes'    => $assetTypes->keyBy('id')->toArray(),
                'organizations' => $organizations->keyBy('id')->toArray(),
            ])->resolve();
            $data['data'] = array_values($listAllocationPosition);
        } else {
            $data['data'] = ListAllocationRateOrganizationResource::make($allocationRate)->additional([
                'assetTypes'    => $assetTypes->keyBy('id')->toArray(),
                'organizations' => $organizations->keyBy('id')->toArray(),
            ])->resolve();
        }

        return $data;
    }

    public function createAllocationRate($data)
    {
        if (AllocationRate::TYPE_POSITION == $data['type']) {
            $allocationRate = $this->allocationRateRepository->getAllocationRatePosition($data['organization_id'], $data['position_id'], true);
        } else {
            $allocationRate = $this->allocationRateRepository->getAllocationRateOrganization($data['organization_id'], true);
        }

        if (!empty($allocationRate)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2094,
            ];
        }

        $dataInsert = [];
        $userId     = Auth::id();
        foreach ($data['configs'] as $config) {
            $dataInsert[] = [
                'organization_id' => $data['organization_id'],
                'position_id'     => $data['position_id'] ?? null,
                'asset_type_id'   => $config['asset_type_id'],
                'price'           => $config['price'],
                'level'           => $config['level'] ?? null,
                'description'     => $config['description'] ?? null,
                'created_by'      => $userId,
            ];
        }

        $insert = $this->allocationRateRepository->insert($dataInsert);
        if (!$insert) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2095,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updateAllocationRate($data)
    {
        $dataNew = [];
        if (AllocationRate::TYPE_POSITION == $data['type']) {
            $listAllocationRate = $this->allocationRateRepository->getAllocationRatePosition($data['organization_id'], $data['position_id']);
        } else {
            $listAllocationRate = $this->allocationRateRepository->getAllocationRateOrganization($data['organization_id']);
        }
        $idsOld    = $listAllocationRate->pluck('id')->toArray();
        $idsUpdate = [];
        $userId    = Auth::id();
        DB::beginTransaction();
        try {
            foreach ($data['configs'] as $config) {
                if (!empty($config['id'])) {
                    $this->allocationRateRepository->update($config['id'], [
                        'asset_type_id' => $config['asset_type_id'],
                        'level'         => $config['level'] ?? null,
                        'price'         => $config['price'],
                        'description'   => $config['description'],
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]);
                    $idsUpdate[] = $config['id'];
                    continue;
                }

                $dataNew[] = [
                    'organization_id' => $data['organization_id'],
                    'position_id'     => $data['position_id'] ?? null,
                    'asset_type_id'   => $config['asset_type_id'],
                    'price'           => $config['price'],
                    'level'           => $config['level'] ?? null,
                    'description'     => $config['description'] ?? null,
                    'created_by'      => $userId,
                ];
            }

            if (!empty($dataNew)) {
                $insert = $this->allocationRateRepository->insert($dataNew);
                if (!$insert) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2095,
                    ];
                }
            }

            $idsRemove = array_diff($idsOld, $idsUpdate);
            if (!empty($idsRemove)) {
                $this->allocationRateRepository->deleteByIds($idsRemove);
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            report($exception);
            dd($exception);
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function deleteAllocationRate($data)
    {
        if (AllocationRate::TYPE_POSITION == $data['type']) {
            $this->allocationRateRepository->deleteAllocationRatePosition($data['organization_id'], $data['position_id']);
        } else {
            $this->allocationRateRepository->deleteAllocationRateOrganization($data['organization_id']);
        }

        return [
            'success' => true,
        ];
    }
}
