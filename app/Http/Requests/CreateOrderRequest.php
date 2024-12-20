<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'                                                          => 'required|string',
            'shopping_plan_company_id'                                      => 'required|integer',
            'supplier_id'                                                   => 'required|integer',
            'purchasing_manager_id'                                         => 'required|string',
            'status'                                                        => 'required|integer',
            'shopping_assets_order'                                         => 'required|array',
            'shopping_assets_order.*'                                       => 'array',
            'shopping_assets_order.*.code'                                  => 'required|string',
            'shopping_assets_order.*.name'                                  => 'required|string',
            'shopping_assets_order.*.price'                                 => 'required|string',
            'shopping_assets_order.*.discount_rate'                         => 'nullable|integer',
            'delivery_date'                                                 => 'nullable|date|date_format:Y-m-d',
            'delivery_location'                                             => 'nullable|string',
            'contact_person'                                                => 'nullable|string',
            'contract_info'                                                 => 'nullable|string',
            'payment_time'                                                  => 'nullable|date|date_format:Y-m-d',
        ];
    }
}
