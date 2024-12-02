<?php

namespace App\Http\Requests;

class CreateShoppingPlanCompanyQuarterRequest extends CreateShoppingPlanCompanyYearRequest
{
    public function rules(): array
    {
        $rules                 = parent::rules();
        $rules['plan_year_id'] = 'required|integer';
        $rules['time']         = 'required|integer';

        return $rules;
    }

    public function attributes(): array
    {
        $attributes                 = parent::attributes();
        $attributes['time']         = __('attributes.shopping_plan_company.quarter.time');
        $attributes['plan_year_id'] = __('attributes.shopping_plan_company.plan_year_id');

        return $attributes;
    }
}
