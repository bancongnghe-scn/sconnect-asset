<?php

namespace App\Services;

use App\Http\Resources\ListShoppingAriseResource;
use App\Http\Resources\ShoppingAriseInfoResource;
use App\Models\ShoppingArise;
use App\Models\ShoppingAriseLog;
use App\Repositories\ShoppingAriseLogRepository;
use App\Repositories\ShoppingAriseRepository;
use App\Repositories\ShoppingAssetRepository;
use App\Support\Constants\AppErrorCode;
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

            $insert = resolve(ShoppingAssetService::class)->insertShoppingAssetArise($data['assets'], $organization->id, $shoppingArise->id);
            if (!$insert) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2123,
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

    public function getListShoppingArise($filters = [])
    {
        if (ShoppingArise::GET_OF_ORGANIZATION == $filters['type']) {
            $filters['created_by'] = Auth::id();
        } else {
            $filters['status_diff'] = ShoppingArise::STATUS_NEW;
        }
        $listShoppingArise = $this->shoppingAriseRepository->getListing($filters);
        if ($listShoppingArise->isEmpty()) {
            return [];
        }

        return ListShoppingAriseResource::make($listShoppingArise)->resolve();
    }

    public function deleteShoppingArise(array $ids)
    {
        $listShoppingArise = $this->shoppingAriseRepository->getListing([
            'id'     => $ids,
            'status' => ShoppingArise::STATUS_NEW,
        ]);

        if ($listShoppingArise->count() != count($ids)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2122,
            ];
        }

        $delete = $this->shoppingAriseRepository->deleteByIds($ids);
        if (!$delete) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2127,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function findShoppingArise($id)
    {
        $shoppingArise = $this->shoppingAriseRepository->find($id)->load('assets');
        if (empty($shoppingArise)) {
            return [];
        }

        return ShoppingAriseInfoResource::make($shoppingArise)->resolve();
    }

    public function updateShoppingArise($data, $id)
    {
        $shoppingArise = $this->shoppingAriseRepository->find($id);
        if (empty($shoppingArise)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2125,
            ];
        }

        try {
            $shoppingArise->name = $data['name'];
            if (!$shoppingArise->save()) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2126,
                ];
            }

            $assetIdsUpdate = [];
            $dataInsert     = [];
            foreach ($data['assets'] as $asset) {
                if (isset($asset['id'])) {
                    $assetIdsUpdate[] = $asset['id'];
                    $update           = $this->shoppingAssetRepository->update($asset['id'], $asset);
                    if (!$update) {
                        DB::rollBack();

                        return [
                            'success'    => false,
                            'error_code' => AppErrorCode::CODE_2123,
                        ];
                    }
                    continue;
                }

                $dataInsert[] = $asset;
            }

            $listAssets     = $shoppingArise->assets;
            $assetIdsOld    = $listAssets->pluck('id')->toArray();
            $assetIdsRemove = array_diff($assetIdsOld, $assetIdsUpdate);
            if (!empty($assetIdsRemove)) {
                $delete = $this->shoppingAssetRepository->deleteByIds($assetIdsRemove);
                if (!$delete) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2124,
                    ];
                }
            }

            if (!empty($dataInsert)) {
                $insert = resolve(ShoppingAssetService::class)->insertShoppingAssetArise(
                    $dataInsert,
                    $shoppingArise->organization_id,
                    $shoppingArise->id
                );
                if (!$insert) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2123,
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
            ];

        } catch (\Throwable $exception) {
            report($exception);

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }

    public function sendShoppingArise($id)
    {
        $shoppingArise = $this->shoppingAriseRepository->find($id);
        if (empty($shoppingArise)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2125,
            ];
        }

        if (ShoppingArise::STATUS_NEW != $shoppingArise->status) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2122,
            ];
        }

        $shoppingArise->status = ShoppingArise::STATUS_PENDING_PROCESSING;
        if (!$shoppingArise->save()) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2126,
            ];
        }

        return [
            'success' => true,
        ];
    }
}
