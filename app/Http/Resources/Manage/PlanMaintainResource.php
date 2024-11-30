<?php

namespace App\Http\Resources\Manage;

use App\Models\PlanMaintain;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanMaintainResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource->map(function ($item) {
            return [
                'id'                        => $item->id,
                'code'                      => $item->code,
                'name'                      => $item->name,
                'asset_quantity'            => $item->asset_quantity,
                'created_at'                => date('d/m/y', strtotime($item->created_at)),
                'total_price_liquidation'   => $item?->planMaintainAsset?->sum('price'),
                'status'                    => PlanMaintain::STATUS_NAME[$item?->status],
            ];
        });

        $result = $this->resource->toArray();
        if (isset($result['total'])) {
            $result['data'] = $data->toArray();

            return $result;
        }

        return $data;
    }
}
