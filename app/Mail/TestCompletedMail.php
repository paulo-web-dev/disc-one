<?php

namespace App\Mail;

use App\Models\Test;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Test $test)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Obrigado por concluir o DISC ONE — seu resultado está pronto',
        );
    }

    public function content(): Content
    {
        $nome = $this->test->respondent_name
            ?? optional($this->test->user)->name
            ?? 'Respondente';

        return new Content(
            view: 'emails.test-completed',
            with: [
                'nome' => $nome,
                'url'  => route('disc.resultDocumento', ['id' => $this->test->id]),
            ],
        );
    }
}
