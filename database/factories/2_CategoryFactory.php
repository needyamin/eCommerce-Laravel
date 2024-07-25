<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(EcommerceCategory::class, function (Faker $faker) {
    return [
        'category_name' => $faker->word,
        'category_description' => $faker->sentence,
        'category_image' => $faker->imageUrl(640, 480, 'categories', true),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->afterCreatingState(EcommerceCategory::class, [], function ($category, $faker) {
    DB::table('ecommerce_category')->insert([
        'category_name' => $category['category_name'],
        'category_description' => $category['category_description'],
        'category_image' => $category['category_image'],
        'created_at' => $category['created_at'],
        'updated_at' => $category['updated_at'],
    ]);
});
