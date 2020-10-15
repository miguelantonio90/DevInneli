<?php

namespace App\Passport;


use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Laravel\Passport\Client as OAuthClient;

class Client extends OAuthClient
{
    use Uuid;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
