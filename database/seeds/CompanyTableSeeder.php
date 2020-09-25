<?php

use App\Company;
use App\Position;
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
            $pos = factory(Position::class)->make();
            $company->users()->save($pos);
        });
    }
}
