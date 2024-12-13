<?php

namespace App\Http\Controllers;

use App\Http\Requests\SentInfoShoppingAssetRequest;
use App\Services\ShoppingAssetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingAssetController extends Controller
{
    public function __construct(
        protected ShoppingAssetService $shoppingAssetService,
    ) {
    }

    public function sentInfoShoppingAsset(SentInfoShoppingAssetRequest $request)
    {
        Auth::user()->canPer('shopping_plan_company.synthetic_shopping');

        try {
            $result = $this->shoppingAssetService->sentInfoShoppingAsset($request->validated());
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_success();
        }
    }

    public function approvalShoppingAsset(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'required|integer',
            'status' => 'required|integer',
            'note'   => 'nullable|string',
        ]);

        try {
            $result = $this->shoppingAssetService->approvalShoppingAsset($request->all());
            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
