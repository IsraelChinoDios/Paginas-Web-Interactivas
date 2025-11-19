<?php

namespace App\Mail;

use App\Models\TicketPurchase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public TicketPurchase $ticket;

    /**
     * Create a new message instance.
     */
    public function __construct(TicketPurchase $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Compra de boletos #'.$this->ticket->id)
            ->view('emails.ticket_purchase');
    }
}
