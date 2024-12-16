<?php

namespace App\Http\Requests;

class RegisterShoppingPlanOrganizationYearRequest extends RegisterShoppingPlanOrganizationRequest
{
    public function rules()
    {
        $rules                      = parent::rules();
        $rules['registers.*.month'] = 'required|integer';

        return $rules;
    }
}
