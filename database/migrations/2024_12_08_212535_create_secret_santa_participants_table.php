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
        Schema::create('secret_santa_participants', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade')
                ->comment('Идентификатор пользователя (отправителя), который участвует в Тайном Санте');

            $table->text('address')
                ->comment('Адрес участника для отправки подарка');

            $table->string('telegram')
                ->nullable()
                ->comment('Телеграм участника для связи');

            $table->string('phone')
                ->comment('Контактные данные участника для связи');

            $table->text('about')
                ->comment('Информация о пользователе, которую он предоставляет о себе для получателя');

            $table->foreignId('receiver_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->comment('Идентификатор пользователя (получателя) из таблицы пользователей');

            $table->string('tracking_number')
                ->comment('Номер отслеживания посылки')
                ->nullable();

            $table->string('status')
                ->comment('Статус участника в Тайном Санте')
                ->default('new');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secret_santa_participants');
    }
};
