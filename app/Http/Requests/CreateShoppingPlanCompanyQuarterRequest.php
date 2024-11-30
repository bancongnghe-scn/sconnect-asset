<?php

namespace App\Http\Requests;

class CreateShoppingPlanCompanyQuarterRequest extends CreateShoppingPlanCompanyYearRequest
{
    public function rules(): array
    {
        $rules['plan_year_id'] = 'required|integer';

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'time'          => __('attributes.shopping_plan_company.quarter.time'),
            'plan_year_id'  => __('attributes.shopping_plan_company.plan_year_id'),
            'start_time'    => __('attributes.shopping_plan_company.start_time'),
            'end_time'      => __('attributes.shopping_plan_company.end_time'),
        ];
    }
}
