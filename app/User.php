<?php

namespace App;

use App\Notifications\MailResetPasswordNotification;
use App\Notifications\VerifyEmail;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @method static where(string $string, string $string1, string $string2)
 * @property mixed email
 * @property mixed firstName
 * @property mixed lastName
 * @property mixed pinCode
 * @property mixed phone
 * @property mixed country
 * @property mixed isAdmin
 * @property mixed isManager
 * @property mixed company_id
 * @property mixed|string password
 * @property mixed avatar
 * @property mixed position_id
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens, Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstName', 'email', 'company_id', 'position_id'
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

    /**
     * @param $data
     * @param $company
     * @param $position
     * @return User
     */
    public static function createFirst($data, $company, $position): User
    {
        $user = new User();
        $user->firstName = 'MANAGER';
        $user->password = Hash::make($data['password']);
        $user->email = $data['email'];
        $user->pinCode = rand(1000, 9999);
        $user->country = $data['country']['id'];
        $user->isAdmin = 0;
        $user->isManager = 1;
        $user->company_id = $company->id;
        $user->position_id = $position->id;
        $user->save();

        return $user;
    }

    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * @return BelongsToMany
     */
    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class);
    }

    /**
     * @return HasMany
     */
    public function assistances()
    {
        return $this->hasMany(Assistance::class);
    }

    /**
     *
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    /**
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }
}
