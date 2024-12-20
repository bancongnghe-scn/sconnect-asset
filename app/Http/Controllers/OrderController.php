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
            'code_name'  => 'nullable|string',
            'created_at' => 'nullable|date|date_format:Y-m-d',
            'status'     => 'nullable|array',
            'status.*'   => 'integer',
            'page'       => 'nullable|integer',
            'limit'      => 'nullable|integer',
        ]);

        try {
            $result = $this->orderService->getListOrder($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function createOrder(Request $request)
    {

    }
}
