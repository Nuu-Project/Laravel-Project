<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reportables', function (Blueprint $table) {
            $table->dropForeign(['report_id']);
            $table->renameColumn('report_id', 'report_type_id');
            $table->foreign('report_type_id')->references('id')->on('report_types');
        });
    }

    public function down(): void
    {
        Schema::table('reportables', function (Blueprint $table) {
            $table->dropForeign(['report_type_id']);
            $table->renameColumn('report_type_id', 'report_id');
            $table->foreign('report_id')->references('id')->on('report_types');
        });
    }
};
