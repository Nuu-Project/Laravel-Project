<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleHasPermissionsSeeder extends Seeder
{

    use WithoutModelEvents;

    public function run(): void
    {
        $permissions = [                         // 對應權限等級
            1 => 'view_product',                 // 查看商品
            2 => 'view_product_comments',        // 查看商品評論
            3 => 'post_product',                 // 刊登商品
            4 => 'edit_product_description',     // 編輯商品描述
            5 => 'unpublish_product',            // 下架商品
            6 => 'add_comment',                  // 新增評論
            7 => 'delete_comment',               // 刪除評論
            8 => 'manage_users',                 // 管理用戶
            9 => 'add_tag',                      // 新增標籤
            10 => 'delete_tag'                    // 刪除標籤
        ];
        
        $roles = [
            1 => 'visitor',
            2 => 'user',
            3 => 'admin'
        ];
        
        $roles1 = Role::findByName($roles[1]);
        $p1 = Permission::where('name', $permissions[1])->first();
        $roles1->givePermissionTo($p1);


        $permission2 = [
            $permissions[1],
            $permissions[2],
            $permissions[3],
            $permissions[4],
            $permissions[5],
            $permissions[6]
        ];

        $roles2 = Role::findByName($roles[2]);

        foreach($permission2 as $p2){
            $p2 = Permission::where('name', $p2)->first(); //找到權限名稱
            $roles2->givePermissionTo($p2);
        }


        $roles3 = Role::findByName($roles[3]);

        foreach($permissions as $p3){
            $permissions = Permission::where('name', $p3)->first(); //找到權限名稱
            $roles3->givePermissionTo($permissions);
        }

    }
}
