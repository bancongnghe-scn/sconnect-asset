<?php

namespace App\Http\Resources;

use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Service\Repositories\OrganizationRepository;

class ListShoppingAriseResource extends JsonResource
{
    private $userRepository;
    private $organizationRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->userRepository         = new UserRepository();
        $this->organizationRepository = new OrganizationRepository();
    }

    public function toArray($request)
    {
        $userIds = $this->resource->pluck('created_by')->toArray();
        $users   = [];
        if (!empty($userIds)) {
            $users = $this->userRepository->getListing(['id' => $userIds])->keyBy('id')->toArray();
        }

        $organizationIds = $this->resource->pluck('organization_id')->toArray();
        $organizations   = [];
        if (!empty($organizationIds)) {
            $organizations = $this->organizationRepository->getInfoOrganizationByFilters(['id' => $organizationIds])->keyBy('id')->toArray();
        }

        $data = [];
        foreach ($this->resource as $shoppingArise) {
            $info = [
                'organization_name' => $organizations[$shoppingArise->organization_id]['name'] ?? null,
                'user'              => $users[$shoppingArise->created_by] ?? [],
            ];

            $data[] = array_merge($shoppingArise->toArray(), $info);
        }

        $listShoppingArise = $this->resource->toArray();
        if (!empty($listShoppingArise['total'])) {
            $listShoppingArise['data'] = $data;

            return $listShoppingArise;
        }

        return $data;
    }
}
