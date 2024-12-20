<?php

use App\Models\OrderHistory;

return [
    OrderHistory::TYPE_CREATE_ORDER => 'Tạo đơn hàng',
    OrderHistory::TYPE_UPDATE_ORDER => 'Cập nhật đơn hàng từ :old_value thành :new_value',
];
