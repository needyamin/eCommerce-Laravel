<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(EcommerceVendor::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'shop_name' => $faker->company,
        'shop_owner_name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'nid' => $faker->numerify('###########'),
        'shop_certificate' => $faker->uuid,
        'shop_location' => $faker->address,
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

$factory->afterCreatingState(EcommerceVendor::class, [], function ($vendor, $faker) {
    DB::table('ecommerce_vendors')->insert([
        'name' => $vendor['name'],
        'shop_name' => $vendor['shop_name'],
        'shop_owner_name' => $vendor['shop_owner_name'],
        'email' => $vendor['email'],
        'phone' => $vendor['phone'],
        'address' => $vendor['address'],
        'nid' => $vendor['nid'],
        'shop_certificate' => $vendor['shop_certificate'],
        'shop_location' => $vendor['shop_location'],
        'created_at' => $vendor['created_at'],
        'updated_at' => $vendor['updated_at'],
    ]);
});
