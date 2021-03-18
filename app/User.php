<?php

namespace App;

use App\Notifications\MailResetPasswordNotification;
use App\Notifications\VerifyEmail;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Questocat\Referral\Traits\UserReferral;

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
    use SoftDeletes, Notifiable, HasApiTokens, Uuid, SoftCascadeTrait, UserReferral;


    public $incrementing = false;
    protected $dates = ['deleted_at'];
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


    protected $softCascade = ['assistances'];

    /**
     * @param $data
     * @param $company
     * @param $position
     * @return User
     */
    public static function createFirst($data, $company, $position): User
    {
        $user = new self();
        $user->firstName = 'MANAGER';
        $user->password = Hash::make($data['password']);
        $user->email = $data['email'];
        $user->pinCode = 000000;
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
    public function assistances(): HasMany
    {
        return $this->hasMany(Assistance::class);
    }

    /**
     *
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail());
    }

    /**
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    /**
     * A user has a referrer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo(self::class, 'referred_by', 'affiliate_id')->with('company');
    }

    /**
     * A user has many referrals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals()
    {
        return $this->hasMany(self::class, 'referred_by', 'affiliate_id')->with('company');
    }
}
