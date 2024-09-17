<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetTypeGroup extends Model
{
    use SoftDeletes;
    protected $table = 'asset_type_groups';
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'deleted_at',
        'deleted_by',
    ];
}
