<?php

namespace App\Http\Controllers;

use App\Models\AssetTypeGroup;
use App\Services\AssetTypeGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AssetTypeGroupController extends Controller
{
    public function __construct(
        protected AssetTypeGroupService $assetTypeGroupService
    )
    {

    }
    public function getListAssetTypeGroup(Request $request) {
        $request->validate([
            'id' => 'integer',
            'name' => 'string|min:2|nullable',
            'page' => 'integer',
            'limit' => 'integer|max:200',
        ]);

        try {
            $result = $this->assetTypeGroupService->getListAssetTypeGroup($request->all());

            return response_success($result);
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function createAssetTypeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'string',
        ],
            [
                'name.required' => 'Tên nhóm tài sản là bắt buộc.',
                'name.string' => 'Tên nhóm tài sản phải là một chuỗi ký tự.',
                'description.string' => 'Mô tả phải là một chuỗi ký tự.'
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

    public function deleteAssetTypeGroup(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ], [
            'id.required' => 'ID nhóm tài sản là bắt buộc.',
        ]);
        try {
            $result = $this->assetTypeGroupService->deleteAssetTypeGroup($request->integer('id'));

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }

    public function updateAssetTypeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'string',
            'id' => 'required|integer',
        ],
            [
                'name.required' => 'Tên nhóm tài sản là bắt buộc.',
                'name.string' => 'Tên nhóm tài sản phải là một chuỗi ký tự.',
                'description.string' => 'Mô tả phải là một chuỗi ký tự.'
            ]
        );

        try {
            $result = $this->assetTypeGroupService->updateAssetTypeGroup($request->all());

            if (!$result['success']) {
                return response_error($result['error_code']);
            }

            return response_success();
        } catch (\Throwable $exception) {
            return response_error();
        }
    }
}
