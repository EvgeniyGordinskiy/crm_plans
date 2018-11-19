<?php
namespace App\Contracts\Invite;

use App\Contracts\ModalHasNameInterface;
use App\Models\Invite;
use App\Models\InviteType;
use App\Models\User;

interface InviteServiceInterface
{
    /**
     * @param User $recipient
     * @param InviteType $invite_type
     * @param array $params
     * @return mixed
     */
    public function create(User $recipient, InviteType $invite_type, array $params = []);
}