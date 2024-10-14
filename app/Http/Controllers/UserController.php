<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'name'  => 'nullable|string',
            'page'  => 'nullable|integer',
            'limit' => 'nullable|integer',
        ]);

        try {
            $result = $this->userService->getListUser($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
