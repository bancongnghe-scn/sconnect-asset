<?php

namespace App\Http\Requests;

class CreateShoppingPlanCompanyWeekRequest extends CreateShoppingPlanCompanyQuarterRequest
{
    public function rules(): array
    {
        $rules                    = parent::rules();
        $rules['plan_quarter_id'] = 'required|integer';
        $rules['month']           = 'required|integer';

        return $rules;
    }

    public function attributes(): array
    {
        $attributes                     = parent::attributes();
        $attributes['time']             = __('attributes.shopping_plan_company.week.time');
        $attributes['plan_quarter_id']  = __('attributes.shopping_plan_company.plan_quarter_id');

        return $attributes;
    }
}
