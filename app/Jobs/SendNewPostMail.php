<?php

namespace App\Jobs;

use App\Mail\NewPostMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewPostMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $MailingData)
    {
        $this->MailingData = $MailingData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Mail::to($this->MailingData['send'])->send(new NewPostMail(['name'=> $this->MailingData['name'], 'title'=> $this->MailingData['title']]));
    }
}
