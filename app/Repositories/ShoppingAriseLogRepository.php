<?php

namespace App\Repositories;

use App\Models\ShoppingAriseLog;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ShoppingAriseLogRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return ShoppingAriseLog::class;
    }

    public function insertShoppingAriseLog($action, $shoppingAriseId, $newValue = [], $oldValue = [], $desc = null)
    {
        return $this->_model->insert([
            'action'            => $action,
            'shopping_arise_id' => $shoppingAriseId,
            'new_value'         => $newValue,
            'old_value'         => $oldValue,
            'desc'              => $desc ?? __('shopping_arise_log.' . $action, [
                'new_value' => $newValue,
                'old_value' => $oldValue,
            ]),
            'created_by' => Auth::id(),
        ]);
    }
}
