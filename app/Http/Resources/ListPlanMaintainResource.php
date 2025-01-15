<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListPlanMaintainResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [];
        foreach ($this->resource as $planMaintain) {
            $data[] = [
                'id'            => $planMaintain->id,
                'name'          => $planMaintain->name,
                'code'          => $planMaintain->code,
                'start_time'    => $planMaintain->start_time,
                'end_time'      => $planMaintain->end_time,
                'status'        => $planMaintain->status,
                'organizations' => $planMaintain->planMaintainOrganizations?->pluck('organization.name')->toArray(),
                'suppliers'     => $planMaintain->planMaintainSuppliers?->pluck('supplier.name')->toArray(),
            ];
        }

        $listPlanMaintain = $this->resource->toArray();
        if (!empty($listPlanMaintain['total'])) {
            $listPlanMaintain['data'] = $data;
        }

        return $listPlanMaintain;
    }
}
