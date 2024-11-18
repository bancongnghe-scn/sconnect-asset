<?php

namespace App\Http\Resources\Manage;

use App\Models\PlanMaintain;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanMaintainResource extends JsonResource
{
    public function toArray($request)
    {
        $data = $this->resource->map(function ($data) {
            return [
                'id'                        => $data->id,
                'code'                      => $data->code,
                'name'                      => $data->name,
                'asset_quantity'            => $data->asset_quantity,
                'created_at'                => date('d/m/y', strtotime($data->created_at)),
                'total_price_liquidation'   => $data->planMaintainAsset->sum('price'),
                'status'                    => PlanMaintain::STATUS_NAME[$data->status],
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
