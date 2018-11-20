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
use Illuminate\Support\Facades\Log;
use Psy\Util\Json;
use ReflectionClass;

class InviteService implements InviteServiceInterface
{
    const PLAN_CONTROLLER_METHOD = 'add_user';
    const PLAN_CONTROLLER_NAME = PlansController::class;

    /**
     * @param User $recipient
     * @param InviteType $invite_type
     * @param array $params
     * @return bool|mixed
     */
    public function create(User $recipient, InviteType $invite_type, array $params = [])
    {
        $invite = new Invite();
        $dataMessage = ['recipient_name' => $recipient->name, 'plan_name' => $params['plan_name']];
        EmailInviteVerificationHandler::$email_body = $this->get_message($invite_type, $dataMessage);
        $invite->type_id = $invite_type->id;
        $invite->recipient_id = $recipient->id;
        $invite->params = Json::encode(['user_id' => $recipient->id, 'plan_id' => $params['plan_id']]);
        $invite->plan_id = $params['plan_id'];
        $invite->save();
        VerificationService::setPlayload(['invite_id' => $invite->id, 'user_id' => $recipient->id, 'plan_id' => $params['plan_id']]);
        $verificationService = new VerificationService();
        $verificationService->send($recipient, new EmailInviteVerificationHandler(), 'invite');
        return true;
    }


    public static function get_model(InviteType $type)
    {
        return self::get_static_property(strtoupper($type->name).'_CONTROLLER_NAME');
    }

    public static function get_method(InviteType $type)
    {
        return self::get_static_property(strtoupper($type->name).'_CONTROLLER_METHOD');
    }

    protected static function get_static_property($name)
    {
        $reflClass = new ReflectionClass(self::class);
        if ($reflClass->hasConstant($name)) {
            return  $reflClass->getConstant($name);
        }
        return false;
    }


    /**
     * @param InviteType $invite_type
     * @param array $params
     * @return mixed
     */
    protected function get_message(InviteType $invite_type, array $params = [])
    {
       $messageTemplate = MessagesTemplates::whereName($invite_type->name)->firstOrFail();
       $messageTemplate->insert_values($params);
       return $messageTemplate->body;
    }
}