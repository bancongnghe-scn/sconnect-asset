<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanInventoryAsset extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table    = 'plan_inventory_asset';
    protected $fillable = [
        'plan_maintain_id',
        'asset_id',
        'status',
        'organization_id',
        'user_id',
        'manager_id',
        'status_asset',
        'location',
        'organization_id_present',
        'user_id_present',
        'manager_id_present',
        'status_asset_present',
        'location_present',
        'note',
        'created_by',
    ];

    public const STATUS_NOT_INVENTORIED = 0;
    public const STATUS_INVENTORIED     = 1;
}
