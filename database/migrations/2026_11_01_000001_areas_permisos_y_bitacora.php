<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        // Nueva área. Las reglas de cada una viven en App\Support\Permisos.
        Role::findOrCreate('operaciones', 'web');
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Reservas solicita el pago al proveedor; Contabilidad lo registra.
        Schema::table('servicios', function (Blueprint $table) {
            $table->timestamp('pago_solicitado_at')->nullable()->after('pagado_at');
            $table->foreignId('pago_solicitado_por')->nullable()->after('pago_solicitado_at')->constrained('users')->nullOnDelete();
        });

        // Sin claves foráneas a propósito en programa: el movimiento debe sobrevivir a que se elimine.
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('usuario');
            $table->string('area', 40)->nullable();
            $table->string('verbo', 60);
            $table->string('modulo', 40);
            $table->string('titulo')->default('');
            $table->unsignedBigInteger('programa_id')->nullable();
            $table->string('programa_codigo', 40)->nullable();
            $table->json('cambios')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('created_at');
            $table->index(['programa_id', 'created_at']);
            $table->index(['area', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');

        Schema::table('servicios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pago_solicitado_por');
            $table->dropColumn('pago_solicitado_at');
        });

        Role::where('name', 'operaciones')->where('guard_name', 'web')->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
