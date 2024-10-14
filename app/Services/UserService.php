<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {

    }

    public function getListUser(array $filters)
    {
        $users = $this->userRepository->getListing($filters);

        return $users->toArray();
    }
}
