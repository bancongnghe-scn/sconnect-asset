<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoveAssetOrg extends Model
{
    use HasFactory;

    protected $table = 'move_asset_organizations';

    public const TYPE_ALLOCATION = 1;
    public const TYPE_RECOVERY   = 2;
    public const IS_ROTATION     = 1;
    protected $guarded           = [];
}
