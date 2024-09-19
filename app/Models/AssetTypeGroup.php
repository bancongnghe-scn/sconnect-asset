<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetTypeGroup extends Model
{
    protected $table = 'asset_type_groups';
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_at',
        'updated_by',
    ];
}
