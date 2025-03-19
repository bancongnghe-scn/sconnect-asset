<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanInventoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'              => 'required|string',
            'start_time'        => 'required|date|date_format:Y-m-d',
            'end_time'          => 'required|date|date_format:Y-m-d',
            'type_inventory'    => 'nullable|integer',
            'organization_ids'  => 'nullable|array',
            'asset_type_ids'    => 'nullable|array',
            'user_ids'          => 'nullable|array',
            'note'              => 'nullable|string',
            'sent_notification' => 'nullable|boolean',
            'assets'            => 'nullable|array',
        ];
    }
}
