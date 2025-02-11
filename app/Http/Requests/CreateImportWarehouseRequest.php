<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateImportWarehouseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code'                                  => 'required|string',
            'name'                                  => 'required|string',
            'order_ids'                             => 'required|array',
            'order_ids.*'                           => 'integer',
            'description'                           => 'nullable|string',
            'shopping_assets'                       => 'required|array',
            'shopping_assets.*'                     => 'array',
            'shopping_assets.*.code'                => 'required|string',
            'shopping_assets.*.name'                => 'required|string',
            'shopping_assets.*.warranty_time'       => 'nullable|integer',
            'shopping_assets.*.seri_number'         => 'nullable|string',
            'shopping_assets.*.price'               => 'required|numeric',
            'shopping_assets.*.price_last'          => 'required|numeric',
            'shopping_assets.*.date_purchase'       => 'nullable|date',
            'shopping_assets.*.asset_type_id'       => 'required|integer',
            'shopping_assets.*.supplier_id'         => 'required|integer',
            'shopping_assets.*.order_id'            => 'required|integer',
            'shopping_assets.*.import_warehouse_id' => 'nullable|integer',
            'shopping_assets.*.id'                  => 'nullable|integer',
        ];
    }
}
