<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Order summary for the customer. (The old app pointed this at the dispatched template; it now uses orderMail.)
 */
class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $data
     */
    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Order Details');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.order.orderMail', with: ['data' => $this->data]);
    }
}
