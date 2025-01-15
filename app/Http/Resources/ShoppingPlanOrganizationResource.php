<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Service\Repositories\OrganizationRepository;

class ShoppingPlanOrganizationResource extends JsonResource
{
    protected $organizationRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->organizationRepository = new OrganizationRepository();
    }

    public function toArray($request)
    {
        $organizationId = $this->resource->organization_id;
        $organization   = $this->organizationRepository->find($organizationId);

        return [
            'id'                => $this->resource->id,
            'name'              => $this->resource->name,
            'status'            => $this->resource->status,
            'start_time'        => $this->resource->start_time,
            'end_time'          => $this->resource->end_time,
            'organization_name' => $organization?->name,
            'organization_id'   => $organization?->id,
            'status_company'    => $this->resource->status_company,
        ];
    }
}
