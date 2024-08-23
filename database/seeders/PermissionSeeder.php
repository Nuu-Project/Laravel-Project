<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{

    use WithoutModelEvents;

    public function run(): void
    {
        Permission::create(['name' => 'view_product']);
        Permission::create(['name' => 'view_product_comments']);
        Permission::create(['name' => 'post_product']);
        Permission::create(['name' => 'edit_product_description']);
        Permission::create(['name' => 'unpublish_product']);
        Permission::create(['name' => 'add_comment']);
        Permission::create(['name' => 'delete_comment']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'add_tag']);
        Permission::create(['name' => 'delete_tag']);
    }
}
