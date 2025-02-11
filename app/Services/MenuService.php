<?php

namespace App\Services;

use App\Http\Resources\ListMenuResource;
use App\Http\Resources\ListMenuUserResource;
use App\Http\Resources\MenuInfoResource;
use App\Repositories\Rbac\MenuRepository;
use App\Repositories\Rbac\MenuRoleRepository;
use App\Repositories\Rbac\MenuUserRepository;
use App\Repositories\Rbac\RoleRepository;
use App\Repositories\Rbac\RoleUserRepository;
use App\Services\Rbac\MenuUserService;
use App\Support\Constants\AppErrorCode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MenuService
{
    public function __construct(
        protected MenuRepository $menuRepository,
        protected RoleUserRepository $roleUserRepository,
        protected MenuRoleRepository $menuRoleRepository,
        protected RoleRepository $roleRepository,
        protected MenuUserRepository $menuUserRepository,
    ) {

    }

    public function getMenuUser($userId)
    {
        if (is_null($userId)) {
            return [];
        }

        $cacheKey = config('cache_keys.keys.menu_key').$userId;

        return Cache::tags(config('cache_keys.tags.menu_tag'))->remember($cacheKey, now()->addHours(2), function () use ($userId) {
            $rolesUser = $this->roleUserRepository->getListing(['user_id' => $userId]);
            $roleIds   = $rolesUser->pluck('role_id')->toArray();
            $menuIds   = [];
            if (!empty($roleIds)) {
                $roleMenus = $this->menuRoleRepository->getListing(['role_id' => $roleIds]);
                $menuIds   = $roleMenus->pluck('menu_id')->toArray();
            }

            $menuUsers = $this->menuUserRepository->getListing(['user_id' => $userId]);
            $menuIds   = array_merge($menuIds, $menuUsers->pluck('menu_id')->toArray());
            if (empty($menuIds)) {
                return [];
            }
            $menus = $this->menuRepository->getListing(['id' => $menuIds]);

            return ListMenuUserResource::make($menus)->resolve();
        });
    }

    public function getListMenu(array $filters)
    {
        $menus = $this->menuRepository->getListMenuByFilters($filters);
        if ($menus->isEmpty()) {
            return [];
        }

        $roleIds = $menus->implode('role_ids', ',');
        $roleIds = array_unique(explode(',', $roleIds));
        $roles   = $this->roleRepository->getListing(['id' => $roleIds]);

        return ListMenuResource::make($menus)
            ->additional(['roles' => $roles])
            ->resolve();

    }

    public function createMenu($data)
    {
        $menu = $this->menuRepository->getFirst(['name' => $data['name']]);
        if (!is_null($menu)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2051,
            ];
        }
        DB::beginTransaction();
        try {
            $menu    = $this->menuRepository->create($data);
            $roleIds = $data['role_ids'] ?? [];
            if (!empty($roleIds)) {
                $insertMenuRoles = resolve(MenuRoleService::class)->insertMenuRoles($roleIds, $menu->id);
                if (!$insertMenuRoles) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2051,
                    ];
                }
            }

            $userIds = $data['user_ids'] ?? [];
            if (!empty($userIds)) {
                $insertMenuUsers = resolve(MenuUserService::class)->insertMenuUsers($userIds, $menu->id);
                if (!$insertMenuUsers) {
                    DB::rollBack();

                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2051,
                    ];
                }
            }

            Cache::tags(config('cache_keys.tags.menu_tag'))->clear();
            DB::commit();
        } catch (\Throwable $exception) {
            report($exception);
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

    public function deleteMenuById($id)
    {
        DB::beginTransaction();
        try {
            $deleteMenu = $this->menuRepository->delete($id);
            if (!$deleteMenu) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2052,
                ];
            }

            $this->menuRoleRepository->deleteByMenuId($id);
            $this->menuUserRepository->deleteByMenuId($id);
            Cache::tags(config('cache_keys.tags.menu_tag'))->clear();
            DB::commit();
        } catch (\Throwable $exception) {
            report($exception);
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

    public function findMenu($id)
    {
        $menu = $this->menuRepository->getFirst(['id' => $id]);

        return MenuInfoResource::make($menu)->resolve();
    }

    public function updateMenu($data, $id)
    {
        $menu = $this->menuRepository->find($id);
        if (is_null($menu)) {
            return [
                'success'    => false,
                'error_code' => AppErrorCode::CODE_2053,
            ];
        }

        DB::beginTransaction();
        try {
            $menu->fill($data);
            if (!$menu->save()) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2054,
                ];
            }

            $roleIds         = $data['role_ids'] ?? [];
            $updateMenuRoles = resolve(MenuRoleService::class)->updateMenuRoles($roleIds, $menu->id);

            if (!$updateMenuRoles) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2055,
                ];
            }

            $userIds         = $data['user_ids'] ?? [];
            $updateMenuUsers = resolve(MenuUserService::class)->updateMenuUsers($userIds, $menu->id);

            if (!$updateMenuUsers) {
                return [
                    'success'    => false,
                    'error_code' => AppErrorCode::CODE_2055,
                ];
            }

            Cache::tags(config('cache_keys.tags.menu_tag'))->clear();
            DB::commit();
        } catch (\Throwable $exception) {
            report($exception);
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

    public function getListMenuParent()
    {
        $menus = $this->menuRepository->getListMenuParent();

        return $menus->toArray();
    }
}
