<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'                                                              => 'required|string',
            'shopping_plan_company_id'                                          => 'required|integer',
            'supplier_id'                                                       => 'required|integer',
            'purchasing_manager_id'                                             => 'required|integer',
            'status'                                                            => 'required|integer',
            'delivery_date'                                                     => 'nullable|date|date_format:Y-m-d',
            'delivery_location'                                                 => 'nullable|string',
            'contact_person'                                                    => 'nullable|string',
            'contract_info'                                                     => 'nullable|string',
            'payment_time'                                                      => 'nullable|date|date_format:Y-m-d',
            'shipping_costs'                                                    => 'nullable|string',
            'other_costs'                                                       => 'nullable|string',
            'shopping_assets_order'                                             => 'required|array',
            'shopping_assets_order.*'                                           => 'array',
            'shopping_assets_order.*.code'                                      => 'required|string',
            'shopping_assets_order.*.name'                                      => 'required|string',
            'shopping_assets_order.*.price'                                     => 'required|numeric',
            'shopping_assets_order.*.asset_type_id'                             => 'required|integer',
            'shopping_assets_order.*.organization_id'                           => 'required|integer',
            'shopping_assets_order.*.shopping_asset_id'                         => 'required|integer',
            'shopping_assets_order.*.supplier_id'                               => 'required|integer',
            'shopping_assets_order.*.vat_rate'                                  => 'nullable|integer',
            'shopping_assets_order.*.description'                               => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'purchasing_manager_id'        => __('attributes.purchasing_manager_id'),
            'shopping_plan_company_id'     => __('attributes.shopping_plan_company_id'),
            'shopping_assets_order.*.name' => 'tên mặt hàng',
        ];
    }
}
