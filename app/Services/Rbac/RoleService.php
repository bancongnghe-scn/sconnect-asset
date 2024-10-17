<?php

namespace App\Services\Rbac;

use App\Http\Resources\RoleInfoResource;
use App\Repositories\Rbac\RolePermissionRepository;
use App\Repositories\Rbac\RoleRepository;
use App\Repositories\Rbac\RoleUserRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function __construct(
        protected RoleRepository $roleRepository,
        protected RoleUserRepository $roleUserRepository,
        protected RolePermissionRepository $rolePermissionRepository,
    ) {

    }

    public function createRole($data)
    {
        $role = $this->roleRepository->getFirst(['name' => $data['name']]);
        if (!empty($role)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2037,
            ];
        }
        DB::beginTransaction();
        try {
            $role = Role::create($data);

            $userIds = $data['user_ids'] ?? [];
            if (!empty($userIds)) {
                resolve(RoleUserService::class)->createRoleUsers($userIds, $role);
            }

            $permissionIds = $data['permission_ids'] ?? [];
            if (!empty($permissionIds)) {
                resolve(RolePermissionService::class)->insertRolePermissions($permissionIds, $role);
            }
            DB::commit();
        } catch (\Throwable $exception) {
            dd($exception);
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

    public function getListRole($filters)
    {
        $data = $this->roleRepository->getListing($filters, ['id', 'name', 'description']);

        return $data->toArray();
    }

    public function deleteRoleById($id)
    {
        $role = $this->roleRepository->find($id);
        if (empty($role)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2040,
            ];
        }

        DB::beginTransaction();
        try {
            if (!$role->delete()) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2039,
                ];
            }

            $this->roleUserRepository->deleteByRoleId($id);
            $this->rolePermissionRepository->deleteByRoleId($id);

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

    public function updateRole($data, $id)
    {
        $role = Role::findById($id);

        $data['updated_by'] = Auth::id();
        $role->fill($data);
        DB::beginTransaction();
        try {
            if (!$role->save()) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2043,
                ];
            }

            $userIds = $data['user_ids'] ?? [];
            if (!empty($userIds)) {
                resolve(RoleUserService::class)->updateRoleUsers($userIds, $role);
            }

            $permissionIds = $data['permission_ids'] ?? [];
            if (!empty($permissionIds)) {
                $updateRolePermissions = resolve(RolePermissionService::class)->updateRolePermissions($permissionIds, $id);
                if (!$updateRolePermissions) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2042,
                    ];
                }
            }

            DB::commit();

            return [
                'success' => true,
            ];
        } catch (\Throwable $exception) {
            DB::rollBack();

            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_1000,
            ];
        }

    }

    public function findRole($id)
    {
        $role = $this->roleRepository->getFirst(['id' => $id], with: ['roleUsers', 'rolePermissions']);

        return RoleInfoResource::make($role)->resolve();
    }
}
