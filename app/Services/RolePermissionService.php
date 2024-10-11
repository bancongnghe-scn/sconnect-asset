<?php

namespace App\Services;

use App\Repositories\RolePermissionRepository;

class RolePermissionService
{
    public function __construct(
        protected RolePermissionRepository $rolePermissionRepository,
    ) {

    }

    public function insertRolePermissions(array $permissionIds, $roleId)
    {
        $dataInsertRolePermissions = [];
        foreach ($permissionIds as $permissionId) {
            $dataInsertRolePermissions[] = [
                'permission_id' => $permissionId,
                'role_id'       => $roleId,
            ];
        }

        return $this->rolePermissionRepository->insert($dataInsertRolePermissions);
    }

    public function insertRolesPermission(array $roleIds, $permissionId)
    {
        $dataInsertRolePermissions = [];
        foreach ($roleIds as $roleId) {
            $dataInsertRolePermissions[] = [
                'permission_id' => $permissionId,
                'role_id'       => $roleId,
            ];
        }

        return $this->rolePermissionRepository->insert($dataInsertRolePermissions);
    }

    public function updateRolePermissions(array $permissionIds, $roleId)
    {
        $rolePermissions  = $this->rolePermissionRepository->getListing(['role_id' => $roleId]);
        $permissionIdsOld = $rolePermissions->pluck('permission_id')->toArray();

        $newPermissionIds    = array_diff($permissionIds, $permissionIdsOld);
        $removePermissionIds = array_diff($permissionIdsOld, $permissionIds);

        if (!empty($newPermissionIds)) {
            $insertRolePermissions = $this->insertRolePermissions($newPermissionIds, $roleId);
            if (!$insertRolePermissions) {
                return false;
            }
        }

        if (!empty($removePermissionIds)) {
            $this->rolePermissionRepository->deleteRolePermissions($removePermissionIds, $roleId);
        }

        return true;
    }

    public function updateRolesPermission(array $roleIds, $permissionId)
    {
        $rolePermissions  = $this->rolePermissionRepository->getListing(['permission_id' => $permissionId]);
        $roleIdsOld       = $rolePermissions->pluck('role_id')->toArray();
        $newRoleIds       = array_diff($roleIds, $roleIdsOld);
        $removeRoleIds    = array_diff($roleIdsOld, $roleIds);

        if (!empty($newRoleIds)) {
            $insertRolePermissions = $this->insertRolesPermission($newRoleIds, $permissionId);
            if (!$insertRolePermissions) {
                return false;
            }
        }

        if (!empty($removeRoleIds)) {
            $this->rolePermissionRepository->deleteRolePermissions($permissionId, $removeRoleIds);
        }

        return true;
    }
}
