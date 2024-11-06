<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminUser = User::firstOrCreate(
            ['email' => 'u1133100@o365.nuu.edu.tw'],
            ['name' => 'Admin User',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now()]
        );
        $adminUser->assignRole($adminRole);

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], ['name' => $permission]); //找到對應權限做更新
        }
    }
}
