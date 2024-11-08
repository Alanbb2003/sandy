<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SurveyEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $transaction;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }
    public function build()
    {
        return $this->subject('We would love your feedback!')
                    ->view('emails.survey')
                    ->with([
                        'transaction' => $this->transaction,
                        'surveyLink' => url('https://tally.so/r/n9JW2K'), 
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
            subject: 'Survey Email',
        );
    }


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
