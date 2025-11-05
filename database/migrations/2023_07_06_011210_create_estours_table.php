<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estours', function (Blueprint $table) {
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
            $table->unsignedBigInteger('relacionado_id')->nullable();
            $table->foreign('relacionado_id')->references('id')->on('tours') ->onDelete('cascade');
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
        Schema::dropIfExists('estours');
    }
}
