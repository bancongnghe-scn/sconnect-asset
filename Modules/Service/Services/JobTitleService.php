<?php

namespace Modules\Service\Services;

use Modules\Service\Repositories\JobTitleRepository;
use Modules\Service\Repositories\OrganizationRepository;

class JobTitleService
{
    public function __construct(
        protected JobTitleRepository $jobTitleRepository,
        protected OrganizationRepository $organizationRepository,
    ) {

    }

    public function getJobs($filters)
    {
        if (!empty($filters['org_id'])) {
            $organizationChild = $this->organizationRepository->departmentTreeCollection($filters['org_id']);
            if (!empty($organizationChild)) {
                $organizationIds   = $organizationChild->pluck('id')->toArray();
                $filters['org_id'] = $organizationIds;
            }
        }
        $results = $this->jobTitleRepository->getJobs($filters);

        return $results->toArray();
    }
}
