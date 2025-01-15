<?php

namespace App\Services\Rbac;

use App\Repositories\Rbac\RoleUserRepository;
use App\Repositories\UserRepository;

class RoleUserService
{
    public function __construct(
        protected RoleUserRepository $roleUserRepository,
        protected UserRepository $userRepository,
    ) {

    }

    public function createRoleUsers(array $userIds, $role)
    {
        $users = $this->userRepository->getListing(['id' => $userIds]);
        foreach ($users as $user) {
            $user->assignRole($role);
        }
    }

    public function updateRoleUsers(array $userIds, $role)
    {
        $roleUsers     = $this->roleUserRepository->getListing(['role_id' => $role->id]);
        $userIdsOld    = $roleUsers->pluck('model_id')->toArray();
        $newUserIds    = array_diff($userIds, $userIdsOld);
        $removeUserIds = array_diff($userIdsOld, $userIds);

        if (!empty($newUserIds)) {
            $this->createRoleUsers($newUserIds, $role);
        }

        if (!empty($removeUserIds)) {
            $this->roleUserRepository->deleteRoleUsers($removeUserIds, $role->id);
        }
    }
}
