<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tarifario_categorias')) {
            Schema::create('tarifario_categorias', function (Blueprint $table) {
                $table->id();
                $table->string('slug', 50)->unique();
                $table->string('nombre', 100);
                $table->string('icono', 50)->default('fa-tag');
                $table->integer('orden')->default(0);
                $table->boolean('activo')->default(true)->index();
                $table->timestamps();
            });

            // Sembrar categorías iniciales oficiales
            $categoriasIniciales = [
                ['slug' => 'traslado',     'nombre' => 'Traslados',                    'icono' => 'fa-shuttle-van',       'orden' => 1],
                ['slug' => 'tour',         'nombre' => 'Tours y Experiencias',         'icono' => 'fa-map-marked-alt',    'orden' => 2],
                ['slug' => 'ceremonia',    'nombre' => 'Ceremonias Místicas',          'icono' => 'fa-fire',              'orden' => 3],
                ['slug' => 'trek',         'nombre' => 'Treks y Caminatas',            'icono' => 'fa-hiking',            'orden' => 4],
                ['slug' => 'boleto',       'nombre' => 'Boletos y Entradas Oficiales', 'icono' => 'fa-ticket-alt',        'orden' => 5],
                ['slug' => 'alimentacion', 'nombre' => 'Almuerzos y Picnics',          'icono' => 'fa-utensils',          'orden' => 6],
                ['slug' => 'viatico',      'nombre' => 'Viáticos Guía / Conductor',    'icono' => 'fa-hotel',             'orden' => 7],
                ['slug' => 'propina',      'nombre' => 'Cuadro de Propinas',           'icono' => 'fa-hand-holding-usd',  'orden' => 8],
                ['slug' => 'componente',   'nombre' => 'Componentes y Logística',      'icono' => 'fa-cogs',              'orden' => 9],
                ['slug' => 'otro',         'nombre' => 'Otros Servicios',              'icono' => 'fa-folder-open',       'orden' => 10],
            ];

            $now = now();
            foreach ($categoriasIniciales as &$cat) {
                $cat['activo'] = true;
                $cat['created_at'] = $now;
                $cat['updated_at'] = $now;
            }
            DB::table('tarifario_categorias')->insert($categoriasIniciales);
        }

        if (! Schema::hasTable('tarifario_ajustes')) {
            Schema::create('tarifario_ajustes', function (Blueprint $table) {
                $table->id();
                $table->string('clave', 50)->unique();
                $table->text('valor')->nullable();
                $table->string('descripcion', 255)->nullable();
                $table->timestamps();
            });

            DB::table('tarifario_ajustes')->insert([
                'clave'       => 'tipo_cambio',
                'valor'       => '3.30',
                'descripcion' => 'Tipo de cambio oficial de referencia (Soles por Dólar)',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // Asignar los servicios de ceremonias místicas a su categoría dedicada 'ceremonia'
        if (Schema::hasTable('tarifario_servicios')) {
            DB::table('tarifario_servicios')
                ->where('codigo', 'like', 'CEREM-%')
                ->update(['categoria' => 'ceremonia']);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifario_categorias');
        Schema::dropIfExists('tarifario_ajustes');
    }
};
