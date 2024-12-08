<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeoPositionsTable extends Migration
{
    public function up()
    {
        Schema::create('geo_positions', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->double('latitude');
            $table->double('longitude');
            $table->string('route_description', 4096);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('geo_positions');
    }
}