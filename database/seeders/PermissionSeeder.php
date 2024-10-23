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
        $permissions = [
            'view_product',
            'view_product_comments',
            'post_product',
            'edit_product_description',
            'unpublish_product',
            'add_comment',
            'delete_comment',
            'manage_users',
            'add_tag',
            'delete_tag',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], ['name' => $permission]); //找到對應權限做更新
        }
    }
}
