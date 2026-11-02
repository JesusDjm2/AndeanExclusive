<?php

namespace App\Mail;

use App\Models\Programa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Recordatorio al cliente (depósito, saldo, datos de pasajeros, encuesta) en el idioma del programa. */
class RecordatorioMailable extends Mailable
{
    use Queueable;
    use SerializesModels;

    private const ASUNTOS = [
        'deposito' => ['Recordatorio: depósito para confirmar su viaje', 'Reminder: deposit to secure your trip'],
        'saldo' => ['Recordatorio: saldo de su viaje', 'Reminder: balance of your trip'],
        'datos_pax' => ['Complete los datos de los viajeros', 'Please complete your travelers’ details'],
        'encuesta' => ['¿Cómo fue su viaje por el Perú?', 'How was your trip to Peru?'],
    ];

    public function __construct(public Programa $programa, public string $tipo) {}

    public function envelope(): Envelope
    {
        $isEn = $this->programa->lang === 'en';

        return new Envelope(
            from: new Address((string) config('mail.from.address'), config('mail.from.name', 'Andean Exclusive Tours')),
            subject: self::ASUNTOS[$this->tipo][$isEn ? 1 : 0] . ' · ' . $this->programa->nombre . ' (' . $this->programa->codigo . ')',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.recordatorio', with: [
            'isEn' => $this->programa->lang === 'en',
            'cobro' => $this->programa->cobranza(),
        ]);
    }
}
