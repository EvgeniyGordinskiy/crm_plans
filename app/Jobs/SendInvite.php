<?php

namespace App\Jobs;

use App\Mail\EmailNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Snowfire\Beautymail\Beautymail;

class SendInvite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $body;
    public $url;
    private $token;
    private $redirectPath;
    
    /**
     * Create a new job instance.
     * @param User $user
     * @param string $body
     * @param string $redirect
     * @return void
     */
    public function __construct(User $user, string $token, string $body, $redirect)
    {
        $this->user = $user;
        $this->body = $body;
        $this->token = $token;
        $this->redirectPath = $redirect;
        $this->url = $this->redirectPath.'?token='.$this->token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $beautymail = new Beautymail(config('beatymail'));
        $beautymail->send('emails.verifyInvite', ['user' => $this->user, 'body' => $this->body, 'url' => $this->redirectPath.'?token='.$this->token, 'logo' => false], function($message)
        {
            $message
                ->from('test_work@example.com', 'Pans')
                ->to($this->user->email)
                ->subject('Invite to the Plan!');
        });
    }
}
