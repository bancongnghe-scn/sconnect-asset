<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public const STATUS_NEW        = 1;
    public const STATUS_TRANSIT    = 2;
    public const STATUS_DELIVERED  = 3;
    public const STATUS_WAREHOUSED = 4;
    public const STATUS_CANCEL     = 5;
}
