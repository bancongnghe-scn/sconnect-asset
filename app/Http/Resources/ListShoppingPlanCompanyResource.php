<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ListShoppingPlanCompanyResource extends JsonResource
{
    public function toArray($request)
    {
        $data      = [];
        $users     = $this->additional['users'] ?? [];
        $now       = Carbon::now();
        foreach ($this->resource as $shoppingPlan) {
            $user   = $users[$shoppingPlan->created_by] ?? [];
            $data[] = [
                'id'              => $shoppingPlan->id,
                'name'            => $shoppingPlan->name,
                'status'          => $shoppingPlan->status,
                'start_time'      => date('d/m/Y', strtotime($shoppingPlan->start_time)),
                'end_time'        => date('d/m/Y', strtotime($shoppingPlan->end_time)),
                'created_at'      => date('d/m/Y', strtotime($shoppingPlan->created_at)),
                'user_name'       => $user['name'] ?? null,
                'status_register' => !$now->isAfter($shoppingPlan->end_time),
            ];
        }

        $shoppingPlanCompany = $this->resource->toArray();
        if (!empty($shoppingPlanCompany['total'])) {
            $shoppingPlanCompany['data'] = $data;

            return $shoppingPlanCompany;
        }

        return $data;
    }
}
