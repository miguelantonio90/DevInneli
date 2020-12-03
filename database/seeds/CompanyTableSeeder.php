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
            'faker' => true,
        ]);

        $position = Position::create([
            'key' => 'admin',
            'name' => 'Super Admin',
            'access_permit' => json_encode(['all' => true,
                'article_list' => true, 'article_add' => true, 'article_edit' => true, 'article_delete' => true, 'article_transport' => true,
                'vending_list' => true, 'vending_add' => true, 'vending_edit' => true, 'vending_delete' => true,
                'category_list' => true, 'category_add' => true, 'category_edit' => true, 'category_delete' => true,
                'mod_list' => true, 'mod_add' => true, 'mod_edit' => true, 'mod_delete' => true,
                'supplier_list' => true, 'supplier_add' => true, 'supplier_edit' => true, 'supplier_delete' => true,
                'buy_list' => true, 'buy_add' => true, 'buy_edit' => true, 'buy_delete' => true,
                'sell_by_product' => true, 'sell_by_category' => true, 'sell_by_employer' => true, 'sell_by_payments' => true,
                'employer_list' => true, 'employer_add' => true, 'employer_edit' => true, 'employer_delete' => true,
                'assistance_list' => true, 'assistance_add' => true, 'assistance_edit' => true, 'assistance_delete' => true,
                'client_list' => true, 'client_add' => true, 'client_edit' => true, 'client_delete' => true,
                'config' => false,
                'shop_list' => true, 'shop_add' => true, 'shop_edit' => true, 'shop_delete' => true,
                'access_list' => true, 'access_add' => true, 'access_edit' => true, 'access_delete' => true,
                'payment_list' => true, 'payment_add' => true, 'payment_edit' => true, 'payment_delete' => true,
                'expense_category_list' => true, 'expense_category_add' => true, 'expense_category_edit' => true, 'expense_category_delete' => true,
                'exchange_rate_list' => true, 'exchange_rate_add' => true, 'exchange_rate_edit' => true, 'exchange_rate_delete' => true,
                'type_of_order_list' => true, 'type_of_order_add' => true, 'type_of_order_edit' => true, 'type_of_order_delete' => true,
                'tax_list' => true, 'tax_add' => true, 'tax_edit' => true, 'tax_delete' => true,
                'discount_list' => true, 'discount_add' => true, 'discount_edit' => true, 'discount_delete' => true,
            ]),
            'company_id' => $company->id
        ]);

        User::create([
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'isAdmin' => true,
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'company_id' => $company->id,
            'position_id' => $position->id,
        ]);
    }
}
