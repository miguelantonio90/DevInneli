<?php

use App\Payment;
use App\Company;
use Illuminate\Database\Seeder;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Payment::class, 1)->create()(function ($payment) {
            // Seed the relation with one address
            $company = factory(Company::class)->make();
            $payment->company()->save($company);
        });
    }
}
