<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNotificationMail extends Mailable
{
    public $htrans;
    public $dtransItems;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($htrans, $dtransItems)
    {
        $this->htrans = $htrans;
        $this->dtransItems = $dtransItems;
    }

    public function build()
    {
        return $this->view('emails.adminNotification')
                    ->with([
                        'htrans' => $this->htrans,
                        'dtransItems' => $this->dtransItems,
                    ])
                    ->subject('Notifikasi Transaksi Baru');
    }
}
