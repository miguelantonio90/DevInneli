<?php

namespace App\Providers;

use App\Passport\Client;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        Passport::ignoreMigrations();
        Passport::useClientModel(Client::class);
    }
}
