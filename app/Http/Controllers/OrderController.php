<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        //        Auth::user()->canPer('order.view');

        try {
            $result = $this->orderService->getListOrder($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function createOrder(CreateOrderRequest $request)
    {
        //        Auth::user()->canPer('order.create');

        try {
            $result = $this->orderService->createOrder($request->validated());

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function updateOrder(UpdateOrderRequest $request)
    {
        //        Auth::user()->canPer('order.update');

        try {
            $result = $this->orderService->updateOrder($request->validated());

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function deleteOrder(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'integer',
            'reason' => 'required|string',
        ]);

        //        Auth::user()->canPer('order.delete');

        try {
            $result = $this->orderService->deleteOrder($request->input('ids'), $request->input('reason'));

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function findOrder(string $id)
    {
        //        Auth::user()->canPer('order.view');

        try {
            $result = $this->orderService->findOrder($id);

            if ($result['success']) {
                return response_success($result['data']);
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
