<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateReportablesAndCreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 創建 reports 表
        if (! Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('report_type_id')->constrained('report_types');
                $table->foreignId('user_id')->constrained('users');
                $table->string('description', 255);
                $table->timestamps();
            });
        }

        // 添加 report_id 欄位到 reportables 表
        Schema::table('reportables', function (Blueprint $table) {
            if (! Schema::hasColumn('reportables', 'report_id')) {
                $table->foreignId('report_id')->nullable()->constrained('reports');
            }
        });

        // 將現有資料從 reportables 移動到 reports
        $reportables = DB::table('reportables')->get();
        foreach ($reportables as $reportable) {
            $report_id = DB::table('reports')->insertGetId([
                'report_type_id' => $reportable->report_type_id,
                'user_id' => $reportable->user_id,
                'description' => $reportable->description,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('reportables')->where('id', $reportable->id)->update(['report_id' => $report_id]);
        }

        Schema::table('reportables', function (Blueprint $table) {
            if (Schema::hasColumn('reportables', 'report_type_id')) {
                $table->dropForeign(['report_type_id']);
            }

            if (Schema::hasColumn('reportables', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
        });

        // 移除舊的唯一約束（如果存在）
        Schema::table('reportables', function (Blueprint $table) {
            $uniqueConstraints = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_NAME = 'reportables'
                AND CONSTRAINT_TYPE = 'UNIQUE'
                AND CONSTRAINT_SCHEMA = DATABASE()
            ");

            foreach ($uniqueConstraints as $constraint) {
                if ($constraint->CONSTRAINT_NAME === 'reportables_unique_index') {
                    $table->dropUnique('reportables_unique_index');
                }
            }
        });

        // 添加新的唯一約束
        Schema::table('reportables', function (Blueprint $table) {
            $table->unique(['report_id', 'reportable_id', 'reportable_type']);
        });

        // 移除多餘的欄位，並將 report_id 設為必填
        Schema::table('reportables', function (Blueprint $table) {
            if (Schema::hasColumn('reportables', 'report_type_id')) {
                $table->dropColumn(['report_type_id']);
            }

            if (Schema::hasColumn('reportables', 'user_id')) {
                $table->dropColumn(['user_id']);
            }

            if (Schema::hasColumn('reportables', 'description')) {
                $table->dropColumn(['description']);
            }

            if (Schema::hasColumn('reportables', 'id')) {
                $table->dropColumn(['id']);
            }

            if (Schema::hasColumn('reportables', 'created_at')) {
                $table->dropColumn(['created_at']);
            }

            if (Schema::hasColumn('reportables', 'updated_at')) {
                $table->dropColumn(['updated_at']);
            }

            $table->foreignId('report_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 回滾變更並刪除外鍵和欄位（首先添加缺失的欄位）
        Schema::table('reportables', function (Blueprint $table) {
            if (! Schema::hasColumn('reportables', 'report_type_id')) {
                $table->foreignId('report_type_id')->nullable()->constrained('report_types');
            } else {
                if (count(DB::select("
                    SELECT CONSTRAINT_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_NAME = 'reportables'
                    AND COLUMN_NAME = 'report_type_id'
                    AND CONSTRAINT_SCHEMA = DATABASE()
                    AND REFERENCED_COLUMN_NAME IS NOT NULL
                ")) === 0) {
                    $table->foreign('report_type_id')->references('id')->on('report_types');
                }
            }

            if (! Schema::hasColumn('reportables', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users');
            } else {
                if (count(DB::select("
                    SELECT CONSTRAINT_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE TABLE_NAME = 'reportables'
                    AND COLUMN_NAME = 'user_id'
                    AND CONSTRAINT_SCHEMA = DATABASE()
                    AND REFERENCED_COLUMN_NAME IS NOT NULL
                ")) === 0) {
                    $table->foreign('user_id')->references('id')->on('users');
                }
            }

            if (! Schema::hasColumn('reportables', 'description')) {
                $table->string('description', 255);
            }

            if (! Schema::hasColumn('reportables', 'id')) {
                $table->id()->first();
            }

            if (! Schema::hasColumn('reportables', 'created_at')) {
                $table->timestamps();
            } else {
                $table->timestamp('created_at')->nullable(false)->change();
                $table->timestamp('updated_at')->nullable(false)->change();
            }
        });

        // 移動資料從 reports 回到 reportables
        $reports = DB::table('reports')->get();
        foreach ($reports as $report) {
            DB::table('reportables')->where('report_id', $report->id)->update([
                'report_type_id' => $report->report_type_id,
                'user_id' => $report->user_id,
                'description' => $report->description,
            ]);
        }

        // 回滾變更並刪除外鍵和欄位
        Schema::table('reportables', function (Blueprint $table) {
            if (Schema::hasColumn('reportables', 'report_id')) {
                $table->dropForeign(['report_id']);
                $table->dropColumn('report_id');
            }
        });

        // 刪除 reports 表
        if (Schema::hasTable('reports')) {
            Schema::dropIfExists('reports');
        }

        // 將外鍵設置為不能為空
        Schema::table('reportables', function (Blueprint $table) {
            $table->foreignId('report_type_id')->nullable(false)->change();
            $table->foreignId('user_id')->nullable(false)->change();
        });

        // 添加舊的唯一約束
        Schema::table('reportables', function (Blueprint $table) {
            $table->unique(['reportable_id', 'reportable_type', 'report_type_id', 'user_id'], 'reportables_unique_index');
        });
    }
}
