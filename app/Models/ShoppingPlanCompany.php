<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingPlanCompany extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table    = 'shopping_plan_company';
    protected $fillable = [
        'name',
        'time',
        'status',
        'type',
        'start_time',
        'end_time',
        'month',
        'plan_year_id',
        'plan_quarter_id',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    public const STATUS_NEW                         = 1;
    public const STATUS_REGISTER                    = 2;
    public const STATUS_PENDING_ACCOUNTANT_APPROVAL = 3;
    public const STATUS_PENDING_MANAGER_APPROVAL    = 4;
    public const STATUS_APPROVAL                    = 5;
    public const STATUS_DISAPPROVAL                 = 6;

    public const TYPE_YEAR    = 1;
    public const TYPE_QUARTER = 2;
    public const TYPE_WEEK    = 3;

    public const TYPE_NAME = [
        self::TYPE_YEAR    => 'năm',
        self::TYPE_QUARTER => 'quý',
        self::TYPE_WEEK    => 'tuần',
    ];

    public function monitorShoppingPlanYear(): HasMany
    {
        return $this->hasMany(Monitor::class, 'target_id')->where('type', Monitor::TYPE_SHOPPING_PLAN_COMPANY[ShoppingPlanCompany::TYPE_YEAR]);
    }

    public function monitorShoppingPlanQuarter(): HasMany
    {
        return $this->hasMany(Monitor::class, 'target_id')->where('type', Monitor::TYPE_SHOPPING_PLAN_COMPANY[ShoppingPlanCompany::TYPE_QUARTER]);
    }
}
