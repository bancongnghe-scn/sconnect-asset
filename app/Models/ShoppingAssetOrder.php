<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingAssetOrder extends Model
{
    use HasFactory;
    protected $table    = 'shopping_assets_order';
    protected $fillable = [
        'name',
        'code',
        'price',
        'asset_type_id',
        'organization_id',
        'organization_id',
        'vat_rate',
        'description',
    ];
    public $timestamps = false;
}
