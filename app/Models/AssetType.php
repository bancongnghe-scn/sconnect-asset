<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'asset_type';
    protected $fillable = [
       'name',
       'asset_type_group_id',
       'description',
       'maintenance_months',
       'created_by',
       'updated_by',
       'deleted_at',
       'deleted_by',
    ];

    public function assetTypeGroup() : HasOne
    {
        return $this->hasOne(AssetTypeGroup::class, 'id', 'asset_type_group_id');
    }
}
