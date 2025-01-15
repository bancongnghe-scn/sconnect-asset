<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlanMaintainRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'                                      => 'required|string',
            'start_time'                                => 'required|date|date_format:Y-m-d',
            'end_time'                                  => 'required|date|date_format:Y-m-d',
            'organization_ids'                          => 'required|array',
            'organization_ids.*'                        => 'required|integer',
            'supplier_ids'                              => 'required|array',
            'supplier_ids.*'                            => 'required|integer',
            'maintain_costs'                            => 'nullable|string',
            'user_ids'                                  => 'nullable|array',
            'user_ids.*'                                => 'integer',
            'assets_maintain'                           => 'required|array',
            'assets_maintain.*'                         => 'array',
            'assets_maintain.*.asset_id'                => 'required|integer',
            'assets_maintain.*.name'                    => 'required|string',
            'assets_maintain.*.code'                    => 'required|string',
            'assets_maintain.*.asset_type_name'         => 'nullable|string',
            'assets_maintain.*.serial_number'           => 'nullable|string',
            'assets_maintain.*.user_id'                 => 'nullable|string',
            'assets_maintain.*.organization_id'         => 'nullable|string',
            'assets_maintain.*.location'                => 'nullable|string',
            'assets_maintain.*.recent_maintenance_date' => 'required|date|date_format:Y-m-d',
            'page'                                      => 'nullable|integer',
            'limit'                                     => 'nullable|integer',
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
