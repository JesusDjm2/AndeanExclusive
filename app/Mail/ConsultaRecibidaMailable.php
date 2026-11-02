<?php

namespace App\Mail;

use App\Models\Consulta;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Aviso a la agencia de una consulta del sitio. Responder al correo le escribe directamente al visitante. */
class ConsultaRecibidaMailable extends Mailable
{
    public function __construct(public Consulta $consulta)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->consulta->email, $this->consulta->nombre)],
            subject: 'Nueva consulta: ' . $this->consulta->nombre . ($this->consulta->tour ? ' · ' . $this->consulta->tour : ''),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.consulta');
    }
}
