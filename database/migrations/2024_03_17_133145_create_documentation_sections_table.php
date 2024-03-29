<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documentation_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title_page')->comment('Заголовок всей страницы');
            $table->string('title');
            $table->integer('level')->nullable()->comment('Уровень заголовка');
            $table->string('slug');
            $table->string('version');
            $table->string('file');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_sections');
    }
};
