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

        if (ShoppingPlanCompany::STATUS_APPROVAL == +$shoppingPlanCompany->status) {
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

    public function setStatusWithMoneyByShoppingPlanCompanyId($shoppingPlanCompanyId)
    {
        $shoppingAssets = $this->shoppingAssetRepository->getListing(['shopping_plan_company_id' => $shoppingPlanCompanyId]);

        $shoppingAssetsHrApproval         = [];
        $shoppingAssetsAccountantApproval = [];
        $shoppingAssetsGeneralApproval    = [];
        foreach ($shoppingAssets as $shoppingAsset) {
            if (+$shoppingAsset->price < ShoppingAsset::PRICE_HR_APPROVAL) {
                $shoppingAssetsHrApproval[] = $shoppingAsset->id;
            } elseif (+$shoppingAsset->price > ShoppingAsset::PRICE_HR_APPROVAL && +$shoppingAsset->price < ShoppingAsset::PRICE_ACCOUNTANT_APPROVAL) {
                $shoppingAssetsAccountantApproval[] = $shoppingAsset->id;
            } else {
                $shoppingAssetsGeneralApproval[] = $shoppingAsset->id;
            }
        }

        if (!empty($shoppingAssetsHrApproval)) {
            $this->shoppingAssetRepository->updateShoppingAsset(
                ['id' => $shoppingAssetsHrApproval],
                ['status' => ShoppingAsset::STATUS_PENDING_HR_MANAGER_APPROVAL]
            );
        }

        if (!empty($shoppingAssetsAccountantApproval)) {
            $this->shoppingAssetRepository->updateShoppingAsset(
                ['id' => $shoppingAssetsAccountantApproval],
                ['status' => ShoppingAsset::STATUS_PENDING_ACCOUNTANT_APPROVAL]
            );
        }

        if (!empty($shoppingAssetsGeneralApproval)) {
            $this->shoppingAssetRepository->updateShoppingAsset(
                ['id' => $shoppingAssetsGeneralApproval],
                ['status' => ShoppingAsset::STATUS_PENDING_GENERAL_APPROVAL]
            );
        }
    }

    public function approvalShoppingAsset($data)
    {
        if (in_array($data['status'], [ShoppingAsset::STATUS_HR_MANAGER_APPROVAL, ShoppingAsset::STATUS_HR_MANAGER_DISAPPROVAL])) {
            $shoppingAsset = $this->shoppingAssetRepository->getListing([
                'id'           => $data['ids'],
                'status_other' => [
                    ShoppingAsset::STATUS_PENDING_HR_MANAGER_APPROVAL,
                    ShoppingAsset::STATUS_HR_MANAGER_APPROVAL,
                    ShoppingAsset::STATUS_HR_MANAGER_DISAPPROVAL,
                ],
            ], first: true);
        } elseif (in_array($data['status'], [ShoppingAsset::STATUS_ACCOUNTANT_APPROVAL, ShoppingAsset::STATUS_ACCOUNTANT_DISAPPROVAL])) {
            $shoppingAsset = $this->shoppingAssetRepository->getListing([
                'id'           => $data['ids'],
                'status_other' => [
                    ShoppingAsset::STATUS_PENDING_ACCOUNTANT_APPROVAL,
                    ShoppingAsset::STATUS_ACCOUNTANT_APPROVAL,
                    ShoppingAsset::STATUS_ACCOUNTANT_DISAPPROVAL,
                ],
            ], first: true);
        } else {
            $shoppingAsset = $this->shoppingAssetRepository->getListing([
                'id'           => $data['ids'],
                'status_other' => [
                    ShoppingAsset::STATUS_PENDING_GENERAL_APPROVAL,
                    ShoppingAsset::STATUS_GENERAL_APPROVAL,
                    ShoppingAsset::STATUS_GENERAL_DISAPPROVAL,
                ],
            ], first: true);
        }
        if (!empty($shoppingAsset)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2079,
            ];
        }

        $this->shoppingAssetRepository->updateShoppingAsset(['id' => $data['ids']], ['status' => $data['status'], 'reason' => $data['note'] ?? null]);

        return [
            'success' => true,
        ];
    }

    public function getListShoppingAsset($filters)
    {
        $data = $this->shoppingAssetRepository->getListing($filters);
        foreach ($data as $shoppingAsset) {
            $shoppingAsset->shopping_asset_id = $shoppingAsset->id;
        }

        return $data->toArray();
    }
}
