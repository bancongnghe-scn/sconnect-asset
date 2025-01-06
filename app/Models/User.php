<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\MigrateAuthorize;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use MigrateAuthorize;
    public const STATUS_ACTIVE = 1;
    protected $guard_name      = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    protected $appends = ['org_last_parent', 'job_position'];

    public function organization()
    {
        return $this->hasOne(Org::class, 'id', 'dept_id');
    }

    public function jobTitle()
    {
        return $this->hasOne(OrgJobTitle::class, 'id', 'job_title_id');
    }

    //code cũ
    public function getJobPositionAttribute()
    {
        if (isset($this->jobTitle, $this->jobTitle->positionOffice, $this->jobTitle->jobPosition)) {
            $job = $this->jobTitle->positionOffice->cfg_key . ' ' . $this->jobTitle->jobPosition->cfg_key;
        }

        return $job ?? '';
    }

    public function getOrgLastParentAttribute()
    {
        if (1 != $this->dept_id) {
            $departments = Org::leftJoin('configs as cfOrg', 'organizations.dept_type_id', '=', 'cfOrg.id')
                ->selectRaw(
                    'organizations.id,
        organizations.parent_id,
        CONCAT(cfOrg.cfg_key, " ",organizations.name) AS org_name'
                )
                ->orderBy('id')->get();

            $departmentsCollection = new Collection($departments);

            $deptId = Org::getLastParentId($this->dept_id, $departmentsCollection, 1);

            if (null === $deptId) {
                return null;
            }

            return $departmentsCollection->where('id', $deptId)->first();
        }

        return null;
    }
}
