<?php

namespace App\Http\Resources;

use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ListAssetMaintainingResource extends JsonResource
{
    protected $userRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->userRepository      = new UserRepository();
    }

    public function toArray($request)
    {
        $userIds = $this->resource->pluck('user_id')->toArray();
        $users   = [];
        if (!empty($userIds)) {
            $users = $this->userRepository->getListing(['id' => $userIds])->keyBy('id')->toArray();
        }
        $data = [];
        foreach ($this->resource as $asset) {
            $data[] = [
                'id'                      => $asset->id,
                'name'                    => $asset->name,
                'code'                    => $asset->code,
                'asset_type_name'         => $asset->asset_type_name,
                'user'                    => $users[$asset->user_id] ?? [],
                'start_date_maintain'     => $asset->start_date_maintain,
                'complete_date_maintain'  => $asset->complete_date_maintain,
                'location'                => $asset->location,
                'status'                  => $asset->status,
            ];
        }

        $assetMaintaining         = $this->resource->toArray();
        $assetMaintaining['data'] = $data;

        return $assetMaintaining;
    }
}
