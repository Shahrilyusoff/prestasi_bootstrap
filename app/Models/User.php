<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type_id',
        'pyd_group_id',
        'position',
        'grade',
        'ministry_department',
        'ic_number'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    public function pydGroup()
    {
        return $this->belongsTo(PydGroup::class);
    }

    public function pppEvaluations()
    {
        return $this->hasMany(Evaluation::class, 'ppp_id');
    }

    public function ppkEvaluations()
    {
        return $this->hasMany(Evaluation::class, 'ppk_id');
    }

    public function pydEvaluations()
    {
        return $this->hasMany(Evaluation::class, 'pyd_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isSuperAdmin()
    {
        return $this->userType && $this->userType->name === 'Super Admin';
    }

    public function isAdmin()
    {
        return $this->userType && $this->userType->name === 'Admin';
    }

    public function isPPP()
    {
        return $this->userType && $this->userType->name === 'PPP';
    }

    public function isPPK()
    {
        return $this->userType && $this->userType->name === 'PPK';
    }

    public function isPYD()
    {
        return $this->userType && $this->userType->name === 'PYD';
    }
}