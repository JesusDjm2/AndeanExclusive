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
        Schema::create('agente_programa', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('agente_id');
            $table->unsignedBigInteger('programa_id');

            // Llaves foráneas
            $table->foreign('agente_id')
                ->references('id')
                ->on('agentes')
                ->onDelete('cascade');

            $table->foreign('programa_id')
                ->references('id')
                ->on('programas')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agente_programa');
    }
};
