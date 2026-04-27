<?php

namespace App\Mail;

use App\Models\Partida;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartidaProxima extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Partida $partida,
        public User $usuario,
        public int $minutesBefore
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu partida de ' . $this->partida->deporte . ' empieza pronto',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partida-proxima',
        );
    }
}
