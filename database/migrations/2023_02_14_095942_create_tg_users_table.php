<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tg_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string("name");
            $table->string("username");
            $table->string("first_name");
            $table->string("last_name");
            $table->string("language_code");
            $table->boolean("is_premium")->default(false);
            $table->string("role")->default("user");
            $table->string("nesting");
            $table->unsignedBigInteger("ban_id");
            $table->dateTime("ban_time");
            $table->string("action")->nullable(false)->default("nesting");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tg_users');
    }
};
