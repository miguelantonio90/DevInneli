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
        $key->access_permit = json_encode(['all' => true,
            json_encode([
                'title' => json_encode(['name' => 'manager_article', 'value' => false]),
                'actions' => json_encode(['article_list' => true, 'article_add' => false, 'article_edit' => false, 'article_delete' => false, 'article_transport' => false])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_vending', 'value' => false]),
                'actions' => json_encode(['vending_list' => true, 'vending_add' => false, 'vending_edit' => false, 'vending_delete' => false,])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_category', 'value' => false]),
                'actions' => json_encode(['category_list' => true, 'category_add' => false, 'category_edit' => false, 'category_delete' => false,])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_mod', 'value' => false]),
                'actions' => json_encode(['mod_list' => true, 'mod_add' => false, 'mod_edit' => false, 'mod_delete' => false])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_supplier', 'value' => false]),
                'actions' => json_encode(['supplier_list' => true, 'supplier_add' => false, 'supplier_edit' => false, 'supplier_delete' => false])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_buy', 'value' => false]),
                'actions' => json_encode(['buy_list' => true, 'buy_add' => false, 'buy_edit' => false, 'buy_delete' => false])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_sell', 'value' => false]),
                'actions' =>
                    json_encode(['sell_by_product' => true, 'sell_by_category' => false, 'sell_by_employer' => false, 'sell_by_payments' => false])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_employer', 'value' => false]),
                'actions' => json_encode(['employer_list' => true, 'employer_add' => false, 'employer_edit' => false, 'employer_delete' => false])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_assistence', 'value' => false]),
                'actions' => json_encode(['assistance_list' => true, 'assistance_add' => false, 'assistance_edit' => false, 'assistance_delete' => false])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_client', 'value' => false]),
                'actions' => json_encode(['client_list' => true, 'client_add' => false, 'client_edit' => false, 'client_delete' => false])
            ]),
            'config' => true,
            json_encode([
                'title' => json_encode(['name' => 'manager_shop', 'value' => false]),
                'actions' => json_encode(['shop_list' => true, 'shop_add' => true, 'shop_edit' => true, 'shop_delete' => true])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_access', 'value' => false]),
                'actions' => json_encode(['access_list' => true, 'access_add' => true, 'access_edit' => true, 'access_delete' => true])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_payment', 'value' => false]),
                'actions' => json_encode(['payment_list' => true, 'payment_add' => true, 'payment_edit' => true, 'payment_delete' => true])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_expense_category', 'value' => false]),
                'actions' => json_encode(['expense_category_list' => true, 'expense_category_add' => true, 'expense_category_edit' => true, 'expense_category_delete' => true])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_exchage_rate', 'value' => false]),
                'actions' => json_encode(['exchange_rate_list' => true, 'exchange_rate_add' => true, 'exchange_rate_edit' => true, 'exchange_rate_delete' => true])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_type_of_order', 'value' => false]),
                'actions' => json_encode(['type_of_order_list' => true, 'type_of_order_add' => true, 'type_of_order_edit' => true, 'type_of_order_delete' => true])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_tax', 'value' => false]),
                'actions' => json_encode(['tax_list' => true, 'tax_add' => true, 'tax_edit' => true, 'tax_delete' => true])
            ]),
            json_encode([
                'title' => json_encode(['name' => 'manager_discount', 'value' => false]),
                'actions' => json_encode(['discount_list' => true, 'discount_add' => true, 'discount_edit' => true, 'discount_delete' => true])
            ])
        ]);
        $key->save();
        $position = Position::create([
            'key_position_id' => $key->id,
            'name' => 'Super Admin',
            'access_permit' => json_encode(['all' => true,
                json_encode([
                    'title' => json_encode(['name' => 'manager_article', 'value' => false]),
                    'actions' => json_encode(['article_list' => true, 'article_add' => false, 'article_edit' => false, 'article_delete' => false, 'article_transport' => false])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_vending', 'value' => false]),
                    'actions' => json_encode(['vending_list' => true, 'vending_add' => false, 'vending_edit' => false, 'vending_delete' => false,])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_category', 'value' => false]),
                    'actions' => json_encode(['category_list' => true, 'category_add' => false, 'category_edit' => false, 'category_delete' => false,])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_mod', 'value' => false]),
                    'actions' => json_encode(['mod_list' => true, 'mod_add' => false, 'mod_edit' => false, 'mod_delete' => false])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_supplier', 'value' => false]),
                    'actions' => json_encode(['supplier_list' => true, 'supplier_add' => false, 'supplier_edit' => false, 'supplier_delete' => false])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_buy', 'value' => false]),
                    'actions' => json_encode(['buy_list' => true, 'buy_add' => false, 'buy_edit' => false, 'buy_delete' => false])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_sell', 'value' => false]),
                    'actions' =>
                        json_encode(['sell_by_product' => true, 'sell_by_category' => false, 'sell_by_employer' => false, 'sell_by_payments' => false])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_employer', 'value' => false]),
                    'actions' => json_encode(['employer_list' => true, 'employer_add' => false, 'employer_edit' => false, 'employer_delete' => false])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_assistence', 'value' => false]),
                    'actions' => json_encode(['assistance_list' => true, 'assistance_add' => false, 'assistance_edit' => false, 'assistance_delete' => false])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_client', 'value' => false]),
                    'actions' => json_encode(['client_list' => true, 'client_add' => false, 'client_edit' => false, 'client_delete' => false])
                ]),
                'config' => true,
                json_encode([
                    'title' => json_encode(['name' => 'manager_shop', 'value' => false]),
                    'actions' => json_encode(['shop_list' => true, 'shop_add' => true, 'shop_edit' => true, 'shop_delete' => true])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_access', 'value' => false]),
                    'actions' => json_encode(['access_list' => true, 'access_add' => true, 'access_edit' => true, 'access_delete' => true])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_payment', 'value' => false]),
                    'actions' => json_encode(['payment_list' => true, 'payment_add' => true, 'payment_edit' => true, 'payment_delete' => true])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_expense_category', 'value' => false]),
                    'actions' => json_encode(['expense_category_list' => true, 'expense_category_add' => true, 'expense_category_edit' => true, 'expense_category_delete' => true])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_exchage_rate', 'value' => false]),
                    'actions' => json_encode(['exchange_rate_list' => true, 'exchange_rate_add' => true, 'exchange_rate_edit' => true, 'exchange_rate_delete' => true])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_type_of_order', 'value' => false]),
                    'actions' => json_encode(['type_of_order_list' => true, 'type_of_order_add' => true, 'type_of_order_edit' => true, 'type_of_order_delete' => true])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_tax', 'value' => false]),
                    'actions' => json_encode(['tax_list' => true, 'tax_add' => true, 'tax_edit' => true, 'tax_delete' => true])
                ]),
                json_encode([
                    'title' => json_encode(['name' => 'manager_discount', 'value' => false]),
                    'actions' => json_encode(['discount_list' => true, 'discount_add' => true, 'discount_edit' => true, 'discount_delete' => true])
                ])
            ]),
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
