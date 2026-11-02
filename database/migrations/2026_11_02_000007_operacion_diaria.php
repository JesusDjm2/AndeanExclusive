<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->string('llegada_vuelo', 40)->nullable()->after('fin');
            $table->dateTime('llegada_at')->nullable()->after('llegada_vuelo');
            $table->string('salida_vuelo', 40)->nullable()->after('llegada_at');
            $table->dateTime('salida_at')->nullable()->after('salida_vuelo');
            $table->timestamp('cerrado_at')->nullable()->after('itinerario_visto_at');
            $table->foreignId('cerrado_por')->nullable()->after('cerrado_at')->constrained('users')->nullOnDelete();
        });

        Schema::table('servicios', function (Blueprint $table) {
            $table->timestamp('orden_enviada_at')->nullable()->after('pago_solicitado_por');
        });
    }

    public function down(): void
    {
        Schema::table('servicios', fn (Blueprint $table) => $table->dropColumn('orden_enviada_at'));

        Schema::table('programas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cerrado_por');
            $table->dropColumn(['llegada_vuelo', 'llegada_at', 'salida_vuelo', 'salida_at', 'cerrado_at']);
        });
    }
};
