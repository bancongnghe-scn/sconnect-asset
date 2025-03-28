<?php

namespace App\Services;

use App\Models\ShoppingArise;
use App\Models\ShoppingAriseLog;
use App\Models\ShoppingAsset;
use App\Repositories\ShoppingAriseLogRepository;
use App\Repositories\ShoppingAriseRepository;
use App\Repositories\ShoppingAssetRepository;
use App\Support\Constants\AppErrorCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Service\Repositories\OrganizationRepository;

class ShoppingAriseService
{
    public function __construct(
        protected ShoppingAriseRepository $shoppingAriseRepository,
        protected OrganizationRepository $organizationRepository,
        protected ShoppingAssetRepository $shoppingAssetRepository,
        protected ShoppingAriseLogRepository $shoppingAriseLogRepository,
    ) {

    }

    public function createShoppingArise($data)
    {
        $userId       = Auth::id();
        $organization = $this->organizationRepository->getListing([
            'manager_id' => $userId,
            'first'      => true,
        ]);

        if (empty($organization)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2121,
            ];
        }

        DB::beginTransaction();
        try {
            $shoppingArise = $this->shoppingAriseRepository->create([
                'name'            => $data['name'],
                'status'          => ShoppingArise::STATUS_NEW,
                'organization_id' => $organization->id,
                'created_by'      => $userId,
            ]);

            $dataInsert = [];
            $now        = Carbon::now();
            foreach ($data['assets'] as $asset) {
                $dataInsert[] = [
                    'asset_type_id'       => $asset['asset_type_id'],
                    'job_id'              => $asset['job_id'],
                    'organization_id'     => $organization->id,
                    'quantity_registered' => $asset['quantity_registered'],
                    'quantity_approved'   => $asset['quantity_registered'],
                    'receiving_time'      => $asset['receiving_time'] ?? null,
                    'shopping_arise_id'   => $shoppingArise->id,
                    'year'                => $now->year,
                    'quarter'             => $now->quarter,
                    'month'               => $now->month,
                    'week'                => $now->week,
                    'description'         => $asset['description'] ?? null,
                    'status'              => ShoppingAsset::STATUS_PENDING_HR_MANAGER_APPROVAL,
                ];
            }

            $insert = $this->shoppingAssetRepository->insert($dataInsert);
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2121,
                ];
            }

            $insert = $this->shoppingAriseLogRepository->insertShoppingAriseLog(
                ShoppingAriseLog::ACTION_CREATE,
                $shoppingArise->id,
                $shoppingArise->toArray()
            );
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2076,
                ];
            }

            DB::commit();

            return [
                'success'    => true,
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
