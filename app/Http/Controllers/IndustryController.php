<?php

namespace App\Http\Controllers;

use App\Services\IndustryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndustryController extends Controller
{
    public function __construct(
        protected IndustryService $industryService,
    ) {

    }

    public function index(Request $request)
    {
        $request->validate([
            'name'  => 'nullable|string',
            'page'  => 'integer',
            'limit' => 'integer|max:200',
        ]);

        try {
            $result = $this->industryService->getListIndustry($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $result = $this->industryService->createIndustry($request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            Log::error($exception);

            return response_error();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $result = $this->industryService->findIndustry($id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success($result['data']);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'        => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $result = $this->industryService->updateIndustry($request->all(), $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = $this->industryService->deleteIndustryById($id);
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
        ]);

        try {
            $result = $this->industryService->deleteIndustryMultiple($request->get('ids'));

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
