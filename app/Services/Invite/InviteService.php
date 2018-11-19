<?php
namespace App\Services\Invite;

use App\Contracts\Invite\InviteServiceInterface;
use App\Http\Controllers\PlansController;
use App\Models\Invite;
use App\Models\InviteType;
use App\Models\MessagesTemplates;
use App\Models\User;
use App\Services\Verification\Handlers\EmailInviteVerificationHandler;
use App\Services\Verification\VerificationService;
use Psy\Util\Json;

class InviteService implements InviteServiceInterface
{
    const PLAN_CONTROLLER_METHOD = 'add_user';
    const PLAN_CONTROLLER_NAME = PlansController::class;

    public function create(User $recipient, InviteType $invite_type, array $params = [])
    {
        $invite = new Invite();
        $dataMessage = ['recipient_name' => $recipient->name, 'plan_name' => $params['plan_name']];
        EmailInviteVerificationHandler::$email_body = $this->get_message($invite_type, $dataMessage);
        $invite->model_name = self::PLAN_CONTROLLER_NAME;
        $invite->model_method = self::PLAN_CONTROLLER_METHOD;
        $invite->recipient_id = $recipient->id;
        $invite->params = Json::encode(['user_id' => $recipient->id, 'plan_id' => $params['plan_id']]);
        $invite->plan_id = $params['plan_id'];
        $invite->save();
        VerificationService::setPlayload(['invite_id' => $invite->id, 'user_id' => $recipient->id, 'plan_id' => $params['plan_id']]);
        $verificationService = new VerificationService();
        $verificationService->send($recipient, new EmailInviteVerificationHandler(), 'invite');
        return true;
    }

    protected function get_message(InviteType $invite_type, array $params = [])
    {
       $messageTemplate = MessagesTemplates::whereName($invite_type->name)->firstOrFail();
       $messageTemplate->insert_values($params);
       return $messageTemplate->body;
    }
}