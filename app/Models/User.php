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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $connection = 'db_dev';

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

    protected $appends = ['org_last_parent'];

    public function organization()
    {
        return $this->hasOne(Organization::class, 'id', 'dept_id');
    }

    public function getOrgLastParentAttribute()
    {
        if ($this->dept_id != 1) {
            $departments = Organization::leftJoin('configs as cfOrg', 'organizations.dept_type_id', '=', 'cfOrg.id')
                ->selectRaw(
                    'organizations.id, 
        organizations.parent_id, 
        CONCAT(cfOrg.cfg_key, " ",organizations.name) AS org_name'
                )
                ->orderBy('id')->get();

            $departmentsCollection = new Collection($departments);

            $deptId = Organization::getLastParentId($this->dept_id, $departmentsCollection, 1);

            return Organization::find($deptId);
        }

        return null;
    }
}
