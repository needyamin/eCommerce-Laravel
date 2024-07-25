<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CoreAuthSeeder extends Seeder
{
  
    public function run(): void
{
        // User::factory(10)->create();
        
        // Insert into core_roles table and get the inserted ID
        $check = DB::table('core_roles')->where('name', 'admin')->first();
        if (!$check){ 
        
            $roleId = DB::table('core_roles')->insertGetId([
            'name' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert into core_role_user table using the retrieved ID
        DB::table('core_role_user')->insert([
            'user_id' => 1,
            'role_id' => $roleId,
        ]);
     
      }

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
