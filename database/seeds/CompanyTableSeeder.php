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
            'currency' => $this->faker->currencyCode,
            'address' => $this->faker->address,
            'faker' => true,
        ]);
        $key = new KeyPosition();
        $key->key = 'admin';
        $key->access_permit = json_encode([
            [
                'title' => ['name' =>  'all', 'value' => true],
                'actions' => []
            ],
            [
                'title' => ['name' => 'manager_article', 'value' => false],
                'actions' => ['article_list' => true, 'article_add' => false, 'article_edit' => false, 'article_delete' => false, 'article_transport' => false]
            ],
            [
                'title' => ['name' => 'manager_vending', 'value' => false],
                'actions' => ['vending_list' => true, 'vending_add' => false, 'vending_edit' => false, 'vending_delete' => false,]
            ],
            [
                'title' => ['name' => 'manager_category', 'value' => false],
                'actions' => ['category_list' => true, 'category_add' => false, 'category_edit' => false, 'category_delete' => false,]
            ],
            [
                'title' => ['name' => 'manager_mod', 'value' => false],
                'actions' => ['mod_list' => true, 'mod_add' => false, 'mod_edit' => false, 'mod_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_supplier', 'value' => false],
                'actions' => ['supplier_list' => true, 'supplier_add' => false, 'supplier_edit' => false, 'supplier_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_buy', 'value' => false],
                'actions' => ['buy_list' => true, 'buy_add' => false, 'buy_edit' => false, 'buy_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_sell', 'value' => false],
                'actions' =>
                    ['sell_by_product' => true, 'sell_by_category' => false, 'sell_by_employer' => false, 'sell_by_payments' => false]
            ],
            [
                'title' => ['name' => 'manager_employer', 'value' => false],
                'actions' => ['employer_list' => true, 'employer_add' => false, 'employer_edit' => false, 'employer_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_assistence', 'value' => false],
                'actions' => ['assistance_list' => true, 'assistance_add' => false, 'assistance_edit' => false, 'assistance_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_client', 'value' => false],
                'actions' => ['client_list' => true, 'client_add' => false, 'client_edit' => false, 'client_delete' => false]
            ],
            [
                'title' => ['name' =>  'config', 'value' => true],
                'actions' => []
            ],
            [
                'title' => ['name' => 'manager_shop', 'value' => false],
                'actions' => ['shop_list' => true, 'shop_add' => false, 'shop_edit' => false, 'shop_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_access', 'value' => false],
                'actions' => ['access_list' => true, 'access_add' => false, 'access_edit' => false, 'access_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_payment', 'value' => false],
                'actions' => ['payment_list' => true, 'payment_add' => false, 'payment_edit' => false, 'payment_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_expense_category', 'value' => false],
                'actions' => ['expense_category_list' => true, 'expense_category_add' => false, 'expense_category_edit' => false, 'expense_category_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_exchage_rate', 'value' => false],
                'actions' => ['exchange_rate_list' => true, 'exchange_rate_add' => false, 'exchange_rate_edit' => false, 'exchange_rate_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_type_of_order', 'value' => false],
                'actions' => ['type_of_order_list' => true, 'type_of_order_add' => false, 'type_of_order_edit' => false, 'type_of_order_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_tax', 'value' => false],
                'actions' => ['tax_list' => true, 'tax_add' => false, 'tax_edit' => false, 'tax_delete' => false]
            ],
            [
                'title' => ['name' => 'manager_discount', 'value' => false],
                'actions' => ['discount_list' => true, 'discount_add' => false, 'discount_edit' => false, 'discount_delete' => false]
            ]
        ]);
        $key->save();
        $position = Position::create([
            'key_position_id' => $key->id,
            'name' => $key->key,
            'access_permit' => $key->access_permit,
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
