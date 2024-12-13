<?php

namespace App\Services;

use App\Models\ShoppingAsset;
use App\Models\ShoppingPlanCompany;
use App\Models\ShoppingPlanLog;
use App\Repositories\ShoppingAssetRepository;
use App\Repositories\ShoppingPlanCompanyRepository;
use App\Repositories\ShoppingPlanLogRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\DB;

class ShoppingAssetService
{
    public function __construct(
        protected ShoppingAssetRepository $shoppingAssetRepository,
        protected ShoppingPlanCompanyRepository $shoppingPlanCompanyRepository,
        protected ShoppingPlanLogRepository $shoppingPlanLogRepository,
    ) {

    }

    public function updateActionShoppingAssets($shoppingAssets)
    {
        $idShoppingAssetNew      = [];
        $idShoppingAssetRotation = [];
        foreach ($shoppingAssets as $shoppingAsset) {
            if (ShoppingAsset::ACTION_NEW == $shoppingAsset['action'] || is_null($shoppingAsset['action'])) {
                $idShoppingAssetNew[] = $shoppingAsset['id'];
            } else {
                $idShoppingAssetRotation[] = $shoppingAsset['id'];
            }
        }

        if (!empty($idShoppingAssetNew)) {
            $this->shoppingAssetRepository->updateShoppingAsset(['id' => $idShoppingAssetNew], ['action' => ShoppingAsset::ACTION_NEW]);
        }

        if (!empty($idShoppingAssetRotation)) {
            $this->shoppingAssetRepository->updateShoppingAsset(['id' => $idShoppingAssetRotation], ['action' => ShoppingAsset::ACTION_ROTATION]);
        }
    }

    public function sentInfoShoppingAsset($data)
    {
        $shoppingPlanCompany = $this->shoppingPlanCompanyRepository->find($data['shopping_plan_company_id']);
        if (empty($shoppingPlanCompany)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2058,
            ];
        }
        if (ShoppingPlanCompany::STATUS_HR_SYNTHETIC !== +$shoppingPlanCompany->status) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2074,
            ];
        }

        DB::beginTransaction();
        try {
            foreach ($data['assets'] as $asset) {
                $id = $asset['id'];
                unset($asset['id']);
                if (!empty($asset)) {
                    $this->shoppingAssetRepository->updateShoppingAsset(['id' => $id], $asset);
                }
            }

            $insertLog = $this->shoppingPlanLogRepository->insertShoppingPlanLog(
                ShoppingPlanLog::ACTION_SENT_INFO_SHOPPING_ASSET,
                $data['shopping_plan_company_id']
            );
            if (!$insertLog) {
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
            report($exception);
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }
    }
}
