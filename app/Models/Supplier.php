<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';

    public function supplierAssetIndustries(): HasMany
    {
        return $this->hasMany(SupplierAssetIndustry::class, 'supplier_id');
    }
}
