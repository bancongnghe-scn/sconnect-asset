<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrgJobTitle extends BaseModel
{
    use HasFactory;

    protected $table = 'org_job_titles';

    public function positionOffice()
    {
        return $this->belongsTo(Config::class, 'position');
    }

    public function jobPosition()
    {
        return $this->belongsTo(Config::class, 'job_position');
    }
}
