<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterShoppingPlanOrganizationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'shopping_plan_organization_id'            => 'required|integer',
            'registers'                                => 'required|array',
            'registers.*.assets'                       => 'nullable|array',
            'registers.*.assets.*.id'                  => 'nullable|integer',
            'registers.*.assets.*.asset_type_id'       => 'required|integer',
            'registers.*.assets.*.job_id'              => 'required|integer',
            'registers.*.assets.*.price'               => 'required|integer',
            'registers.*.assets.*.quantity_registered' => 'required|integer',
            'registers.*.assets.*.quantity_approved'   => 'required|integer',
            'registers.*.assets.*.description'         => 'nullable|string',
        ];
    }
}
