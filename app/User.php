<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','activation_token'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function department()
    {
        return $this->hasOne('App\Department');
    }

    public function getNameAttribute()
    {
        return $this->Profile->first_name . ' ' . $this->Profile->last_name;
    }

    public function getNameWithEmailAttribute()
    {
        return $this->Profile->first_name . ' ' . $this->Profile->last_name . ' (' . $this->email . ')';
    }

    public function scopeFilterByEmail($q, $email = null)
    {
        if (!$email) {
            return $q;
        }

        return $q->where('email', 'like', '%'.$email.'%');
    }

    public function scopeFilterByName($q, $name = null)
    {
        if (!$name) {
            return $q;
        }

        return $q->whereHas('profile', function ($q1) use ($name) {
            $q1->where(function($q2) use($name) {
                $q2->where('first_name', 'like', '%'.$name.'%')->orWhere('last_name', 'like', '%'.$name.'%');
            });
        });
    }

    public function scopeFilterByStatus($q, $status = null)
    {
        if (!$status) {
            return $q;
        }

        return $q->where('status', '=', $status);
    }

    public function scopeCreatedAtDateBetween($q, $dates)
    {
        if ((!$dates['start_date'] || !$dates['end_date']) && $dates['start_date'] <= $dates['end_date']) {
            return $q;
        }

        return $q->where('created_at', '>=', getStartOfDate($dates['start_date']))->where('created_at', '<=', getEndOfDate($dates['end_date']));
    }
}
