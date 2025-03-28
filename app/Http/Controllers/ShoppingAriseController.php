<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShoppingAriseRequest;
use App\Services\ShoppingAriseService;
use Illuminate\Support\Facades\Auth;

class ShoppingAriseController extends Controller
{
    public function __construct(
        protected ShoppingAriseService $shoppingAriseService,
    ) {
    }

    public function createShoppingArise(CreateShoppingAriseRequest $request)
    {
        //        Auth::user()->canPer('shopping_arise.create');
        try {
            $result = $this->shoppingAriseService->createShoppingArise($request->validated());
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
