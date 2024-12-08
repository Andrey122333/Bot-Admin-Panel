<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * number_applications
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("telegram_applications_count");
            $table->unsignedBigInteger("web_applications_per_page");
            $table->string("message");
            $table->string("ban_keyboard");
            $table->string("token");
            $table->string("telegram_locked_account_button_text");
            $table->string("telegram_technical_work_message", 4096);
            $table->string("telegram_default_locked_account_reason", 4096);
            $table->timestamps();
        });

        DB::table('settings')->insert([
            'name' => 'none',
            'token' => 'none',
            'message' => 'Приветствую в меню бота',
            'web_applications_per_page' => 25,
            'telegram_applications_count' => 1,
            'telegram_technical_work_message' => 'Извините, выбранная Вами услуга временно недоступна из-за технических работ или изменений. Мы работаем над восстановлением доступа/обновлением информации. Спасибо за понимание.',
            'telegram_locked_account_button_text' => 'Ваш аккаунт временно заблокирован',
            'telegram_default_locked_account_reason' => 'К сожалению, мы вынуждены сообщить, что Ваша учетная запись временно заблокирована из-за нарушения правил нашего сервиса. Мы просим Вас ознакомиться с нашими правилами использования, чтобы избежать подобных ситуаций в будущем.',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};

