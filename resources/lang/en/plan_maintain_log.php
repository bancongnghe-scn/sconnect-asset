<?php

use App\Models\PlanMaintainLog;

return [
    PlanMaintainLog::ACTION_CREATE_PLAN_MAINTAIN    => 'Tạo mới kế hoạch bảo dưỡng',
    PlanMaintainLog::ACTION_UPDATE_PLAN_MAINTAIN    => 'Cập nhật kế hoạch bảo dưỡng',
    PlanMaintainLog::ACTION_COMPLETE_PLAN_MAINTAIN  => 'Hoàn thành kế hoạch bảo dưỡng',
    PlanMaintainLog::ACTION_DELETE_PLAN_MAINTAIN    => 'Xóa kế hoạch bảo dưỡng',
    PlanMaintainLog::ACTION_CREATE_PLAN_INVENTORY   => 'Tạo mới kế hoạch kiểm kê',
    PlanMaintainLog::ACTION_UPDATE_PLAN_INVENTORY   => 'Cập nhật kế hoạch kiểm kê',
    PlanMaintainLog::ACTION_COMPLETE_PLAN_INVENTORY => 'Hoàn thành kế hoạch kiểm kê',
    PlanMaintainLog::ACTION_DELETE_PLAN_INVENTORY   => 'Xóa kế hoạch kiểm kê',
];
