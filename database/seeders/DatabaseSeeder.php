<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        if (app()->isLocal()) {
            $this->call([
                TagSeeder::class,
                ReportTypeSeeder::class,
                ProductSeeder::class,
            ]);
        }
    }
}
