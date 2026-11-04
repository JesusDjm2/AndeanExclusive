<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->boolean('plantilla')->default(false)->after('estado')->index();
        });
    }

    public function down(): void
    {
        Schema::table('programas', fn (Blueprint $table) => $table->dropColumn('plantilla'));
    }
};
