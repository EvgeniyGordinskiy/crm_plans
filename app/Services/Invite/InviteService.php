<?php
namespace App\Services\Invite;

use App\Contracts\Invite\HandlerForImplementationInterface;
use App\Contracts\Invite\HandlerForSendingInterface;
use App\Contracts\Invite\InviteServiceInterface;
use App\Contracts\ModalHasNameInterface;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Requests\ParentUser\AddUserToFamily;
use App\Models\Invite;
use App\Models\InviteType;
use App\Models\Message;
use App\Models\Messages\InviteUserToFamilyMessage;
use App\Models\Messages\RequestToTheLimitedClassMessage;
use App\Models\ParentUser;
use App\Models\School;
use App\Models\User;
use App\Services\Verification\Handlers\EmailInviteVerificationHandler;
use App\Services\Verification\VerificationService;
use Psy\Util\Json;

class InviteService// implements InviteServiceInterface
{
    const FAMILY_MODEL_NAME = ParentUser::class;
    const FAMILY_MODELS_METHOD = 'addUserToFamily';
    const SCHOOL_CONTROLLER_METHOD = 'add_user_to_school';
    const SCHOOL_CONTROLLER_NAME = SchoolController::class;
    const PROGRAM_CONTROLLER_NAME = ProgramController::class;
    const PROGRAM_CONTROLLER_METHOD = 'add_user_to_program';
    const SCHOOL_MODELS_METHOD_REQUEST = AddUserToFamily::class;

    const REQUEST_LIMITED_CLASS_CONTROLLER_METHOD = 'add_user';
    const REQUEST_LIMITED_CLASS_CONTROLLER_NAME = SubscriptionController::class;

    public function create(User $recipient, User $sender, InviteType $invite_type, array $params = [])
    {
        $invite = new Invite();
        switch ($invite_type->name) {
            case InviteType::FAMILY: {
                $message = new InviteUserToFamilyMessage($recipient, $sender);
                EmailInviteVerificationHandler::$email_body = $message->body;
                if($message->save()) {
                    $invite->model_name = self::FAMILY_MODEL_NAME;
                    $invite->model_method = self::FAMILY_MODELS_METHOD;
                    $invite->recipient_id = $recipient->id;
                    $invite->message_id = $message->id;
                    $invite->params = Json::encode($params);
                    $invite->parent_id = $sender->id;
                    $invite->save();
                    VerificationService::setPlayload(['invite_id' => $invite->id, 'parent_id' => $sender->id]);
                }
                break;
            }
            case InviteType::PROGRAM: {
                InviteUserToFamilyMessage::$message_template_name = 'program';
                if (!$params['program_name'] || !$params['program_id']) {
                    throw new \Exception('Missed required param - "program_name"');
                }
                InviteUserToFamilyMessage::$programName = $params['program_name'];
                $message = new InviteUserToFamilyMessage($recipient, $sender);
                EmailInviteVerificationHandler::$email_body = $message->body;
                if($message->save()) {
                    $invite->model_name = self::PROGRAM_CONTROLLER_NAME;
                    $invite->model_method = self::PROGRAM_CONTROLLER_METHOD;
                    $invite->recipient_id = $recipient->id;
                    $invite->message_id = $message->id;
                    $invite->params = Json::encode($params);
                    $invite->program_id = $params['program_id'];
                    $invite->save();
                    VerificationService::setPlayload(['invite_id' => $invite->id, 'program_id' => $params['program_id']]);
                }
                break;
            }
            case InviteType::SCHOOL: {
                InviteUserToFamilyMessage::$message_template_name = 'school';
                if (!$params['school_name'] || !$params['school_name']) {
                    throw new \Exception('Missed required param - "school_name"');
                }
                InviteUserToFamilyMessage::$schoolName = $params['school_name'];
                $message = new InviteUserToFamilyMessage($recipient, $sender);
                EmailInviteVerificationHandler::$email_body = $message->body;
                if($message->save()) {
                    $invite->model_name = self::SCHOOL_CONTROLLER_NAME;
                    $invite->model_method = self::SCHOOL_CONTROLLER_METHOD;
                    $invite->recipient_id = $recipient->id;
                    $invite->message_id = $message->id;
                    $invite->params = Json::encode($params);
                    $invite->school_id = $params['school_id'];
                    $invite->save();
                    VerificationService::setPlayload(['invite_id' => $invite->id, 'user_id' => $recipient->id, 'school_id' => $params['school_id']]);
                }
                break;
            }
            case InviteType::LIMITED_CLASS: {
                if (!$params['program_name'] || !$params['subscription_name']) {
                    throw new \Exception('Missed required param - "program_name or subscription_name"');
                }
                $message = new RequestToTheLimitedClassMessage($recipient, $sender, $params);
                EmailInviteVerificationHandler::$email_body = $message->body;
                if($message->save()) {
                    $invite->model_name = self::REQUEST_LIMITED_CLASS_CONTROLLER_NAME;
                    $invite->model_method = self::REQUEST_LIMITED_CLASS_CONTROLLER_METHOD;
                    $invite->recipient_id = $recipient->id;
                    $invite->message_id = $message->id;
                    $invite->params = Json::encode($params);
                    $invite->save();
                    VerificationService::setPlayload(['invite_id' => $invite->id, 'user_id' => $recipient->id]);
                }
                break;
            }
        }
        $verificationService = new VerificationService();
        $status = $verificationService->send($recipient, new EmailInviteVerificationHandler(), 'auth/invite');
        if($status === VerificationService::SUCCESSFULLY_SEND) return true;
        return false;
    }
}