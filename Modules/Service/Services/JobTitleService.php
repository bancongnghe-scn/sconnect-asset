<?php

namespace Modules\Service\Services;

use Modules\Service\Repositories\JobTitleRepository;

class JobTitleService
{
    public function __construct(
        protected JobTitleRepository $jobTitleRepository,
    ) {

    }

    public function getJobs($filters)
    {
        $results = $this->jobTitleRepository->getJobs($filters);

        return $results->toArray();
    }
}
