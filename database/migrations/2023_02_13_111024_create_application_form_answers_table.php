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
        Schema::create('application_form_answers', function (Blueprint $table) {
            $table->id();
            $table->text("question");
            $table->text("answer");
            $table->unsignedBigInteger("application_form_id");
            //$table->foreign('application_form_id')->references('id')->on('application_forms');
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
        Schema::dropIfExists('application_form_answers');
    }
};
