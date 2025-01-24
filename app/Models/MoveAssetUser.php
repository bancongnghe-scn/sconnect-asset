<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoveAssetUser extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'move_asset_users';

    protected $guarded = [];

    public function transferAsset()
    {
        return $this->hasOne(TransferAsset::class, 'id', 'transfer_asset_id');
    }
}
