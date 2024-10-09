<?php

namespace App\Services;

use App\Repositories\UserPermissionRepository;

class UserPermissionService
{
    public function __construct(
        protected UserPermissionRepository $userPermissionRepository,
    ) {

    }

    public function insertUsersPermission($userIds, $permissionId)
    {
        $dataInsertUsersPermission = [];
        foreach ($userIds as $userId) {
            $dataInsertUsersPermission[] = [
                'user_id'       => $userId,
                'permission_id' => $permissionId,
            ];
        }

        return $this->userPermissionRepository->insert($dataInsertUsersPermission);
    }
}
