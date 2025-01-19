<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanMaintainLog extends Model
{
    use HasFactory;
    protected $table = 'plan_maintain_log';
    protected $fillable = [
        'new_value',
        'old_value'
    ];
    protected $casts = [
        'new_value' => 'array',
        'old_value' => 'array',
    ];
    public const ACTION_CREATE_PLAN_MAINTAIN = 'action_create_plan_maintain';
    public const ACTION_UPDATE_PLAN_MAINTAIN = 'action_update_plan_maintain';
    public const ACTION_COMPLETE_PLAN_MAINTAIN = 'action_complete_plan_maintain';
    public const ACTION_DELETE_PLAN_MAINTAIN = 'action_delete_plan_maintain';
}
