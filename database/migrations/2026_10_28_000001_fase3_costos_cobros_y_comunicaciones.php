<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 3:
 *  - servicios: costo (en USD o PEN, por grupo o por persona) y pago al proveedor (cuentas por pagar).
 *  - programas: tipo de cambio, suplemento individual y fecha de confirmación (plazo del depósito: 7 días).
 *  - paxs: cuándo los actualizó el propio cliente desde su enlace.
 *  - notificaciones: correos automáticos enviados (recordatorios de pago, datos de pasajeros, encuesta).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->decimal('costo', 10, 2)->nullable()->after('confirmacion');
            $table->char('moneda', 3)->default('USD')->after('costo');
            $table->string('costo_por', 8)->default('grupo')->after('moneda');
            $table->date('pagado_at')->nullable()->after('costo_por');
            $table->string('pago_referencia')->nullable()->after('pagado_at');
        });

        Schema::table('programas', function (Blueprint $table) {
            $table->decimal('tipo_cambio', 6, 3)->nullable()->after('porcentaje_ganancia');
            $table->decimal('suplemento_individual', 10, 2)->default(0)->after('tipo_cambio');
            $table->unsignedTinyInteger('habitaciones_individuales')->default(0)->after('suplemento_individual');
            $table->timestamp('confirmado_at')->nullable()->after('estado');
        });
        DB::table('programas')->where('estado', 'confirmado')->update(['confirmado_at' => DB::raw('updated_at')]);

        Schema::table('paxes', function (Blueprint $table) {
            $table->timestamp('actualizado_por_cliente_at')->nullable();
        });

        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->string('tipo', 30);
            $table->string('email');
            $table->boolean('automatica')->default(true);
            $table->foreignId('enviada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['programa_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
        Schema::table('paxes', fn (Blueprint $table) => $table->dropColumn('actualizado_por_cliente_at'));
        Schema::table('programas', fn (Blueprint $table) => $table->dropColumn(['tipo_cambio', 'suplemento_individual', 'habitaciones_individuales', 'confirmado_at']));
        Schema::table('servicios', fn (Blueprint $table) => $table->dropColumn(['costo', 'moneda', 'costo_por', 'pagado_at', 'pago_referencia']));
    }
};
