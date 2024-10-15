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

    public function index(Request $request)
    {
        $request->validate([
            'time'   => 'nullable|integer',
            'type'   => 'nullable|integer',
            'status' => 'nullable|integer',
        ]);

        try {
            $result = $this->planCompanyService->getListPlanCompany($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'time'               => 'required|integer',
            'start_time'         => 'required|date|date_format:Y-m-d',
            'end_time'           => 'required|date|date_format:Y-m-d',
            'organization_ids'   => 'required|array',
            'organization_ids.*' => 'integer',
        ]);
    }
}
