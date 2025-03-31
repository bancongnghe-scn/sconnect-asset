<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateShoppingAriseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'                         => 'required|string',
            'assets'                       => 'required|array',
            'assets.*.asset_type_id'       => 'required|integer',
            'assets.*.quantity_registered' => 'required|integer',
            'assets.*.job_id'              => 'required|integer',
            'assets.*.receiving_time'      => 'nullable|date',
            'assets.*.description'         => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'assets'                       => 'tài sản đăng ký',
            'assets.*.asset_type_id'       => 'loại tài sản',
            'assets.*.quantity_registered' => 'số lượng',
            'assets.*.job_id'              => 'vị trí chức danh',
        ];
    }
}
