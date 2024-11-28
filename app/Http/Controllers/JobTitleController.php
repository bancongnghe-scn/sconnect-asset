<?php

namespace App\Http\Controllers;

use App\Services\JobTitleService;
use App\Services\ScApiService;

class JobTitleController extends Controller
{
    public function __construct(
        protected JobTitleService $jobTitleService,
    ) {
    }

    public function getAllJob()
    {
        try {
            return response_success(ScApiService::getAllJob());
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
