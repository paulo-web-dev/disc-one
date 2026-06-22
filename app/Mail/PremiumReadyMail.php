<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PremiumReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nome,
        public string $url,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Seu relatório DISC ONE Premium está liberado',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.premium-ready');
    }
}
