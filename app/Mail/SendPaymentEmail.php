<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class SendPaymentEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $details;

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
        $pdf = PDF::loadView('emails.invoice', compact('details'));
        return $this->from('order@mosmos.co.ke')
                    ->subject('Payment Invoice')
                    ->attachData($pdf->output(), "invoice.pdf")
                    ->view('emails.sendInvoice');
    }
}
