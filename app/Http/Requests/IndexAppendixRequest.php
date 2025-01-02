<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexAppendixRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name_code'       => 'nullable|string|max:255',
            'contract_id'     => 'nullable|integer',
            'status'          => 'nullable|integer',
            'signing_date'    => 'nullable|date|date_format:Y-m-d',
            'from'            => 'nullable|date|date_format:Y-m-d',
            'page'            => 'nullable|integer',
            'limit'           => 'nullable|integer|max:200',
        ];
    }
}
