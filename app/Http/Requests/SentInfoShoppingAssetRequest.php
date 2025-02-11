<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SentInfoShoppingAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'shopping_plan_company_id'   => 'required|integer',
            'assets'                     => 'required|array',
            'assets.*.id'                => 'required|integer',
            'assets.*.quantity_approved' => 'nullable|integer',
            'assets.*.price'             => 'nullable|integer',
            'assets.*.tax_money'         => 'nullable|integer',
            'assets.*.supplier_id'       => 'nullable|integer',
            'assets.*.link'              => 'nullable|string',
        ];
    }
}
