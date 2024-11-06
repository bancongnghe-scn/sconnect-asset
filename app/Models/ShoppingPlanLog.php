<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingPlanLog extends Model
{
    use HasFactory;
    protected $table                                 = 'shopping_plan_logs';
    public const ACTION_CREATE_SHOPPING_PLAN_COMPANY = 1;
}
