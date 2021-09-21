<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class SendTravelPaymentEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $details =  $this->details;
        $pdf = PDF::loadView('emails.travelnvoice', compact('details'));
        return $this->from('book@mosmos.co.ke')
                    ->subject('Payment Invoice')
                    ->attachData($pdf->output(), "invoice.pdf")
                    ->view('emails.sendTravelInvoice');
    }
}
