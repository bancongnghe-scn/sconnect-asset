<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanMaintainAsset extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps  = false;
    protected $table    = 'plan_maintain_asset';
    protected $guarded  = [];

    public const STATUS_NEW                     = 1;
    public const STATUS_APPROVAL                = 2;
    public const STATUS_CANCEL                  = 3;
    public const STATUS_MAINTAINING             = 4;
    public const STATUS_COMPLETE_MAINTAINING    = 5;

    public const STATUS_NAME = [
        self::STATUS_NEW                    => 'Chưa duyệt',
        self::STATUS_APPROVAL               => 'Đã duyệt',
        self::STATUS_CANCEL                 => 'Từ chối',

    ];

    public function planMaintain(): BelongsTo
    {
        return $this->belongsTo(PlanMaintain::class, 'plan_maintain_id');
    }

    public function asset(): HasOne
    {
        return $this->hasOne(Asset::class, 'id', 'asset_id');
    }
}
