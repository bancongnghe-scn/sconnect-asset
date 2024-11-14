<?php

namespace App\Http\Requests;

class UpdateShoppingPlanCompanyRequest extends CreateShoppingPlanCompanyYearRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = parent::rules();
        unset($rules['time']);

        return $rules;
    }
}
