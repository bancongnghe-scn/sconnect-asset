<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateShoppingPlanCompanyYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'time'               => 'required|integer|gte:'.now()->year,
            'start_time'         => 'required|date|date_format:Y-m-d|after_or_equal:today',
            'end_time'           => 'required|date|date_format:Y-m-d|after_or_equal:start_time',
            'monitor_ids'        => 'nullable|array',
            'monitor_ids.*'      => 'integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'time'       => __('attributes.shopping_plan_company.year.time'),
            'start_time' => __('attributes.shopping_plan_company.start_time'),
            'end_time'   => __('attributes.shopping_plan_company.end_time'),
        ];
    }

    public function messages(): array
    {
        return [
            'gte'            => 'Trường :attribute phải lớn hơn hoặc bằng năm hiện tại.',
            'after_or_equal' => 'Trường :attribute phải lớn hơn hoặc bằng ngày hiện tại.',
        ];
    }
}
