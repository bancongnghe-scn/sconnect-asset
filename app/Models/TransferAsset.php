<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferAsset extends Model
{
    use HasFactory;

    protected $table = 'transfer_assets';

    protected $fillable = [
        'user_id',
        'org_id',
        'type',
        'to_user_id',
        'to_org_id',
        'created_by',
        'description',
        'link_report',
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userTo()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function organization()
    {
        return $this->belongsTo(Org::class, 'org_id');
    }

    public function organizationTo()
    {
        return $this->belongsTo(Org::class, 'to_org_id');
    }

    public function createBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
