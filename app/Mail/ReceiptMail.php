<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $htrans;
    // public $cartItems;
    public $dtransItems; // Use dtrans items with product details
    // $cartItems masukan di () 
    public function __construct($htrans, $dtransItems)
    {
        $this->htrans = $htrans;
        // $this->cartItems = $cartItems;
        $this->dtransItems = $dtransItems;
    }
    
    public function build()
    {
        return $this->view('emails.receipt')
                    ->subject('Your Order Receipt')
                    ->with([
                        'htrans' => $this->htrans,
                        'dtransItems' => $this->dtransItems,
                        // 'cartItems' => $this->cartItems,
                    ]);
    }
    
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Receipt Mail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    // public function content()
    // {
    //     return new Content(
    //         view: 'emails.receipt',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
