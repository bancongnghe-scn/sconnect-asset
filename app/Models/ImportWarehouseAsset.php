<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportWarehouseAsset extends Model
{
    use HasFactory;

    protected $table    = 'import_warehouse_assets';
    protected $fillable = [
        'code',
        'name',
        'price',
        'price_last',
        'date_purchase',
        'warranty_time',
        'seri_number',
        'asset_type_id',
        'supplier_id',
        'order_id',
        'import_warehouse_id',
    ];
}
