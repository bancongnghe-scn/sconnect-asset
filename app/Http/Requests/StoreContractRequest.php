<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
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
            'code'                    => 'required|string|max:255',
            'name'                    => 'required|string|max:255',
            'type'                    => 'required|integer',
            'supplier_id'             => 'required|integer',
            'signing_date'            => 'required|date|date_format:Y-m-d',
            'from'                    => 'required|date|date_format:Y-m-d',
            'to'                      => 'nullable|date_format:Y-m-d|after:from',
            'user_ids'                => 'required|array',
            'user_ids.*'              => 'integer',
            'contract_value'          => 'nullable|integer',
            'description'             => 'nullable|string',
            'files'                   => 'nullable|array',
            'payments'                => 'nullable|array',
            'payments.*.payment_date' => 'date|date_format:Y-m-d',
            'payments.*.money'        => 'integer',
            'payments.*.description'  => 'string',
        ];
    }

    public function attributes(): array
    {
        return [
            'code'         => __('attributes.contract.code'),
            'type'         => __('attributes.contract.type'),
            'signing_date' => __('attributes.contract.signing_date'),
            'user_ids'     => __('attributes.contract.user_ids'),
        ];
    }
}
