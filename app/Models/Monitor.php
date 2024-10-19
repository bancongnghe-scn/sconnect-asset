<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    use HasFactory;
    protected $table = 'monitor';

    public const TYPE_CONTRACT                   = 1;
    public const TYPE_CONTRACT_APPENDIX          = 2;
    public const TYPE_SHOPPING_PLAN_COMPANY_YEAR = 3;
}
