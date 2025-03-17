<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanInventoryAsset extends Model
{
    use HasFactory;
    protected $table = 'plan_inventory_asset';

    public const STATUS_NOT_INVENTORIED = 0;
    public const STATUS_INVENTORIED     = 1;
}
