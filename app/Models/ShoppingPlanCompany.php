<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingPlanCompany extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table    = 'shopping_plan_company';
    protected $fillable = [];

    public const STATUS_REGISTER                    = 1;
    public const STATUS_PENDING_ACCOUNTANT_APPROVAL = 2;
    public const STATUS_PENDING_MANAGER_APPROVAL    = 2;

    public const TYPE_YEAR    = 'year';
    public const TYPE_QUARTER = 'quarter';
    public const TYPE_WEEK    = 'week';

    public const TYPE_NAME = [
        self::TYPE_YEAR    => 'năm',
        self::TYPE_QUARTER => 'quý',
        self::TYPE_WEEK    => 'tuần',
    ];
}
