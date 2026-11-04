<?php

namespace App\Mail;

use App\Models\Programa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProgramaBienvenidaMailable extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Programa $programa,
        public string $pdfBinary,
        public string $pdfFilename,
    ) {}

    public function envelope(): Envelope
    {
        $fromName = config('mail.from.name', config('app.name', 'Andean Exclusive'));
        $isEn = $this->programa->lang === 'en';

        // El asunto distingue la etapa: propuesta (cotización) o programa confirmado.
        $subject = $this->programa->estado === 'cotizacion'
            ? ($isEn ? 'Your travel proposal: ' : 'Su propuesta de viaje: ') . $this->programa->nombre . ' (' . $this->programa->codigo . ')'
            : ($isEn ? 'Your confirmed trip to Peru: ' : 'Su viaje al Perú confirmado: ') . $this->programa->nombre . ' (' . $this->programa->codigo . ')';

        return new Envelope(
            from: new Address((string) config('mail.from.address'), $fromName),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.programa-bienvenida',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfBinary, $this->pdfFilename)
                ->withMime('application/pdf'),
        ];
    }
}
