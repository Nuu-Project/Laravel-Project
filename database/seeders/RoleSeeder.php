<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents; //避免觸發模型事件

    public function run(): void
    {

        Role::updateOrCreate(['name' => 'admin']);  //管理員

    }
}
