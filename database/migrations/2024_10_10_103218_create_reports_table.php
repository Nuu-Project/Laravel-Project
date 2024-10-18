<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('type')->nullable();
            $table->timestamps();
        });

        Schema::create('reportables', function (Blueprint $table) {
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->morphs('reportable'); 
            $table->unsignedBigInteger('whistleblower_id')->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->timestamps();

            $table->unique(['report_id', 'reportable_id', 'reportable_type','whistleblower_id'],'reportables_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('reportables');
    }
};
