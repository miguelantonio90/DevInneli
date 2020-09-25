<?php

namespace App;

use App\Notifications\MailResetPasswordNotification;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

/**
 * @method static findOrFail($id)
 * @method static latest()
 * @method find($id)
 * @method static create(array $array)
 * @method static select(string $string, $raw)
 * @property mixed email
 * @property mixed firstName
 * @property mixed lastName
 * @property mixed employeeEmail
 * @property mixed pinCode
 * @property mixed phone
 * @property mixed isAdmin
 * @property mixed isManager
 * @property mixed company_id
 * @property mixed|string password
 * @property mixed avatar
 * @property mixed position_id
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
        'firstName', 'email', 'employeeEmail'
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
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

    public static function createFirst($data, $company, $position): User
    {
        $user = new User();
        $user->firstName = 'MANAGER';
        $user->password = Hash::make($data['password']);
        $user->email = $data['email'];
        $user->employeeEmail = $data['email'];
        $user->pinCode = 1234;
        $user->isAdmin = 0;
        $user->isManager = 1;
        $user->company_id = $company->id;
        $user->position_id = $position->id;
        $user->save();

        return $user;
    }
}
