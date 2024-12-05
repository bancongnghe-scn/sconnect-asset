<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShoppingPlanCompanyWeekRequest;
use App\Models\ShoppingPlanCompany;
use App\Services\ShoppingPlanCompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingPlanCompanyWeekController extends Controller
{
    public function __construct(
        protected ShoppingPlanCompanyService $planCompanyService,
    ) {

    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasAnyRole(['accounting_director', 'hr_director'])) {
            return view('assets.shopping_plan_master.week.list');
        }
        if ($user->hasRole('manager_organization')) {
            return view('assets.shopping_plan_organization.week.list');
        } else {
            return view('assets.shopping-plan-company.week.list');
        }
    }

    public function getListShoppingPlanCompanyWeek(Request $request)
    {
        $request->validate([
            'name'         => 'nullable|integer',
            'start_time'   => 'nullable|integer',
            'end_time'     => 'nullable|integer',
            'status'       => 'nullable|array',
            'status.*'     => 'integer',
        ]);
        try {
            $filters         = $request->all();
            $filters['type'] = ShoppingPlanCompany::TYPE_WEEK;
            $result          = $this->planCompanyService->getListShoppingPlanCompany($filters);

            return response_success($result['data'] ?? [], extraData: $result['extra_data'] ?? []);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function createShoppingPlanCompanyWeek(CreateShoppingPlanCompanyWeekRequest $request)
    {
        Auth::user()->canPer('shopping_plan_company.crud');

        try {
            $data         = $request->validated();
            $data['type'] = ShoppingPlanCompany::TYPE_WEEK;
            $result       = $this->planCompanyService->createShoppingPlanCompany($data);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function updateShoppingPlanCompanyWeek(CreateShoppingPlanCompanyWeekRequest $request, string $id)
    {
        Auth::user()->canPer('shopping_plan_company.crud');

        try {
            $data         = $request->validated();
            $data['type'] = ShoppingPlanCompany::TYPE_WEEK;
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
