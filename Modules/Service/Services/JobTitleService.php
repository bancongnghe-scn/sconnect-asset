<?php

namespace Modules\Service\Services;

use Illuminate\Support\Facades\Auth;
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

    public function getListJobOfManager()
    {
        $organization = $this->organizationRepository->getListing([
            'manager_id' => Auth::id(),
            'first'      => true,
        ]);
        if (empty($organization)) {
            return [];
        }

        return $this->getJobs(['org_id' => $organization->id]);
    }
}
