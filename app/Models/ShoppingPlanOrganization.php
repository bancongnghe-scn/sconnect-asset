<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingPlanOrganization extends Model
{
    protected $table                                = 'shopping_plan_organization';
    public const STATUS_NEW                         = 1;
    public const STATUS_REGISTER                    = 2;
    public const STATUS_PENDING_ACCOUNTANT_APPROVAL = 3;
    public const STATUS_PENDING_MANAGER_APPROVAL    = 4;
    public const STATUS_DISAPPROVAL                 = 5;
}
