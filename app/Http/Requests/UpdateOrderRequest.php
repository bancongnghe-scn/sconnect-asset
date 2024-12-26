<?php

namespace App\Http\Requests;

class UpdateOrderRequest extends CreateOrderRequest
{
    public function rules(): array
    {
        $rules                               = parent::rules();
        $rules['id']                         = 'required|integer';
        $rules['shopping_assets_order.*.id'] = 'required|integer';
        unset($rules['shopping_plan_company_id'], $rules['supplier_id']);

        return $rules;
    }
}
