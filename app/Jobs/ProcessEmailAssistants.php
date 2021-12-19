<?php

namespace App\Jobs;

use App\Mail\EventAssistantMailer;
use App\Models\Event;
use App\Models\EventAssistant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessEmailAssistants implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $amount;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, int $amount)
    {
        $this->amount = $amount;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->data['email'])->send(new EventAssistantMailer($this->data, $this->amount));
    }
}
