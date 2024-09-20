<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\SupplierAsseTypeRepository;

class SupplierAsseTypeService
{
    public function __construct(
        protected SupplierAsseTypeRepository $supplierAsseTypeRepository
    )
    {

    }

    public function insertByAssetTypeIdsAndSupplierId($assetTypeIds, $supplierId)
    {
        $dataInsertSupplierAsseType = [];
        foreach ($assetTypeIds as $assetTypeId) {
            $dataInsertSupplierAsseType[] = [
                'supplier_id' => $supplierId,
                'asset_type_id' => $assetTypeId,
                'created_by' => User::USER_ID_ADMIN
            ];
        }

        return $this->supplierAsseTypeRepository->insert($dataInsertSupplierAsseType);
    }
}
