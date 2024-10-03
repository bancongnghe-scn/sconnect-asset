<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {

    }

    public function checkUserExistLogin($userLogin)
    {
        $user = $this->userRepository->find($userLogin['id']);
        if (empty($user)) {
            try {
                $this->userRepository->create([
                    'id'    => $userLogin['id'],
                    'name'  => $userLogin['name'],
                    'email' => $userLogin['email'],
                ]);
            } catch (\Exception $e) {
                dd($e->getMessage());

                return false;
            }
        }

        return true;
    }
}
