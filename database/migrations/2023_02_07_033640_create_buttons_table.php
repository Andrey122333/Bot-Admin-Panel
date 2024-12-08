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
        Schema::create('buttons', function (Blueprint $table) {
            $table->id();
            $table->string("name")->default('');
            $table->boolean("keyboard_button")->default(false);
            $table->string("nesting");
            $table->string("nesting_down")->default('none');
            $table->string("class")->default('');
            $table->string("keyboard")->default('');
            $table->integer("horizontal")->default(0);
            $table->integer("vertical")->default(0);
            $table->timestamps();
            $table->index(['keyboard_button', 'nesting'], 'idx_keyboard_button_nesting');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buttons');
    }
};
