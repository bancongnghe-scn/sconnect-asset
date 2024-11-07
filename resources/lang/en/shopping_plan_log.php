<?php

use App\Models\ShoppingPlanLog;

return [
    ShoppingPlanLog::ACTION_CREATE_SHOPPING_PLAN_COMPANY                   => 'Tạo mới kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_UPDATE_SHOPPING_PLAN_COMPANY                   => 'Cập nhật kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_SENT_NOTIFICATION_SHOPPING_PLAN_COMPANY        => 'Gửi thông báo tới đơn vị',
    ShoppingPlanLog::ACTION_SEND_ACCOUNTANT_APPROVAL_SHOPPING_PLAN_COMPANY => 'Gửi giám độc kế toán duyệt',
];
