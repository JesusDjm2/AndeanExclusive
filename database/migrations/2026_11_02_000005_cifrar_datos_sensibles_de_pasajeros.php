<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * El n.º de pasaporte y las condiciones médicas pasan a guardarse cifrados con APP_KEY:
 * si cambia o se pierde esa clave, estos datos no se pueden recuperar.
 */
return new class extends Migration
{
    private const CAMPOS = ['pasaporte', 'condiciones_medicas'];

    public function up(): void
    {
        Schema::table('paxes', fn (Blueprint $table) => $table->text('pasaporte')->nullable()->change());

        $this->convertir(function (string $valor) {
            try {
                Crypt::decryptString($valor);

                return $valor;
            } catch (DecryptException) {
                return Crypt::encryptString($valor);
            }
        });
    }

    public function down(): void
    {
        $this->convertir(function (string $valor) {
            try {
                return Crypt::decryptString($valor);
            } catch (DecryptException) {
                return $valor;
            }
        });

        Schema::table('paxes', fn (Blueprint $table) => $table->string('pasaporte')->nullable()->change());
    }

    private function convertir(callable $convertir): void
    {
        DB::table('paxes')->select(['id', ...self::CAMPOS])->orderBy('id')->each(function ($pax) use ($convertir) {
            $valores = [];
            foreach (self::CAMPOS as $campo) {
                if (filled($pax->{$campo})) {
                    $valores[$campo] = $convertir($pax->{$campo});
                }
            }
            if ($valores) {
                DB::table('paxes')->where('id', $pax->id)->update($valores);
            }
        });
    }
};
