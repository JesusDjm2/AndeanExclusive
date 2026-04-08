<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->foreignId('anio_id')->nullable()->after('presentacion')->constrained('anios')->nullOnDelete();
            $table->foreignId('mes_id')->nullable()->after('anio_id')->constrained('meses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->dropForeign(['anio_id']);
            $table->dropForeign(['mes_id']);
            $table->dropColumn(['anio_id', 'mes_id']);
        });
    }
};
