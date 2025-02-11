<?php

namespace App\Repositories;

use App\Models\OrderHistory;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Auth;

class OrderHistoryRepository extends BaseRepository
{
    public function getModelClass(): string
    {
        return OrderHistory::class;
    }

    public function getListing($filters, $columns = ['*'])
    {
        $query = $this->_model->newQuery()->select($columns);

        if (!empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['first'])) {
            return $query->first();
        }

        return $query->get();
    }

    public function insertOrderHistory($orderId, $type, $newValue = null, $oldValue = null, $description = null)
    {
        return $this->_model->insert([
            'order_id'    => $orderId,
            'type'        => $type,
            'new_value'   => $newValue,
            'old_value'   => $oldValue,
            'description' => $description ?? __('order_history_message.'.$type, ['new_value' => $newValue, 'old_value' => $oldValue]),
            'created_by'  => Auth::id(),
        ]);
    }
}
