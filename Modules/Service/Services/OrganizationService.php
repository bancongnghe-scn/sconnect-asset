<?php

namespace Modules\Service\Services;

use Modules\Service\Repositories\OrganizationRepository;

class OrganizationService
{
    public function __construct(
        protected OrganizationRepository $organizationRepository,
    ) {

    }

    public function getListOrganization($filters = [])
    {
        $data = $this->organizationRepository->getInfoOrganizationByFilters($filters);

        return $data->toArray();
    }
}
