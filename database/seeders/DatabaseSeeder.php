<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Insert data into ecommerce_vendors table (10 times)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('ecommerce_vendors')->insert([
                'name' => 'Vendor Name ' . $i,
                'shop_name' => 'Shop Name ' . $i,
                'shop_owner_name' => 'Owner Name ' . $i,
                'email' => 'vendor' . $i . '@example.com',
                'phone' => '123456789' . $i,
                'address' => 'Address ' . $i,
                'nid' => '123456789012' . $i,
                'shop_certificate' => 'Cert' . $i,
                'shop_location' => 'Location ' . $i,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        // Insert data into ecommerce_category table (10 times)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('ecommerce_category')->insert([
                'category_name' => 'Category ' . $i,
                'category_description' => 'Description ' . $i,
                'category_image' => 'https://via.placeholder.com/150',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Insert data into ecommerce_products table (10 times)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('ecommerce_products')->insert([
                'vendor_id' => $i,
                'product_name' => 'Product ' . $i,
                'product_cover'=> 'Cover ' . $i,
                'product_detail' => 'Product detail ' . $i,
                'sku' => 'SKU' . $i,
                'price' => 100.50 + ($i * 10),
                'stock_quantity' => 10 * $i,
                'is_active' => 1,
                'category_id' => $i,
                'brand' => 'Brand ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        User::factory()->create([
            'name' => 'Yamin',
            'email' => 'needyamin@gmail.com',
            'password' => Hash::make('needyamin@gmail.com'),
            'role' => 1,
            'phone'=> '01878578504',
            'extra_phone' => '01712573270',
        ]);

        // Status
        DB::table('core_status')->insert([
            // Status
            ['description' => 'pending', 'code' => 0, 'type' => 'status', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'approved', 'code' => 1, 'type' => 'status', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'block', 'code' => 2, 'type' => 'status', 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'suspend', 'code' => 3, 'type' => 'status', 'created_at' => now(), 'updated_at' => now()],
        
        
        ]);
        

    }
}
