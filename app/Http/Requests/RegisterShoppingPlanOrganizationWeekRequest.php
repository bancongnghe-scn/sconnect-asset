<?php

namespace App\Http\Requests;

class RegisterShoppingPlanOrganizationWeekRequest extends RegisterShoppingPlanOrganizationRequest
{
    public function rules()
    {
        $rules                      = parent::rules();
        unset($rules['registers.*.assets.*.price']);
        $rules['registers.*.assets.*.receiving_time'] = 'nullable|date|date_format:Y-m-d';

        return $rules;
    }
}
