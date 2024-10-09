<?php

namespace App\Services;

use App\Repositories\SupplierAssetIndustryRepository;
use App\Support\AppErrorCode;

class SupplierAssetIndustryService
{
    public function __construct(
        protected SupplierAssetIndustryRepository $supplierAssetIndustryRepository,
    ) {

    }

    public function insertByIndustryIdsAndSupplierId($industryIds, $supplierId)
    {
        $dataInsertSupplierAssetIndustries = [];
        foreach ($industryIds as $industryId) {
            $dataInsertSupplierAssetIndustries[] = [
                'supplier_id'   => $supplierId,
                'industries_id' => $industryId,
            ];
        }

        return $this->supplierAssetIndustryRepository->insert($dataInsertSupplierAssetIndustries);
    }

    public function updateSupplierAssetIndustry($industriesIds, $supplierId)
    {
        $supplierAssetIndustry = $this->supplierAssetIndustryRepository->getListing([
            'supplier_id' => $supplierId,
        ]);

        $industriesIdsCreated = $supplierAssetIndustry->pluck('industries_id')->toArray();
        $industriesIdsNew     = array_diff($industriesIds, $industriesIdsCreated);
        $industriesIdsRemove  = array_diff($industriesIdsCreated, $industriesIds);

        if (!empty($industriesIdsRemove)) {
            $removeAssetTypeIndustry = $this->supplierAssetIndustryRepository->removeIndustriesOfSupplier($industriesIdsRemove, $supplierId);
            if (!$removeAssetTypeIndustry) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2021,
                ];
            }
        }

        if (!empty($industriesIdsNew)) {
            $dataCreate = [];
            foreach ($industriesIdsNew as $industryId) {
                $dataCreate[] = [
                    'supplier_id'   => $supplierId,
                    'industries_id' => $industryId,
                ];
            }

            $insertSupplierAssetIndustry = $this->supplierAssetIndustryRepository->insert($dataCreate);
            if (!$insertSupplierAssetIndustry) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2022,
                ];
            }
        }

        return [
            'success' => true,
        ];
    }
}
