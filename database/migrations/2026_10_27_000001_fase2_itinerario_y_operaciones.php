<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fase 2: itinerario estructurado por días y operación.
 *  - programa_dias: título y texto de cada día para el cliente.
 *  - servicios: lo que se opera cada día (traslado, guía, tren, entrada...), con proveedor, estado de reserva y confirmación.
 *  - tareas: pendientes con fecha límite (entradas a Machu Picchu, permisos del Camino Inca...).
 * La tabla programa_proveedor (casillas sin fecha ni estado) se convierte en servicios y se elimina.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programa_dias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->date('fecha');
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->unique(['programa_id', 'fecha']);
        });

        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->date('fecha');
            $table->time('hora')->nullable();
            $table->string('tipo', 20)->default('otro');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedors')->nullOnDelete();
            $table->string('punto_encuentro')->nullable();
            $table->string('estado', 15)->default('pendiente');
            $table->string('confirmacion', 80)->nullable();
            $table->text('notas')->nullable();
            $table->boolean('visible_cliente')->default(true);
            $table->timestamps();
            $table->index(['fecha', 'hora']);
        });

        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->string('titulo');
            $table->date('fecha_limite')->nullable();
            $table->string('notas')->nullable();
            $table->timestamp('completada_at')->nullable();
            $table->foreignId('completada_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('fecha_limite');
        });

        // Proveedores asignados → un servicio "por reservar" el primer día del programa.
        if (Schema::hasTable('programa_proveedor')) {
            $tipoPorCategoria = ['transporte' => 'traslado', 'guía' => 'guia', 'guia' => 'guia', 'restaurante' => 'comida', 'picnic' => 'comida'];
            $filas = DB::table('programa_proveedor as pp')
                ->join('programas as p', 'p.id', '=', 'pp.programa_id')
                ->join('proveedors as pr', 'pr.id', '=', 'pp.proveedor_id')
                ->leftJoin('categorias as c', 'c.id', '=', 'pr.categoria_id')
                ->get(['pp.programa_id', 'pp.proveedor_id', 'p.inicio', 'pr.nombre', 'c.nombre as categoria']);

            foreach ($filas as $fila) {
                DB::table('servicios')->insert([
                    'programa_id' => $fila->programa_id,
                    'fecha' => $fila->inicio ?? now()->toDateString(),
                    'tipo' => $tipoPorCategoria[mb_strtolower((string) $fila->categoria)] ?? 'otro',
                    'titulo' => $fila->nombre,
                    'proveedor_id' => $fila->proveedor_id,
                    'estado' => 'pendiente',
                    'visible_cliente' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::drop('programa_proveedor');
        }
    }

    public function down(): void
    {
        Schema::create('programa_proveedor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programa_id')->constrained('programas')->cascadeOnDelete();
            $table->foreignId('proveedor_id')->constrained('proveedors')->cascadeOnDelete();
            $table->unique(['programa_id', 'proveedor_id']);
            $table->timestamps();
        });

        DB::table('servicios')->whereNotNull('proveedor_id')->get(['programa_id', 'proveedor_id'])
            ->unique(fn ($s) => $s->programa_id . '-' . $s->proveedor_id)
            ->each(fn ($s) => DB::table('programa_proveedor')->insert([
                'programa_id' => $s->programa_id, 'proveedor_id' => $s->proveedor_id, 'created_at' => now(), 'updated_at' => now(),
            ]));

        Schema::dropIfExists('tareas');
        Schema::dropIfExists('servicios');
        Schema::dropIfExists('programa_dias');
    }
};
