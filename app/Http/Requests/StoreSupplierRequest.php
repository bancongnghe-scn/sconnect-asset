<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'code'                   => 'required|string|max:255',
            'name'                   => 'required|string|max:255',
            'contact'                => 'nullable|string|max:255',
            'tax_code'               => 'nullable|string|max:255',
            'address'                => 'nullable|string|max:255',
            'email'                  => 'nullable|email',
            'contract_user'          => 'nullable|string|max:255',
            'industry_ids'           => 'required|array',
            'industry_ids.*'         => 'integer',
            'asset_type_ids'         => 'required|array',
            'asset_type_ids.*'       => 'integer',
            'description'            => 'nullable|string',
            'meta_data'              => 'nullable|array',
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => __('attributes.supplier.code'),
        ];
    }
}
