<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new details instance.
     *
     * @return void
     */
    public $details;

    public function __construct($details)
  {
      $this->details = $details;
  }


    /**
     * Build the details.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('combineke@gmail.com')
            ->subject('Booking Reminder')
            ->view('emails.sendmail');
    }
}
