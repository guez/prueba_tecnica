<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventAssistantMailer extends Mailable
{
    use Queueable, SerializesModels;

    private $data;
    private $amount;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $amount)
    {
        $this->data = $data;    
        $this->amount = $amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.events.event_assistant', [
            "data"=>$this->data,
            "amount"=>$this->amount
        ]);
    }
}
