<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ) {
    }

    public function getListOrder(Request $request)
    {
        $request->validate([
            'status' => 'nullable|integer',
            'page'   => 'nullable|integer',
            'limit'  => 'nullable|integer',
        ]);

        try {
            $result = $this->orderService->getListOrder($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
