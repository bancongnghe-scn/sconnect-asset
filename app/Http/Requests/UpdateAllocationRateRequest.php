<?php

namespace App\Http\Requests;

class UpdateAllocationRateRequest extends CreateAllocationRateRequest
{
    public function rules(): array
    {
        $rules                 = parent::rules();
        $rules['configs.*.id'] = 'nullable|integer';

        return $rules;
    }
}
