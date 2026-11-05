<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('programas') && ! Schema::hasColumn('programas', 'moneda')) {
            Schema::table('programas', function (Blueprint $table) {
                $table->string('moneda', 3)->default('USD')->after('lang');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('programas') && Schema::hasColumn('programas', 'moneda')) {
            Schema::table('programas', function (Blueprint $table) {
                $table->dropColumn('moneda');
            });
        }
    }
};
