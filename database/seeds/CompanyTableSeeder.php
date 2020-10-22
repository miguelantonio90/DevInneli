<?php

use App\Company;
use App\Position;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanyTableSeeder extends Seeder
{
    protected $faker;

    /**
     * CompanyTableSeeder constructor.
     * @param  Faker  $faker
     */
    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create([
            'name' => $this->faker->company,
            'phone' => $this->faker->phoneNumber,
            'email' => 'admin@admin.com',
            'logo' => '',
            'country' => $this->faker->countryCode,
            'currency' => $this->faker->currencyCode,
            'address' => $this->faker->address,
            'faker' => 1,
        ]);

        $position = Position::create([
            'key' => 'admin',
            'name' => 'Super Admin',
            'company_id' => $company->id
        ]);

        User::create([
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'isAdmin' => 1,
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'company_id' => $company->id,
            'position_id' => $position->id,
        ]);
    }
}
