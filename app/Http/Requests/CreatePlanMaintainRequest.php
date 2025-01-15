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
            'sent_notification'                         => 'nullable|integer',
            'assets_maintain'                           => 'required|array',
            'assets_maintain.*'                         => 'array',
            'assets_maintain.*.id'                      => 'required|integer',
            'assets_maintain.*.name'                    => 'required|string',
            'assets_maintain.*.code'                    => 'required|string',
            'assets_maintain.*.asset_type_name'         => 'nullable|string',
            'assets_maintain.*.serial_number'           => 'nullable|string',
            'assets_maintain.*.user_id'                 => 'nullable|integer',
            'assets_maintain.*.organization_id'         => 'nullable|integer',
            'assets_maintain.*.location'                => 'nullable|integer',
            'assets_maintain.*.recent_maintenance_date' => 'required|date|date_format:Y-m-d',
            'page'                                      => 'nullable|integer',
            'limit'                                     => 'nullable|integer',
        ];
    }

    public function attributes()
    {
        return [
            'start_time'       => 'ngày bắt đầu bảo dưỡng',
            'end_time'         => 'ngày kết thúc bảo dưỡng',
            'organization_ids' => 'đơn vị bảo dưỡng',
            'supplier_ids'     => 'đơn vị thực hiện bảo dưỡng',
        ];
    }
}
