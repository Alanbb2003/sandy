<?php

namespace App\Mail;

use App\Models\retur;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReturAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $retur;

    /**
     * Create a new message instance.
     */
    public function __construct(retur $retur)
    {
        $this->retur = $retur;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Permintaan Pengembalian Diterima - Diperlukan Tindakan')
                    ->view('emails.returAccepted');
    }
}
