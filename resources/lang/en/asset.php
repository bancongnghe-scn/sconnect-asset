<?php

return [
    'shopping_plan_company' => [
        App\Models\ShoppingPlanCompany::TYPE_YEAR => [
            'name' => 'Kế hoạch mua sắm năm :time',
        ],
        App\Models\ShoppingPlanCompany::TYPE_QUARTER => [
            'name' => 'Kế hoạch mua sắm quý :time năm :time_other',
        ],
        App\Models\ShoppingPlanCompany::TYPE_WEEK => [
            'name' => 'Kế hoạch mua sắm tuần :time tháng :time_other',
        ],
    ],
];
