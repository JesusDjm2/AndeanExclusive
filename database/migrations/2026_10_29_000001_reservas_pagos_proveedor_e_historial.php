<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Módulo de reservas (área de reservas):
 *  - proveedor_pagos: pagos a proveedores por servicio (parciales o totales, con método, referencia y comprobante).
 *  - servicio_eventos: historial de cada servicio (cambios de estado, confirmaciones, pagos) con quién y cuándo.
 * servicios.pagado_at queda como dato derivado (fecha en que se completó el pago); pago_referencia pasa a los pagos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedor_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
            $table->date('fecha');
            $table->decimal('monto', 10, 2);
            $table->char('moneda', 3)->default('USD');
            $table->string('metodo', 20)->nullable();
            $table->string('referencia')->nullable();
            $table->string('comprobante')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('servicio_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo', 15); // estado | pago | pago_anulado | nota
            $table->string('de', 30)->nullable();
            $table->string('a', 30)->nullable();
            $table->string('detalle')->nullable();
            $table->timestamps();
            $table->index(['servicio_id', 'created_at']);
        });

        // Servicios ya marcados como pagados → un pago por el importe total (costo × pasajeros que pagan si es por persona).
        $servicios = DB::table('servicios')->whereNotNull('pagado_at')->whereNotNull('costo')->get();
        foreach ($servicios as $s) {
            $pagantes = $s->costo_por === 'persona'
                ? DB::table('paxes')->where('programa_id', $s->programa_id)->count()
                : 1;
            DB::table('proveedor_pagos')->insert([
                'servicio_id' => $s->id,
                'fecha' => $s->pagado_at,
                'monto' => round($s->costo * max(1, $pagantes), 2),
                'moneda' => $s->moneda ?: 'USD',
                'referencia' => $s->pago_referencia,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('servicios', fn (Blueprint $table) => $table->dropColumn('pago_referencia'));
    }

    public function down(): void
    {
        Schema::table('servicios', fn (Blueprint $table) => $table->string('pago_referencia')->nullable()->after('pagado_at'));
        Schema::dropIfExists('servicio_eventos');
        Schema::dropIfExists('proveedor_pagos');
    }
};
