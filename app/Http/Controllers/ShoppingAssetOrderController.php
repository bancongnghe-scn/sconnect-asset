<?php

namespace App\Http\Controllers;

use App\Services\ShoppingAssetOrderService;
use Illuminate\Http\Request;

class ShoppingAssetOrderController extends Controller
{
    public function __construct(
        protected ShoppingAssetOrderService $shoppingAssetOrderService,
    ) {

    }

    public function getListShoppingAssetOrder(Request $request)
    {
        $request->validate([
            'order_id'   => 'required|array',
            'order_id.*' => 'integer',
        ]);

        try {
            $result = $this->shoppingAssetOrderService->getListShoppingAssetOrder($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
