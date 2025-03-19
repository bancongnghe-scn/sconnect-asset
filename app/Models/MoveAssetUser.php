<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoveAssetUser extends Model
{
    use HasFactory;

    protected $table = 'move_asset_users';

    protected $guarded = [];

    public const TYPE_ALLOCATION = 1;
    public const TYPE_RECOVERY   = 2;

    public function transferAsset()
    {
        return $this->hasOne(TransferAsset::class, 'id', 'transfer_asset_id');
    }
}
