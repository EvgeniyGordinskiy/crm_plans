<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $body;
    public $url;
    private $token;
    private $redirectPath;
    /**
     * Create a new message instance.
     *
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('test_work@example.com', 'Pans')
            ->to($this->user->email)->view('emails.verifyInvite');
    }
}
