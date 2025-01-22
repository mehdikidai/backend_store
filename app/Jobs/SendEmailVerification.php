<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendEmailVerification implements ShouldQueue
{
    use Queueable;


    public string $code;
    public object $user;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $code)
    {
        $this->code = $code;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Log::info('email verification :' . $this->user->email . ' and code :' . $this->code);

    }
}
