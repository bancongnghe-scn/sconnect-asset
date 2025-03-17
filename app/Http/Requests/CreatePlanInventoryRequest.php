<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePlanInventoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'              => 'required|string',
            'start_time'        => 'required|date|date_format:Y-m-d',
            'end_time'          => 'required|date|date_format:Y-m-d',
            'type_inventory'    => 'required|integer',
            'organization_ids'  => 'required|array',
            'asset_type_ids'    => 'required|array',
            'user_ids'          => 'nullable|array',
            'note'              => 'nullable|string',
            'sent_notification' => 'nullable|integer',
        ];
    }
}
