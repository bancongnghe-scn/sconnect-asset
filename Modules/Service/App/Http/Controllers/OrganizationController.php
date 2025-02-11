<?php

namespace Modules\Service\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Service\Services\OrganizationService;

class OrganizationController extends Controller
{
    public function __construct(
        protected OrganizationService $organizationService,
    ) {
    }

    public function getListOrganization(Request $request)
    {
        $request->validate([
            'status' => 'nullable|integer',
        ]);

        try {
            $result = $this->organizationService->getListOrganization($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }

    public function getOrganizationalStructure(Request $request)
    {
        $request->validate([
            'ids' => 'nullable|array',
        ]);

        try {
            $result = $this->organizationService->getOrganizationalStructure($request->get('ids'));

            return response_success($result);
        } catch (\Throwable $exception) {
            report($exception);

            return response_error();
        }
    }
}
