<?php
namespace App\Services\Invite\Handlers;

use App\Contracts\Invite\HandlerForSendingInterface;
use App\Models\Invite;
use App\Models\User;

class SendEmailInviteHandler implements HandlerForSendingInterface
{
    public function send(User $recipient, Invite $invite)
    {
        return dispatch(new SendVerificationEmail($user, $string, env('APP_FRONT_URL').'/'.$redirectPath));
    }
}