<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Separar "qué se vende" de "quién lo opera":
 *  - costo_estimado: con lo que se cotiza al cliente (tarifa de referencia, sin proveedor elegido).
 *  - costo (existente): el costo real que cobra el proveedor asignado; con él se paga y se calcula el margen real.
 *  - estado 'sin_asignar': servicio vendido que todavía no tiene proveedor (antes de "Por reservar").
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', fn (Blueprint $table) => $table->decimal('costo_estimado', 10, 2)->nullable()->after('confirmacion'));

        // Lo cargado hasta hoy servía de estimado y de real a la vez.
        DB::table('servicios')->whereNotNull('costo')->update(['costo_estimado' => DB::raw('costo')]);
        DB::table('servicios')->whereNull('proveedor_id')->where('estado', 'pendiente')->update(['estado' => 'sin_asignar']);
    }

    public function down(): void
    {
        DB::table('servicios')->where('estado', 'sin_asignar')->update(['estado' => 'pendiente']);
        Schema::table('servicios', fn (Blueprint $table) => $table->dropColumn('costo_estimado'));
    }
};
