<?php

namespace App\Services;

use App\Models\PlanMaintainSupplier;
use App\Repositories\PlanMaintainSupplierRepository;
use App\Support\Constants\AppErrorCode;

class PlanMaintainSupplierService
{
    public function __construct(
        protected PlanMaintainSupplierRepository $planMaintainSupplierRepository,
    ) {

    }

    public function updatePlanMaintainSupplier($supplierNewIds, $planId)
    {
        $supplierOldIds = PlanMaintainSupplier::where('plan_maintain_id', $planId)->pluck('supplier_id')->toArray();
        $supplierAddIds = array_diff($supplierNewIds, $supplierOldIds);
        if (!empty($supplierAddIds)) {
            $dataInsert = [];
            foreach ($supplierAddIds as $supplierId) {
                $dataInsert[] = [
                    'plan_maintain_id' => $planId,
                    'supplier_id'      => $supplierId,
                ];
            }
            if (!empty($dataInsert)) {
                $insert = $this->planMaintainSupplierRepository->insert($dataInsert);
                if (!$insert) {
                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2096,
                    ];
                }
            }
        }
        $supplierRemoveIds = array_diff($supplierOldIds, $supplierNewIds);
        if (!empty($supplierRemoveIds)) {
            PlanMaintainSupplier::where('plan_maintain_id', $planId)->whereIn('supplier_id', $supplierRemoveIds)->delete();
        }

        return [
            'success' => true,
        ];
    }

    public function insertPlanMaintainSupplier(array $supplierIds, $planId)
    {
        $dataInsert = [];
        foreach ($supplierIds as $supplierId) {
            $dataInsert[] = [
                'plan_maintain_id' => $planId,
                'supplier_id'      => $supplierId,
            ];
        }
        if (!empty($dataInsert)) {
            $insert = $this->planMaintainSupplierRepository->insert($dataInsert);
            if (!$insert) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2097,
                ];
            }
        }

        return [
            'success' => true,
        ];
    }
}
