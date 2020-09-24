<?php

use App\Company;
use App\User;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Company::class, 1)->create()->each(function ($company) {
            // Seed the relation with one address
            $user = factory(User::class)->make();
            $company->users()->save($user);
        });
    }
}
