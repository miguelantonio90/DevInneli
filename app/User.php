<?php

namespace App;

use App\Notifications\MailResetPasswordNotification;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

/**
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
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
        'name', 'email', 'password', 'username'
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
        return $this->belongsToMany(Shop::class);
    }


    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    /**
     * @return mixed
     */
    public static function getUsers()
    {
        return DB::table('users')
            ->select(
                'users.*',
                'shops.*',
                'employees.phone',
                'employees.pinCode',
                'positions.key as role',
                'positions.name as position'
            )
            ->join('employees', 'users.id', '=', 'employees.user_id')
            ->join('position_user', 'users.id', '=', 'position_user.user_id')
            ->join('positions', 'positions.id', '=', 'position_user.position_id')
            ->join('shops', 'users.id', '=', 'shops.user_id')
            ->get();
    }
}
