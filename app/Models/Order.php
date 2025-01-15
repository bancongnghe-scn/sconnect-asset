<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $table    = 'orders';
    protected $fillable = [
        'name',
        'code',
        'supplier_id',
        'shopping_plan_company_id',
        'status',
        'description',
        'type',
        'delivery_date',
        'delivery_location',
        'purchasing_manager_id',
        'contact_person',
        'contract_info',
        'payment_time',
        'shipping_costs',
        'other_costs',
        'reason',
        'created_by',
    ];

    public const STATUS_NEW                = 1;
    public const STATUS_TRANSIT            = 2;
    public const STATUS_DELIVERED          = 3;
    public const STATUS_WAREHOUSED         = 4;
    public const STATUS_CANCEL             = 5;
    public const TYPE_CREATE_WITH_PLAN     = 1;
    public const TYPE_CREATE_WITH_NOT_PLAN = 2;

    public function shoppingPlanCompany(): BelongsTo
    {
        return $this->belongsTo(ShoppingPlanCompany::class, 'shopping_plan_company_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function purchasingManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'purchasing_manager_id');
    }

    public function shoppingAssetOrders(): HasMany
    {
        return $this->hasMany(ShoppingAssetOrder::class, 'order_id');
    }
}
