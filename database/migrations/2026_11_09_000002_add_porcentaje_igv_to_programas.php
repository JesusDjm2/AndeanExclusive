<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            if (! Schema::hasColumn('programas', 'porcentaje_igv')) {
                $table->decimal('porcentaje_igv', 8, 2)->nullable()->default(18.00)->after('porcentaje_ganancia');
            }
        });
    }

    public function down(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            if (Schema::hasColumn('programas', 'porcentaje_igv')) {
                $table->dropColumn('porcentaje_igv');
            }
        });
    }
};
