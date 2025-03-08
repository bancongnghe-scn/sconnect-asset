<?php

namespace App\Http\Controllers\Rbac;

use App\Http\Controllers\Controller;
use App\Services\Rbac\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService,
    ) {

    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'user_ids'         => 'nullable|array',
            'user_ids.*'       => 'integer',
            'permission_ids'   => 'nullable|array',
            'permission_ids.*' => 'integer',
        ]);

        Auth::user()->canPer('role.create');

        try {
            $result = $this->roleService->createRole($request->all());

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

        Auth::user()->canPer('role.view');
        try {
            $result = $this->roleService->getListRole($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function destroy(string $id)
    {
        Auth::user()->canPer('role.delete');
        try {
            $result = $this->roleService->deleteRoleById($id);
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
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'user_ids'         => 'nullable|array',
            'user_ids.*'       => 'integer',
            'permission_ids'   => 'nullable|array',
            'permission_ids.*' => 'integer',
        ]);

        Auth::user()->canPer('role.create');
        try {
            $result = $this->roleService->updateRole($request->all(), $id);

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
        Auth::user()->canPer('role.view');
        try {
            $result = $this->roleService->findRole($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
