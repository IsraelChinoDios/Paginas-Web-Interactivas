<?php

namespace App\Mail;

use App\Models\ConcessionOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConcessionOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public ConcessionOrder $order;

    /**
     * Create a new message instance.
     */
    public function __construct(ConcessionOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Pedido dulcería #'.$this->order->id)
            ->view('emails.concession_order');
    }
}
