<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppendixRequest extends FormRequest
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
            'contract_id'             => 'required|integer',
            'code'                    => 'required|string|max:255',
            'name'                    => 'required|string|max:255',
            'signing_date'            => 'required|date|date_format:Y-m-d',
            'from'                    => 'required|date|date_format:Y-m-d',
            'to'                      => 'nullable|date_format:Y-m-d|after:from',
            'user_ids'                => 'required|array',
            'user_ids.*'              => 'integer',
            'description'             => 'nullable|string',
            'link'                    => 'nullable|string',
            'files'                   => 'nullable|array',
        ];
    }
}
