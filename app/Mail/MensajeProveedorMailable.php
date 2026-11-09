<?php

namespace App\Mail;

use App\Support\CorreoReservas;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Mensaje de Reservas a un proveedor (App\Support\CorreoReservas): texto redactado por Reservas y, si se pide, la orden en PDF. */
class MensajeProveedorMailable extends Mailable
{
    use Queueable;

    /** @param array{0: string, 1: string}|null $orden  [contenido del PDF, nombre del archivo] */
    public function __construct(public string $asunto, public string $cuerpo, private ?array $orden = null) {}

    public function envelope(): Envelope
    {
        [$correo, $nombre] = CorreoReservas::remitente();
        $propio = filled(config('mail.mailers.reservas.host'));

        // Con su propio SMTP sale como reservas@; si no, con el remitente general y "responder a" reservas@.
        return new Envelope(
            from: $propio ? new Address($correo, $nombre) : new Address((string) config('mail.from.address'), $nombre),
            replyTo: [new Address($correo, $nombre)],
            subject: $this->asunto,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.mensaje-proveedor', text: 'emails.mensaje-proveedor-texto');
    }

    public function attachments(): array
    {
        return $this->orden ? [Attachment::fromData(fn () => $this->orden[0], $this->orden[1])->withMime('application/pdf')] : [];
    }
}
