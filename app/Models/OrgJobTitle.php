<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgJobTitle extends Model
{
    use HasFactory;

    protected $connection = 'db_dev';

    protected $table = "org_job_titles";

    public function positionOffice()
    {
        return $this->belongsTo(Config::class, 'position');
    }
    public function jobPosition()
    {
        return $this->belongsTo(Config::class, 'job_position');
    }
}
