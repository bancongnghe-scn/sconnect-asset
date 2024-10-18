<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateShoppingPlanCompanyYear extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'time'               => 'required|integer',
            'type'               => 'required|integer',
            'start_time'         => 'required|date|date_format:Y-m-d',
            'end_time'           => 'required|date|date_format:Y-m-d',
            'monitor_ids'        => 'nullable|array',
            'monitor_ids.*'      => 'integer',
        ];
    }
}
