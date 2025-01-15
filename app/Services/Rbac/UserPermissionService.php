<?php

namespace App\Services\Rbac;

use App\Repositories\Rbac\UserPermissionRepository;
use App\Repositories\UserRepository;

class UserPermissionService
{
    public function __construct(
        protected UserPermissionRepository $userPermissionRepository,
        protected UserRepository $userRepository,
    ) {

    }

    public function createUsersPermission($userIds, $permission)
    {
        $users = $this->userRepository->getListing(['id' => $userIds]);
        foreach ($users as $user) {
            $user->givePermissionTo($permission);
        }
    }

    public function updateUsersPermission(array $userIds, $permission)
    {
        $usersPermission = $this->userPermissionRepository->getListing(['permission_id' => $permission->id]);
        $userIdsOld      = $usersPermission->pluck('model_id')->toArray();
        $newUserIds      = array_diff($userIds, $userIdsOld);
        $removeUserIds   = array_diff($userIdsOld, $userIds);

        if (!empty($newUserIds)) {
            $this->createUsersPermission($newUserIds, $permission);
        }

        if (!empty($removeUserIds)) {
            $this->userPermissionRepository->deleteByUserIdsAndPermissionId($removeUserIds, $permission->id);
        }
    }
}
