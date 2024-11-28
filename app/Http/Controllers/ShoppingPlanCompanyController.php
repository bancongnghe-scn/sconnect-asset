<?php

namespace App\Http\Controllers;

use App\Services\ShoppingPlanCompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function sentNotificationRegister(Request $request)
    {
        $request->validate([
            'id'              => 'required|integer',
            'organizations'   => 'nullable|array',
            'organizations.*' => 'integer',
        ]);

        Auth::user()->canPer('shopping_plan_company.sent_notifi_register');

        try {
            $result = $this->planCompanyService->sentNotificationRegister($request->all());

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function sendAccountantApproval(string $id)
    {
        Auth::user()->canPer('shopping_plan_company.sent_account_approval');

        try {
            $result = $this->planCompanyService->sendAccountantApproval($id);

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {

            return response_error();
        }
    }

    public function sendManagerApproval(string $id)
    {
        Auth::user()->canPer('shopping_plan_company.sent_manager_approval');

        try {
            $result = $this->planCompanyService->sendManagerApproval($id);

            if ($result['success']) {
                return response_success();
            }

            return response_error($result['error_code']);
        } catch (\Throwable $exception) {

            return response_error();
        }
    }

    public function managerApproval(Request $request)
    {
        $request->validate([
            'id'    => 'required|integer',
            'type'  => 'required|string',
        ]);

        Auth::user()->canPer('shopping_plan_company.general_approval');

        try {
            $result = $this->planCompanyService->managerApproval($request->all());

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
        Auth::user()->canPer('shopping_plan_company.crud');

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

        Auth::user()->canPer('shopping_plan_company.crud');

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
