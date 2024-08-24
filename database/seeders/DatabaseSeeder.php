<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        //插入數據測試用
        // User::factory(10)->create();
        
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);              

        $this->call([                   
            PermissionSeeder::class,        //確保先運行前兩個Seeder
            RoleSeeder::class,              
            RoleHasPermissionsSeeder::class,    
        ]);

    }
}
