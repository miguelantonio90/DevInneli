<?php

/** @var Factory $factory */

use App\Position;
use Faker\Generator as Faker;

$factory->define(Position::class, function (Faker $faker) {
    return [
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
    ])
    ];
});
