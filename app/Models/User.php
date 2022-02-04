<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey='user_id';
    protected $table='users';
    protected $fillable = [

        'first_name',
        'middle_name',
        'last_name',
        'mobile_number',
        'student_number',
        'course_id',
        'year_and_section',
        'role_id',
        'email',
        'password',
        'date_of_birth',
        'suffix',
        'title',
        'address',
        'status',
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')->withPivot('organization_id');
    }

    /**
     * check if the user has the role
     * @param string role
     * @return bool
     */
    public function hasAnyRole(String $role){

        return null !== $this->roles()->where('role', $role)->first();
    }

    /**
     * check if the user has any given role
     * @param array role
     * @return bool
     */
    public function hasAnyRoles(Array $role){

        return null !== $this->roles()->whereIn('role', $role)->first();
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id');
    }

    public function signature(){
        
        return $this->hasOne(event_signatures::class, 'signature_id');

    }

    public function event(){
        return $this->belongsTo(Disapproved_events::class, 'disapproved_by', 'user_id');
     }
}
