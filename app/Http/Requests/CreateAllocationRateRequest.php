<?php

namespace App\Http\Requests;

use App\Models\AllocationRate;
use Illuminate\Foundation\Http\FormRequest;

class CreateAllocationRateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'organization_id'         => 'required|integer',
            'type'                    => 'required|integer',
            'position_id'             => 'required_if:type,'.AllocationRate::TYPE_POSITION,
            'configs'                 => 'required|array',
            'configs.*.asset_type_id' => 'required|integer',
            'configs.*.level'         => 'nullable|integer',
            'configs.*.description'   => 'nullable|integer',
            'configs.*.price'         => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'organization_id'         => __('attributes.organization_id'),
            'position_id'             => __('attributes.job_id'),
            'configs'                 => 'cấu hình định mức',
            'configs.*.asset_type_id' => 'loại tài sản',
        ];
    }
}
