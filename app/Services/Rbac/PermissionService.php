<?php

namespace App\Services\Rbac;

use App\Http\Resources\PermissionInfoResource;
use App\Repositories\Rbac\PermissionRepository;
use App\Repositories\Rbac\RolePermissionRepository;
use App\Repositories\Rbac\UserPermissionRepository;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function __construct(
        protected PermissionRepository $permissionRepository,
        protected RolePermissionRepository $rolePermissionRepository,
        protected UserPermissionRepository $userPermissionRepository,
    ) {

    }

    public function createPermission($data)
    {
        $permission = $this->permissionRepository->getFirst(['name' => $data['name']]);
        if (!empty($permission)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2044,
            ];
        }

        $data['created_by'] = Auth::id();
        DB::beginTransaction();
        try {
            $permission = Permission::create($data);

            $userIds = $data['user_ids'] ?? [];
            if (!empty($userIds)) {
                resolve(UserPermissionService::class)->createUsersPermission($userIds, $permission);
            }

            $roleIds = $data['role_ids'] ?? [];
            if (!empty($roleIds)) {
                $insertRolePermissions = resolve(RolePermissionService::class)->insertRolesPermission($roleIds, $permission->id);
                if (!$insertRolePermissions) {
                    DB::rollBack();

                    return  [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2050,
                    ];
                }
            }

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function getListPermission($filters)
    {
        $data = $this->permissionRepository->getListing($filters, ['id', 'name', 'description']);

        return $data->toArray();
    }

    public function deletePermissionById($id)
    {
        $permission = $this->permissionRepository->find($id);
        if (empty($permission)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2047,
            ];
        }

        DB::beginTransaction();
        try {
            if (!$permission->delete()) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2046,
                ];
            }

            $this->userPermissionRepository->deleteByPermissionId($id);
            $this->rolePermissionRepository->deleteByPermissionId($id);

            DB::commit();
        } catch (\Throwable $throwable) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return [
            'success' => true,
        ];
    }

    public function updatePermission($data, $id)
    {
        DB::beginTransaction();
        try {
            Permission::where('id', $id)->update([
                'name'        => $data['name'],
                'description' => $data['description'],
            ]);

            $userIds    = $data['user_ids'] ?? [];
            $permission = Permission::findById($id);
            resolve(UserPermissionService::class)->updateUsersPermission($userIds, $permission);

            $roleIds               = $data['role_ids'] ?? [];
            $updateRolesPermission = resolve(RolePermissionService::class)->updateRolesPermission($roleIds, $id);
            if (!$updateRolesPermission) {
                DB::rollBack();

                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2050,
                ];
            }

            DB::commit();

        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

        return ['success' => true];
    }

    public function findPermission($id)
    {
        $permission = $this->permissionRepository->getFirst(['id' => $id], with: ['usersPermission', 'rolesPermission']);

        return PermissionInfoResource::make($permission)->resolve();
    }
}
