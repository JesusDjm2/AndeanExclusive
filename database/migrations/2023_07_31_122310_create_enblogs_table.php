<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnblogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enblogs', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('imgThumb');
            $table->string('imgFull');
            $table->text('descripcionCorta');
            $table->text('descripcion');
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
        Schema::dropIfExists('enblogs');
    }
}
