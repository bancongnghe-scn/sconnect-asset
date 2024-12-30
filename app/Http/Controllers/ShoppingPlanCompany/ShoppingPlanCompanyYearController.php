<?php

namespace App\Http\Controllers\ShoppingPlanCompany;

use App\Http\Controllers\Controller;
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

        if ($user->hasAnyRole(['accounting_director', 'hr_director'])) {
            return view('assets.shopping_plan_master.year.list');
        }
        if ($user->hasRole('manager_organization')) {
            return view('assets.shopping_plan_organization.year.list');
        } else {
            return view('assets.shopping-plan-company.year.list');
        }
    }

    public function getListShoppingPlanCompanyYear(Request $request)
    {
        $request->validate([
            'time'     => 'nullable|integer',
            'status'   => 'nullable|integer',
        ]);
        try {
            $filters         = $request->all();
            $filters['type'] = ShoppingPlanCompany::TYPE_YEAR;
            $result          = $this->planCompanyService->getListShoppingPlanCompany($filters);

            return response_success($result['data'] ?? [], extraData: $result['extra_data'] ?? []);
        } catch (\Throwable $exception) {
            report($exception);

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
            report($exception);

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
            report($exception);

            return response_error();
        }
    }
}
