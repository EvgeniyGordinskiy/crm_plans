<?php
namespace App\Services\Verification\Handlers;

use App\Contracts\VerificationHandler;
use App\Http\Controllers\ClasscController;
use App\Http\Requests\Classc\AddUserToClasscRequest;
use App\Http\Requests\ParentUser\AddUserToFamily;
use App\Http\Requests\Program\AddUserToProgramRequest;
use App\Http\Requests\School\AddUserToSchoolRequest;
use App\Http\Requests\Subscription\AddUserSubscriptionRequest;
use App\Jobs\SendInvite;
use App\Jobs\SendNotificationEmail;
use App\Jobs\SendVerificationEmail;
use App\Models\EmailConfirmations;
use App\Models\Invite;
use App\Models\Message;
use App\Models\User;
use App\Models\UsersVerification;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\Log;
use PHPUnit\Util\Json;

class EmailInviteVerificationHandler implements VerificationHandler
{

    public static $email_body = '';

    /**
     * @param User $user
     * @param String $token
     * @param String $redirectPath
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public function send(User $user, String $token, $redirectPath)
    {
        if (!$redirectPath) return false;
        return dispatch(new SendInvite($user, $token, self::$email_body, env('APP_FRONT_URL').'/'.$redirectPath));
    }

    /**
     * Create token
     * @return string
     */
    public function createToken()
    {
        $token = sha1(time());
        return $token;
    }

    public function confirm(User &$user)
    {
        $verification = UsersVerification::whereUserId($user->id)->whereClassName(self::class)->first();
        if (!$verification) throw new \Exception('Verification not found');
        $payload = json_decode($verification->playload);
        $invite = Invite::find($payload->invite_id);
        $params = json_decode($invite->params);
        if ((!$invite->model_name || !$invite->model_method) && $invite->redirect_path) {
            throw new \Exception('Wrong invites parameters');
        }
        if($invite->model_name && $invite->model_method) {
            $class = $invite->model_name;
            $object = new $class();
            $method = $invite->model_method;
            $request = false;
            if (isset($params->parent_id)) {
                $request =  new AddUserToFamily();
            }
            if (isset($params->program_id)) {
                $request =  new AddUserToProgramRequest();
            }
            if (isset($params->school_id)) {
                $request =  new AddUserToSchoolRequest();
            }
            if (isset($params->subscription_id)) {
                $request =  new AddUserSubscriptionRequest();
            }
            if ($request) {
                $request->request->add((array) $params);
                try{
                $object->$method($request);
                if (isset($params->program_id) && isset($params->class_id)) {
                    $requestClass = new AddUserToClasscRequest();
                    $requestClass->request->add(['classc_id' => $params->class_id, 'user_id' => $user->id]);
                    app(ClasscController::class)->add_user_to_class($requestClass);
                }
                }catch(\Exception $e) {
                    Log::emergency($e->getMessage().'; line: '.$e->getLine());
                }
            } else {
                throw new \Exception();
            }
        }
        try{
            $message = $invite->message;
            $invite->delete();
            $message->delete();
            $verification->delete();
        }catch(\Exception $e) {
            Log::emergency($e->getMessage().'; line: '.$e->getLine());
        }
    }
}