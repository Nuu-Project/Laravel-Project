<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ReportSeeder::class,
            RoleSeeder::class,
            TagSeeder::class,
        ]);

        if (! app()->isProduction()) {
            $this->call(ProductSeeder::class);
        }
    }
}
