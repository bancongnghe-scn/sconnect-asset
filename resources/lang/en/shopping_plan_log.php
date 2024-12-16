<?php

use App\Models\ShoppingPlanLog;

return [
    ShoppingPlanLog::ACTION_CREATE_SHOPPING_PLAN_COMPANY                                 => 'Tạo mới kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_UPDATE_SHOPPING_PLAN_COMPANY                                 => 'Cập nhật kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_SENT_NOTIFICATION_SHOPPING_PLAN_COMPANY                      => 'Gửi thông báo tới đơn vị',
    ShoppingPlanLog::ACTION_SEND_ACCOUNTANT_APPROVAL_SHOPPING_PLAN_COMPANY               => 'Gửi giám độc kế toán duyệt',
    ShoppingPlanLog::ACTION_SEND_MANAGER_APPROVAL_SHOPPING_PLAN_COMPANY                  => 'Gửi tổng giám độc duyệt',
    ShoppingPlanLog::ACTION_ACCOUNT_APPROVAL_ORGANIZATION                                => 'Kế toán đã duyệt kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_ACCOUNT_DISAPPROVAL_ORGANIZATION                             => 'Kế toán từ chối kế hoạch mua sắm với lý do ":note"',
    ShoppingPlanLog::ACTION_ACCOUNT_REVIEW_ORGANIZATION                                  => 'Kế toán đang review kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_MANAGER_APPROVAL_SHOPPING_PLAN_COMPANY                       => 'Giám đốc đã duyệt kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_MANAGER_DISAPPROVAL_SHOPPING_PLAN_COMPANY                    => 'Giám đốc đã từ chối kế hoạch mua sắm với lý do ":note"',
    ShoppingPlanLog::ACTION_REGISTER_SHOPPING                                            => 'Đăng ký mua sắm',
    ShoppingPlanLog::ACTION_HR_HANDLE_PLAN_COMPANY                                       => 'Nhân sự xử lý kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_HR_SYNTHETIC_PLAN_COMPANY                                    => 'Nhân sự tổng hợp kế hoạch mua sắm',
    ShoppingPlanLog::ACTION_SENT_INFO_SHOPPING_ASSET                                     => 'Nhân sự điền thông tin mua sắm',
    ShoppingPlanLog::ACTION_SEND_HR_MANAGER_APPROVAL                                     => 'Gửi giám đốc nhân sự duyệt',
];
