<?php

namespace App\Http\Resources;

use App\Repositories\UserRepository;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Service\Repositories\OrganizationRepository;

class ShoppingAriseInfoResource extends JsonResource
{
    protected $userRepository;
    protected $organizationRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->organizationRepository = new OrganizationRepository();
        $this->userRepository         = new UserRepository();
    }

    public function toArray($request)
    {
        $organization = $this->organizationRepository->getInfoOrganizationByFilters([
            'id'    => $this->resource->organization_id,
            'first' => true,
        ]);
        $user                              = $this->userRepository->find($this->resource->created_by);
        $this->resource->user_name         = $user?->code . ' ' . $user?->name;
        $this->resource->organization_name = $organization?->name;

        return $this->resource->toArray();
    }
}
