<?php

namespace App\Services;

use App\Http\Resources\ListMenuResource;
use App\Http\Resources\ListMenuUserResource;
use App\Http\Resources\MenuInfoResource;
use App\Repositories\MenuRepository;
use App\Repositories\MenuRoleRepository;
use App\Repositories\Rbac\RoleRepository;
use App\Repositories\Rbac\RoleUserRepository;
use App\Support\AppErrorCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MenuService
{
    public function __construct(
        protected MenuRepository $menuRepository,
        protected RoleUserRepository $roleUserRepository,
        protected MenuRoleRepository $menuRoleRepository,
        protected RoleRepository $roleRepository,
    ) {

    }

    public function getMenuUser($userId = null)
    {
        if (is_null($userId)) {
            $userId = Auth::id();
        }
        $cacheKey = config('cache_keys.menu_key').$userId;

        return Cache::tags(config('cache_tags.menu_tag'))->remember($cacheKey, now()->addHours(2), function () use ($userId) {
            $rolesUser = $this->roleUserRepository->getListing(['user_id' => $userId]);
            if ($rolesUser->isEmpty()) {
                return [];
            }

            $roleIds   = $rolesUser->pluck('role_id')->toArray();
            $roleMenus = $this->menuRoleRepository->getListing(['role_id' => $roleIds]);
            if ($roleMenus->isEmpty()) {
                return [];
            }

            $menuIds = $roleMenus->pluck('menu_id')->toArray();
            $menus   = $this->menuRepository->getListing(['id' => $menuIds]);

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
                        'error_code' => AppErrorCode::CODE_2055,
                    ];
                }
            }

            Cache::tags('menu_tag')->clear();
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
            Cache::tags('menu_tag')->clear();
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

            $roleIds = $data['role_ids'] ?? [];
            if (!empty($roleIds)) {
                $updateMenuRoles = resolve(MenuRoleService::class)->updateMenuRoles($roleIds, $menu->id);

                if (!$updateMenuRoles) {
                    return [
                        'success'    => false,
                        'error_code' => AppErrorCode::CODE_2055,
                    ];
                }
            }

            Cache::tags('menu_tag')->clear();
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

    public function getListMenuParent()
    {
        $menus = $this->menuRepository->getListMenuParent();

        return $menus->toArray();
    }
}
