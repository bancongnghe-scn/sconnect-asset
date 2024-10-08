<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService,
    ) {

    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $result = $this->roleService->createRole($request->all());

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
            'name'    => 'nullable|string',
            'page'    => 'nullable|integer',
            'limit'   => 'nullable|integer|max:200',
        ]);

        try {
            $result = $this->roleService->getListRole($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->roleService->deleteRoleById($id);
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
            'name'        => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $result = $this->roleService->updateRole($request->all(), $id);

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
            $result = $this->roleService->findRole($id);

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
