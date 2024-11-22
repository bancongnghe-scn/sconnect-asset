<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingPlanLog extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $table    = 'shopping_plan_logs';
    protected $fillable = [
        'action',
        'record_id',
        'new_value',
        'old_value',
        'desc',
        'created_by',
    ];

    // COMPANY
    public const ACTION_CREATE_SHOPPING_PLAN_COMPANY                   = 'shopping_plan_create';
    public const ACTION_UPDATE_SHOPPING_PLAN_COMPANY                   = 'shopping_plan_update';
    public const ACTION_SENT_NOTIFICATION_SHOPPING_PLAN_COMPANY        = 'shopping_plan_sent_notification';
    public const ACTION_SEND_ACCOUNTANT_APPROVAL_SHOPPING_PLAN_COMPANY = 'shopping_plan_send_account_approval';

    //ORGANIZATION
    public const ACTION_ACCOUNT_APPROVAL_ORGANIZATION    = 'shopping_plan_organization_account_approval';
    public const ACTION_ACCOUNT_DISAPPROVAL_ORGANIZATION = 'shopping_plan_organization_account_disapproval';
}
