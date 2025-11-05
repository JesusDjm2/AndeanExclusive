<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('recorrido');
            $table->integer('dias');
            $table->string('precio');
            $table->string('imgThumb');
            $table->string('imgFull');
            $table->text('descripcionCorta');
            $table->text('presentacion');
            $table->text('itinerario');
            $table->text('incluye');
            $table->string('mapa');
            $table->text('importante');
            $table->string('slug');
            $table->string('keywords');
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
        Schema::dropIfExists('tours');
    }
}
