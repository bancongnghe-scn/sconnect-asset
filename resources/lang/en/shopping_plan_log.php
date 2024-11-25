<?php

use App\Models\ShoppingPlanLog;

return [
    ShoppingPlanLog::ACTION_CREATE_SHOPPING_PLAN_COMPANY                   => 'Tạo mới kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_UPDATE_SHOPPING_PLAN_COMPANY                   => 'Cập nhật kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_SENT_NOTIFICATION_SHOPPING_PLAN_COMPANY        => 'Gửi thông báo tới đơn vị',
    ShoppingPlanLog::ACTION_SEND_ACCOUNTANT_APPROVAL_SHOPPING_PLAN_COMPANY => 'Gửi giám độc kế toán duyệt',
    ShoppingPlanLog::ACTION_SEND_MANAGER_APPROVAL_SHOPPING_PLAN_COMPANY    => 'Gửi tổng giám độc duyệt',
    ShoppingPlanLog::ACTION_ACCOUNT_APPROVAL_ORGANIZATION                  => 'Kế toán đã duyệt kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_ACCOUNT_DISAPPROVAL_ORGANIZATION               => 'Kế toán từ chối kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_ACCOUNT_REVIEW_ORGANIZATION                    => 'Kế toán đang review kế hoạch mua sắm',
];
