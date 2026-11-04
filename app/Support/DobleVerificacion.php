<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Throwable;

/** Segundo paso del inicio de sesión: un código de 6 dígitos enviado al correo del usuario. */
class DobleVerificacion
{
    private const COOKIE = 'ae_equipo_confianza';

    private const MINUTOS_DE_VALIDEZ = 10;

    private const INTENTOS = 5;

    public static function hacePara(User $user, Request $request): bool
    {
        return config('seguridad.doble_verificacion')
            && $user->hasAnyRole(config('seguridad.doble_verificacion_roles'))
            && ! self::esEquipoDeConfianza($user, $request);
    }

    /** Guarda el pendiente en la sesión y envía el código. Devuelve false si el correo no salió. */
    public static function iniciar(User $user, bool $recordar, Request $request): bool
    {
        $codigo = (string) random_int(100000, 999999);

        try {
            Mail::raw("Tu código para entrar al panel de Andean Exclusive es: {$codigo}\n\nVence en " . self::MINUTOS_DE_VALIDEZ . " minutos. Si no fuiste tú, cambia tu contraseña y avisa a Administración.", function ($mensaje) use ($user) {
                $mensaje->to($user->email, $user->name)->subject('Tu código de acceso');
            });
        } catch (Throwable $e) {
            report($e);

            return false;
        }

        $request->session()->put('doble_verificacion', [
            'user_id' => $user->id, 'codigo' => Hash::make($codigo), 'vence' => now()->addMinutes(self::MINUTOS_DE_VALIDEZ)->timestamp,
            'intentos' => 0, 'recordar' => $recordar,
        ]);

        return true;
    }

    public static function pendiente(Request $request): ?array
    {
        $pendiente = $request->session()->get('doble_verificacion');

        return $pendiente && $pendiente['vence'] >= now()->timestamp && $pendiente['intentos'] < self::INTENTOS ? $pendiente : null;
    }

    public static function comprobar(Request $request, string $codigo): bool
    {
        $pendiente = self::pendiente($request);
        if (! $pendiente) {
            return false;
        }
        if (Hash::check($codigo, $pendiente['codigo'])) {
            return true;
        }

        $request->session()->put('doble_verificacion.intentos', $pendiente['intentos'] + 1);

        return false;
    }

    public static function confiarEnEsteEquipo(User $user): void
    {
        $dias = (int) config('seguridad.dias_equipo_de_confianza');
        Cookie::queue(Cookie::make(self::COOKIE, self::sello($user), $dias * 24 * 60, null, null, null, true, false, 'lax'));
    }

    private static function esEquipoDeConfianza(User $user, Request $request): bool
    {
        return hash_equals(self::sello($user), (string) $request->cookie(self::COOKIE));
    }

    /** Cambia con la contraseña: al cambiarla, los equipos de confianza dejan de serlo. */
    private static function sello(User $user): string
    {
        return hash_hmac('sha256', $user->id . '|' . $user->password, (string) config('app.key'));
    }
}
