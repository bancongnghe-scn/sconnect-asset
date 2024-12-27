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
    ];
    public $timestamps = false;
}
