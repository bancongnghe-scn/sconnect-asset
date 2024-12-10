<?php

namespace App\Http\Controllers\ShoppingPlanCompany;

use App\Http\Controllers\Controller;
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
            'plan_quarter_id' => 'nullable|integer',
            'time'            => 'nullable|integer',
            'status'          => 'nullable|array',
            'status.*'        => 'integer',
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

    public function handleShopping(string $id)
    {
        Auth::user()->canPer('shopping_plan_company.handle_shopping');

        try {
            $result = $this->planCompanyService->handleShoppingPlanWeek($id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function syntheticShopping(Request $request)
    {
        $request->validate([
            'shopping_plan_company_id' => 'required|integer',
            'shopping_assets'          => 'nullable|array',
            'shopping_assets.*'        => 'nullable|array',
            'shopping_assets.*.id'     => 'required|integer',
            'shopping_assets.*.action' => 'nullable|integer',
        ]);

        Auth::user()->canPer('shopping_plan_company.synthetic_shopping');

        try {
            $result = $this->planCompanyService->syntheticShoppingPlanWeek($request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            dd($exception);
            report($exception);

            return response_error();
        }
    }
}
