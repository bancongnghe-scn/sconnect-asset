<?php

namespace App\Http\Controllers;

use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(
        protected MenuService $menuService,
    ) {
    }

    public function getMenuUserLogin()
    {
        try {
            $result = $this->menuService->getMenuUser();

            return response_success($result);
        } catch (\Throwable $exception) {
            dd($exception);

            return response_error();
        }
    }

    public function getMenuParent()
    {
        try {
            $result = $this->menuService->getListMenuParent();

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string',
            'icon'              => 'required|string',
            'url'               => 'nullable|string',
            'order'             => 'required|integer',
            'parent_id'         => 'nullable|integer',
            'description'       => 'nullable|string',
            'role_ids'          => 'nullable|array',
            'role_ids.*'        => 'integer',
        ], [], ['icon' => __('attributes.menu.icon')]);

        try {
            $result = $this->menuService->createMenu($request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function index(Request $request)
    {
        $request->validate([
            'name'        => 'nullable|string',
            'role_ids'    => 'nullable|array',
            'role_ids.*'  => 'integer',
            'page'        => 'nullable|integer',
            'limit'       => 'nullable|integer|max:200',
        ]);

        try {
            $result = $this->menuService->getListMenu($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->menuService->deleteMenuById($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'             => 'required|string',
            'icon'             => 'required|string',
            'url'              => 'nullable|string',
            'order'            => 'required|integer',
            'parent_id'        => 'nullable|integer',
            'description'      => 'nullable|string',
            'role_ids'         => 'nullable|array',
            'role_ids.*'       => 'integer',
        ], [], ['icon' => __('attributes.menu.icon')]);

        try {
            $result = $this->menuService->updateMenu($request->all(), $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function show(string $id)
    {
        try {
            $result = $this->menuService->findMenu($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
