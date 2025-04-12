<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents; // 避免觸發模型事件

    public function run(): void
    {
        foreach (RoleType::cases() as $role) {
            Role::updateOrCreate(
                ['name' => $role->value],
                ['guard_name' => 'web']
            );
        }
    }
}
