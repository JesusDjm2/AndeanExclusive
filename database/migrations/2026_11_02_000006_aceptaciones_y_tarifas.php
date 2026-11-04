<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Constancia de lo que el cliente aceptó: nombre, momento y la propuesta tal como estaba.
        Schema::create('aceptaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('ip', 45)->nullable();
            $table->string('navegador')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->json('propuesta');
            $table->string('huella', 40);
            $table->timestamps();
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->timestamp('itinerario_visto_at')->nullable()->after('confirmado_at');
        });

        // Precio de una habitación o de un concepto de un proveedor, por temporada.
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habitacion_id')->nullable()->constrained('habitacions')->cascadeOnDelete();
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedors')->cascadeOnDelete();
            $table->string('concepto')->nullable();
            $table->date('desde');
            $table->date('hasta');
            $table->decimal('precio', 10, 2);
            $table->string('moneda', 3)->default('USD');
            $table->string('por', 10)->default('grupo');
            $table->string('notas')->nullable();
            $table->timestamps();
            $table->index(['desde', 'hasta']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifas');
        Schema::table('programas', fn (Blueprint $table) => $table->dropColumn('itinerario_visto_at'));
        Schema::dropIfExists('aceptaciones');
    }
};
