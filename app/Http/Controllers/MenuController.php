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

    public function index(Request $request)
    {
        $request->validate([]);

        try {
            $result = [];

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = [];
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function store(Request $request)
    {
        $request->validate();

        try {
            $result = [];

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
        $request->validate([]);

        try {
            $result = [];

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
            $result = [];

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
