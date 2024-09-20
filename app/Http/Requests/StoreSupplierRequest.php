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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'name' => 'required|string',
            'contact' => 'nullable|string',
            'tax_code' => 'nullable|string',
            'address' => 'nullable|string',
            'website' => 'nullable|string',
            'industry_ids' => 'required|array',
            'industry_ids.*' => 'integer',
            'asset_type_ids' => 'required|array',
            'asset_type_ids.*' => 'integer',
            'description' => 'nullable|string',
            'meta_data' => 'nullable|array',
        ];
    }
}
