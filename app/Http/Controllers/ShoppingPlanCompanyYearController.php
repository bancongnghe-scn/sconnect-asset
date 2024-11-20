<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShoppingPlanCompanyYearRequest;
use App\Models\ShoppingPlanCompany;
use App\Services\ShoppingPlanCompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanCompanyYearController extends Controller
{
    public function __construct(
        protected ShoppingPlanCompanyService $planCompanyService,
    ) {

    }

    public function index()
    {
        $user = Auth::user();

        $user->canAnyPer(['shopping_plan_organization.view', 'shopping_plan_company.view']);

        if ($user->hasAnyRole(['accounting_director', 'hr_director'])) {
            return view('assets.shopping-plan-company.year.listPersonnelDirector');
        }
        if ($user->hasRole('manager_organization')) {
            return view('assets.shopping_plan_organization.year.list');
        } else {
            return view('assets.shopping-plan-company.year.listShoppingPlanCompany');
        }
    }

    public function getListShoppingPlanCompanyYear(Request $request)
    {
        $request->validate([
            'time'     => 'nullable|integer',
            'status'   => 'nullable|array',
            'status.*' => 'integer',
        ]);

        Auth::user()->canPer(['shopping_plan_company.view']);

        try {
            $filters         = $request->all();
            $filters['type'] = ShoppingPlanCompany::TYPE_YEAR;
            $result          = $this->planCompanyService->getListShoppingPlanCompany($filters);

            return response_success($result['data'] ?? [], extraData: $result['extra_data'] ?? []);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function getOrganizationRegisterYear(string $id)
    {
        try {
            dd(1);
            $result = $this->planCompanyService->getOrganizationRegisterYear($id);
            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result['data']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function createShoppingPlanCompanyYear(CreateShoppingPlanCompanyYearRequest $request)
    {
        Auth::user()->canPer('shopping_plan_company.crud');

        try {
            $data         = $request->validated();
            $data['type'] = ShoppingPlanCompany::TYPE_YEAR;

            $result = $this->planCompanyService->createShoppingPlanCompany($data);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function updateShoppingPlanCompanyYear(CreateShoppingPlanCompanyYearRequest $request, string $id)
    {
        Auth::user()->canPer('shopping_plan_company.crud');

        try {
            $data         = $request->validated();
            $data['type'] = ShoppingPlanCompany::TYPE_YEAR;
            $result       = $this->planCompanyService->updateShoppingPlanCompany($data, $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
