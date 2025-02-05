<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('reports', 'report_types');
    }

    public function down(): void
    {
        Schema::rename('report_types', 'reports');
    }
};
