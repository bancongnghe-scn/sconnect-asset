<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Services\Rbac\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService,
    ) {

    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'role_ids'    => 'nullable|array',
            'role_ids.*'  => 'integer',
            'user_ids'    => 'nullable|array',
            'user_ids.*'  => 'integer',
        ]);

        try {
            $result = $this->permissionService->createPermission($request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function index(Request $request)
    {
        $request->validate([
            'name'    => 'nullable|string|max:255',
            'page'    => 'nullable|integer',
            'limit'   => 'nullable|integer|max:200',
        ]);

        try {
            $result = $this->permissionService->getListPermission($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->permissionService->deletePermissionById($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'role_ids'    => 'nullable|array',
            'role_ids.*'  => 'integer',
            'user_ids'    => 'nullable|array',
            'user_ids.*'  => 'integer',
        ]);

        try {
            $result = $this->permissionService->updatePermission($request->all(), $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function show(string $id)
    {
        try {
            $result = $this->permissionService->findPermission($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
