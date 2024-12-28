<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $connection = 'db_dev';

    protected $table = 'organizations';

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
