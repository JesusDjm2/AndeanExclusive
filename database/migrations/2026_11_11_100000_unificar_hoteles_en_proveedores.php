<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Unifica hoteles directamente en la tabla proveedors y vincula habitacions a proveedor_id.
     * Elimina la redundancia de la tabla hotels y hotel_programa.
     */
    public function up(): void
    {
        // 1. Agregar columna img a proveedors si no existe
        if (! Schema::hasColumn('proveedors', 'img')) {
            Schema::table('proveedors', function (Blueprint $table) {
                $table->string('img')->nullable()->after('correo');
            });
        }

        // 2. Agregar columna proveedor_id a habitacions si no existe
        if (! Schema::hasColumn('habitacions', 'proveedor_id')) {
            Schema::table('habitacions', function (Blueprint $table) {
                $table->foreignId('proveedor_id')->nullable()->after('id')->constrained('proveedors')->cascadeOnDelete();
            });
        }

        // 3. Migrar datos existentes de hotels -> proveedors y habitacions -> proveedor_id
        if (Schema::hasTable('hotels')) {
            $categoriaHotelId = DB::table('categorias')->where('nombre', 'like', '%Hotel%')->value('id');
            if (! $categoriaHotelId) {
                $categoriaHotelId = DB::table('categorias')->insertGetId([
                    'nombre' => 'Hoteles',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $hotels = DB::table('hotels')->get();
            foreach ($hotels as $hotel) {
                $provId = $hotel->proveedor_id;

                if (! $provId) {
                    $prov = DB::table('proveedors')->where('nombre', $hotel->nombre)->first();
                    if ($prov) {
                        $provId = $prov->id;
                    } else {
                        $provId = DB::table('proveedors')->insertGetId([
                            'nombre' => $hotel->nombre,
                            'categoria_id' => $categoriaHotelId,
                            'ruc' => $hotel->ruc,
                            'direccion' => $hotel->direccion,
                            'telefono' => $hotel->telefono ?? '',
                            'correo' => $hotel->correo ?? 'reservas@' . \Illuminate\Support\Str::slug($hotel->nombre) . '.com',
                            'img' => $hotel->img,
                            'detalles' => $hotel->detalles,
                            'created_at' => $hotel->created_at ?? now(),
                            'updated_at' => $hotel->updated_at ?? now(),
                        ]);
                    }
                }

                // Si el hotel tenía foto de portada, pasarla al proveedor
                if ($hotel->img && $provId) {
                    DB::table('proveedors')->where('id', $provId)->whereNull('img')->update(['img' => $hotel->img]);
                }

                // Actualizar las habitaciones que pertenecían a este hotel
                if ($provId && Schema::hasColumn('habitacions', 'hotel_id')) {
                    DB::table('habitacions')->where('hotel_id', $hotel->id)->update(['proveedor_id' => $provId]);
                }
            }
        }

        // 4. Eliminar tabla pivot hotel_programa si existe
        if (Schema::hasTable('hotel_programa')) {
            Schema::dropIfExists('hotel_programa');
        }

        // 5. Eliminar la foreign key y columna hotel_id de habitacions
        if (Schema::hasTable('habitacions') && Schema::hasColumn('habitacions', 'hotel_id')) {
            Schema::table('habitacions', function (Blueprint $table) {
                $table->dropForeign(['hotel_id']);
                $table->dropColumn('hotel_id');
            });
        }

        // 6. Eliminar la tabla redundante hotels
        if (Schema::hasTable('hotels')) {
            Schema::dropIfExists('hotels');
        }
    }

    /**
     * Reversa las migraciones.
     */
    public function down(): void
    {
        // Recrear hotels si no existe
        if (! Schema::hasTable('hotels')) {
            Schema::create('hotels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('proveedor_id')->nullable()->constrained('proveedors')->nullOnDelete();
                $table->string('nombre');
                $table->string('ruc')->nullable();
                $table->string('direccion')->nullable();
                $table->string('telefono')->nullable();
                $table->string('correo')->nullable();
                $table->string('img')->nullable();
                $table->text('detalles')->nullable();
                $table->timestamps();
            });
        }

        // Recrear columna hotel_id en habitacions
        if (! Schema::hasColumn('habitacions', 'hotel_id')) {
            Schema::table('habitacions', function (Blueprint $table) {
                $table->foreignId('hotel_id')->nullable()->after('proveedor_id')->constrained('hotels')->cascadeOnDelete();
            });
        }

        // Recrear hotel_programa
        if (! Schema::hasTable('hotel_programa')) {
            Schema::create('hotel_programa', function (Blueprint $table) {
                $table->id();
                $table->foreignId('programa_id')->constrained()->cascadeOnDelete();
                $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }
};
