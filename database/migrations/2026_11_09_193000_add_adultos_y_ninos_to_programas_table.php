<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->unsignedSmallInteger('cantidad_adultos')->default(1)->nullable()->after('moneda');
            $table->unsignedSmallInteger('cantidad_ninos')->default(0)->nullable()->after('cantidad_adultos');
        });

        // Poblar datos iniciales según cantidad_pax existente
        DB::table('programas')->whereNotNull('cantidad_pax')->update([
            'cantidad_adultos' => DB::raw('GREATEST(1, cantidad_pax)'),
            'cantidad_ninos'   => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->dropColumn(['cantidad_adultos', 'cantidad_ninos']);
        });
    }
};
