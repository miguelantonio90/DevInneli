<?php
/** @var Factory $factory */

use App\Company;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'phone' => $faker->phoneNumber,
        'email' => 'company@admin.com',
        'country' => $faker->countryCode,
        'address' => $faker->address,
        'faker' => 1,
    ];
});
