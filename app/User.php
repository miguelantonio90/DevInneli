<?php

namespace App;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @property mixed email
 * @property mixed|string password
 * @property mixed avatar
 * @property mixed position
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstName', 'lastName', 'email', 'password', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employer()
    {
        return $this->hasOne(Employee::class);
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class);
    }


    public function shops()
    {
        return $this->hasMany(Shop::class);
    }


    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }
}
