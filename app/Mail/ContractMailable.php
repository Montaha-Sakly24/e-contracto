<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContractMailable extends Mailable
{
    use Queueable, SerializesModels;


    public $contractData;

    /**
     * Create a new message instance.
     */
    public function __construct($contractData)
    {
        $this->contractData = $contractData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Contract Mailable',
        );
    }

     /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contract')
                    ->with(['contractData' => $this->contractData]);
    }


}
