<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEsblogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('esblogs', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('imgThumb');
            $table->string('imgFull');
            $table->text('descripcionCorta');
            $table->text('descripcion');
            $table->string('slug');
            $table->string('keywords');
            $table->unsignedBigInteger('relacionado_id')->nullable();
            $table->foreign('relacionado_id')->references('id')->on('enblogs') ->onDelete('cascade');
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
        Schema::dropIfExists('esblogs');
    }
}
