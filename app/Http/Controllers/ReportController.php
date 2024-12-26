<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService)
    {
    }

    public function overviewReport(): View
    {
        $arrDataOverview = $this->reportService->getDataOverviewReport();

        return view('assets.report.overviewReport', $arrDataOverview);
    }

    public function getDataValueReport(): JsonResponse
    {
        try {
            $arrData = $this->reportService->getDataValueReport();

            return response_success($arrData);
        } catch (\Throwable $exception) {
            Log::error($exception);
            dd($exception);

            return response_error();
        }
    }

    public function getDataOperatingReport(): JsonResponse
    {
        try {
            $arrData = $this->reportService->getDataOperatingReport();

            return response_success($arrData);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function getDataStructureReport(): JsonResponse
    {
        try {
            $arrData = $this->reportService->getDataStructureReport();

            return response_success($arrData);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function getDataUseReport(): JsonResponse
    {
        try {
            $arrData = $this->reportService->getDataUseReport();

            return response_success($arrData);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    public function getDataMaintainReport(): JsonResponse
    {
        try {
            $arrData = $this->reportService->getDataMaintainReport();

            return response_success($arrData);
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }
}
