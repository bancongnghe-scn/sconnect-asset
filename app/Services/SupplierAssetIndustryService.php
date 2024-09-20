<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\SupplierAssetIndustryRepository;

class SupplierAssetIndustryService
{
    public function __construct(
        protected SupplierAssetIndustryRepository $supplierAssetIndustryRepository
    )
    {

    }

    public function insertByIndustryIdsAndSupplierId($industryIds, $supplierId)
    {
        $dataInsertSupplierAssetIndustries = [];
        foreach ($industryIds as $industryId) {
            $dataInsertSupplierAssetIndustries[] = [
                'supplier_id' => $supplierId,
                'industries_id' => $industryId,
                'created_by' => User::USER_ID_ADMIN
            ];
        }

        return $this->supplierAssetIndustryRepository->insert($dataInsertSupplierAssetIndustries);
    }
}
