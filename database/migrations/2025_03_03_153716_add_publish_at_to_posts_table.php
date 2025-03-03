<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('posts', 'publish_at')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->timestamp('publish_at')->nullable()->useCurrent();
        });

        // Проставляем publish_at текущим значением created_at
        // Это нужно для согласованности данных в колонках
        DB::statement('UPDATE posts SET publish_at = created_at');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('posts', 'publish_at')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('publish_at');
        });
    }
};
