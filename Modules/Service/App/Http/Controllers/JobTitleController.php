<?php

namespace Modules\Service\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Service\Services\JobTitleService;

class JobTitleController extends Controller
{
    public function __construct(
        protected JobTitleService $jobTitleService,
    ) {

    }

    public function getJobs(Request $request)
    {
        $request->validate([
            'id'       => 'nullable|array',
            'id.*'     => 'integer',
            'org_id'   => 'nullable|array',
            'org_id.*' => 'integer',
        ]);

        try {
            $result = $this->jobTitleService->getJobs($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
