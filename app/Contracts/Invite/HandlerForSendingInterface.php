<?php
namespace App\Contracts\Invite;

use App\Models\Invite;
use App\Models\User;

interface HandlerForSendingInterface
{
    /**
     * Send notification of invite.
     * @return mixed
     */
    public function send(User $recipient, Invite $invite);
}