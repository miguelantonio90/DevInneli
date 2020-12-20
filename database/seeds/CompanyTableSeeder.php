<?php

use App\Company;
use App\KeyPosition;
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
     * @param Faker $faker
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
            'sector' =>'others',
            'currency' => $this->faker->currencyCode,
            'address' => $this->faker->address,
            'faker' => true,
        ]);
        $position = Position::create([
            'name' => 'admin',
            'access_permit' => json_encode([
                [
                    'title' => ['name' => 'dashboard', 'value' => true],
                    'actions' => ['dashboard' => true]
                ],
                [
                    'title' => ['name' => 'manager_article', 'value' => true],
                    'actions' => ['article_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_vending', 'value' => true],
                    'actions' => ['vending_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_boxes', 'value' => true],
                    'actions' => ['boxes_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_category', 'value' => true],
                    'actions' => ['category_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_mod', 'value' => true],
                    'actions' => ['mod_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_supplier', 'value' => true],
                    'actions' => ['supplier_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_buy', 'value' => true],
                    'actions' => ['buy_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_sell', 'value' => true],
                    'actions' =>
                        ['sell_by_product' => true, 'sell_by_category' => true, 'sell_by_employer' => true, 'sell_by_payments' => true]
                ],
                [
                    'title' => ['name' => 'manager_employer', 'value' => true],
                    'actions' => ['employer_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_assistence', 'value' => true],
                    'actions' => ['assistance_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_client', 'value' => true],
                    'actions' => ['client_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_shop', 'value' => true],
                    'actions' => ['shop_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_key', 'value' => true],
                    'actions' => ['key_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_access', 'value' => true],
                    'actions' => ['access_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_payment', 'value' => true],
                    'actions' => ['payment_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_expense_category', 'value' => true],
                    'actions' => ['expense_category_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_exchage_rate', 'value' => true],
                    'actions' => ['exchange_rate_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_type_of_order', 'value' => true],
                    'actions' => ['type_of_order_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_tax', 'value' => true],
                    'actions' => ['tax_list' => true]
                ],
                [
                    'title' => ['name' => 'manager_discount', 'value' => true],
                    'actions' => ['discount_list' => true]
                ]
            ])
        ]);
        User::create([
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'isAdmin' => true,
            'isLoginPin' => true,
            'pinCode' => 123456,
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'company_id' => $company->id,
            'position_id' => $position->id,
        ]);
    }
}
