<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SupplierAssetIndustry extends Model
{
    use HasFactory;
    protected $table = 'supplier_asset_industries';

    public function industry(): HasOne
    {
        return $this->hasOne(Industry::class, 'id', 'industries_id');
    }
}
