<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailTestCommand extends Command
{
    protected $signature = 'mail:test {email : Correo destino para la prueba}';

    protected $description = 'Envía un correo de prueba vía SMTP (sin adjuntos) para verificar MAIL_* en .env';

    public function handle(): int
    {
        $to = $this->argument('email');

        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->error('El destino no es un correo válido.');

            return self::FAILURE;
        }

        $from = config('mail.from.address');
        $mailer = config('mail.default');

        if (empty($from) || ! filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $this->error('MAIL_FROM_ADDRESS en .env está vacío o no es un email válido.');
            $this->line('Ejemplo: MAIL_FROM_ADDRESS=tu-cuenta@gmail.com');

            return self::FAILURE;
        }

        if ($mailer === 'smtp' && empty(config('mail.mailers.smtp.username'))) {
            $this->warn('MAIL_USERNAME está vacío. Gmail y la mayoría de SMTP exigen usuario y contraseña.');
        }

        $this->info("Mailer: {$mailer}");
        $this->info("Desde: {$from}");
        $this->info("Hacia: {$to}");

        try {
            Mail::raw(
                "Prueba SMTP — " . now()->toDateTimeString() . "\n\nSi lee esto, la configuración básica funciona.",
                function ($message) use ($to) {
                    $message->to($to)->subject('Prueba de correo — Andean Exclusive');
                }
            );

            $this->info('Correo enviado. Revise la bandeja (y spam).');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Fallo al enviar:');
            $this->line($e->getMessage());
            $this->newLine();
            $this->line('Compruebe en .env: MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION (tls con puerto 587, o ssl con 465).');
            $this->line('Gmail: use “contraseña de aplicación”, no la contraseña normal de la cuenta.');

            return self::FAILURE;
        }
    }
}
