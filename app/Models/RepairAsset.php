<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepairAsset extends Model
{
    use HasFactory;
    protected $table = 'repair_asset';

    protected $fillable = [
        'asset_id',
        'date_repair',
        'date_repaired',
        'address',
        'performer_supplier',   // người thực hiện or nhà cung cấp
        'address_repair',
        'cost_repair',
        'note_repair',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const ADDRESS_COMPANY  = 1;
    public const ADDRESS_SUPPLIER = 2;

    public const ADDRESS_NAME = [
        self::ADDRESS_COMPANY   => 'company',
        self::ADDRESS_SUPPLIER  => 'supplier',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
