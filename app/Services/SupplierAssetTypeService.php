<?php

namespace App\Services;

use App\Repositories\SupplierAssetTypeRepository;
use App\Support\Constants\AppErrorCode;

class SupplierAssetTypeService
{
    public function __construct(
        protected SupplierAssetTypeRepository $supplierAssetTypeRepository,
    ) {

    }

    public function insertByAssetTypeIdsAndSupplierId($assetTypeIds, $supplierId)
    {
        $dataInsertSupplierAsseType = [];
        foreach ($assetTypeIds as $assetTypeId) {
            $dataInsertSupplierAsseType[] = [
                'supplier_id'   => $supplierId,
                'asset_type_id' => $assetTypeId,
            ];
        }

        return $this->supplierAssetTypeRepository->insert($dataInsertSupplierAsseType);
    }

    public function updateSupplierAssetType($assetTypeIds, $supplierId)
    {
        $supplierAssetType = $this->supplierAssetTypeRepository->getListing([
            'supplier_id' => $supplierId,
        ]);

        $assetTypeIdsCreated = $supplierAssetType->pluck('asset_type_id')->toArray();
        $assetTypeIdsNew     = array_diff($assetTypeIds, $assetTypeIdsCreated);
        $assetTypeIdsRemove  = array_diff($assetTypeIdsCreated, $assetTypeIds);

        if (!empty($assetTypeIdsRemove)) {
            $removeAssetTypeSupplier = $this->supplierAssetTypeRepository->removeAssetTypeOfSupplier($assetTypeIdsRemove, $supplierId);
            if (!$removeAssetTypeSupplier) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2019,
                ];
            }
        }

        if (!empty($assetTypeIdsNew)) {
            $dataCreate = [];
            foreach ($assetTypeIdsNew as $assetTypeId) {
                $dataCreate[] = [
                    'supplier_id'   => $supplierId,
                    'asset_type_id' => $assetTypeId,
                ];
            }

            $insertSupplierAssetType = $this->supplierAssetTypeRepository->insert($dataCreate);
            if (!$insertSupplierAssetType) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2020,
                ];
            }
        }

        return [
            'success' => true,
        ];
    }
}
