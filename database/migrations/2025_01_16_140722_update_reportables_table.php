<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reportables', function (Blueprint $table) {
            $table->renameColumn('whistleblower_id', 'user_id');
            $table->foreignId('user_id')->change()->constrained()->cascadeOnDelete();
            $table->string('description', 255)->change();
        });
    }

    public function down(): void
    {
        Schema::table('reportables', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'whistleblower_id');
            $table->unsignedBigInteger('whistleblower_id')->change();
            $table->text('description')->change();
        });
    }
};
