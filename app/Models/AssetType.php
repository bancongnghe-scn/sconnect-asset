<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetType extends Model
{
    use HasFactory;
    protected $table = 'asset_type';

    protected $fillable = [
       'name',
       'asset_type_group_id',
       'description',
       'maintenance_months',
       'measure',
       'created_by',
       'updated_by',
    ];

    public const MEASURE_NAME = [
        1 => 'Chiếc',
        2 => 'Cái',
        3 => 'Bộ',
        4 => 'Bình',
        5 => 'Cuộn',
        6 => 'Hộp',
        7 => 'Túi',
        8 => 'Lọ',
        9 => 'Thùng',
        10 => 'Đôi',
    ];

    public function assetTypeGroup() : HasOne
    {
        return $this->hasOne(AssetTypeGroup::class, 'id', 'asset_type_group_id');
    }
}
