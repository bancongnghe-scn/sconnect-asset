<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoveAssetOrg extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'move_asset_organizations';
}
