<?php

namespace App\Http\Controllers;

use App\Services\AssetTypeGroupService;
use Illuminate\Http\Request;

class AssetTypeGroupController extends Controller
{
    public function __construct(
        protected AssetTypeGroupService $assetTypeGroupService,
    ) {

    }

    public function index(Request $request)
    {
        $request->validate([
            'id'    => 'integer',
            'name'  => 'string|min:2|nullable',
            'page'  => 'integer',
            'limit' => 'integer|max:200',
        ]);

        try {
            $result = $this->assetTypeGroupService->getListAssetTypeGroup($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name'        => 'required|string',
                'description' => 'string',
            ],
            [
                'name.required'      => 'Tên nhóm tài sản là bắt buộc.',
                'name.string'        => 'Tên nhóm tài sản phải là một chuỗi ký tự.',
                'description.string' => 'Mô tả phải là một chuỗi ký tự.',
            ]
        );

        try {
            $result = $this->assetTypeGroupService->createAssetTypeGroup($request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function destroy(string $id)
    {
        try {
            $result = $this->assetTypeGroupService->deleteAssetTypeGroup($id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'name'        => 'required|string',
                'description' => 'string',
            ],
            [
                'name.required'      => 'Tên nhóm tài sản là bắt buộc.',
                'name.string'        => 'Tên nhóm tài sản phải là một chuỗi ký tự.',
                'description.string' => 'Mô tả phải là một chuỗi ký tự.',
            ]
        );

        try {
            $result = $this->assetTypeGroupService->updateAssetTypeGroup($request->all(), $id);

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
