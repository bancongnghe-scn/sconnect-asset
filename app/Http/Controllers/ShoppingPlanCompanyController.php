<?php

namespace App\Http\Controllers;

use App\Services\ShoppingPlanCompanyService;
use Illuminate\Http\Request;

class ShoppingPlanCompanyController extends Controller
{
    public function __construct(
        protected ShoppingPlanCompanyService $planCompanyService,
    ) {

    }

    public function findShoppingPlanCompany(string $id)
    {

        try {
            $result = $this->planCompanyService->findShoppingPlanCompany($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result['data']);
        } catch (\Throwable $exception) {

            return response_error();
        }
    }

    public function sentNotificationRegister(string $id)
    {
        try {
            $result = $this->planCompanyService->sentNotificationRegister($id);

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function deleteShoppingPlanCompany(string $id)
    {
        try {
            $result = $this->planCompanyService->deleteShoppingPlanCompany($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer',
            'type'  => 'required|integer',
        ]);
        try {
            $result = $this->planCompanyService->deleteShoppingPlanCompanyMultiple($request->get('ids'), $request->integer('type'));

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {

            return response_error();
        }
    }
}
