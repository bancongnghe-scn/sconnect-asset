<?php

namespace App\Services;

use App\Http\Resources\ListUserResource;
use App\Repositories\UserRepository;
use Modules\Service\Repositories\JobTitleRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
        protected JobTitleRepository $jobTitleRepository
    ) {

    }

    public function getListUser(array $filters)
    {
        $users = $this->userRepository->getListing($filters);
        if ($users->isEmpty()) {
            return [];
        }

        $jobTitleIds = $users->pluck('job_title_id')->toArray();
        $jobTitles = [];
        if (!empty($jobTitleIds)) {
            $jobTitles = $this->jobTitleRepository->getJobs(['id' => $jobTitleIds]);
        }

        return ListUserResource::make($users)->additional([
            'job_titles' => $jobTitles
        ])->resolve();
    }
}
