<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanMaintainRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'supplier_ids'   => 'required|array',
            'maintain_costs' => 'nullable|string',
            'user_ids'       => 'nullable|array',
            'note'           => 'nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'supplier_ids'     => 'đơn vị thực hiện bảo dưỡng',
        ];
    }
}
