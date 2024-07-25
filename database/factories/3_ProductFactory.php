<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(EcommerceProduct::class, function (Faker $faker) {
    return [
        'vendor_id' => function () {
            return DB::table('ecommerce_vendors')->inRandomOrder()->first()->id;
        },
        'name' => $faker->word,
        'detail' => $faker->sentence,
        'sku' => $faker->unique()->ean13,
        'price' => $faker->randomFloat(2, 10, 1000),
        'actual_price' => $faker->randomFloat(2, 10, 1000),
        'offer_percentage' => $faker->randomFloat(2, 100),
        'stock_quantity' => $faker->numberBetween(1, 100),
        'is_active' => 1,
        'category_id' => function () {
            return DB::table('ecommerce_category')->inRandomOrder()->first()->id;
        },
        'brand' => $faker->company,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null,
    ];
});

$factory->afterCreatingState(EcommerceProduct::class, [], function ($product, $faker) {
    DB::table('ecommerce_products')->insert([
        'vendor_id' => $product['vendor_id'],
        'name' => $product['name'],
        'product_cover' => $product['product_cover'],
        'detail' => $product['detail'],
        'sku' => $product['sku'],
        'price' => $product['price'],
        'actual_price' => $product['actual_price'],
        'offer_percentage' => $product['offer_percentage'],
        'stock_quantity' => $product['stock_quantity'],
        'is_active' => $product['is_active'],
        'category_id' => $product['category_id'],
        'brand' => $product['brand'],
        'created_at' => $product['created_at'],
        'updated_at' => $product['updated_at'],
        'deleted_at' => $product['deleted_at'],
    ]);
});
