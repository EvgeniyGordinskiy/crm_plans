<?php
namespace App\Services\Verification\Handlers;
use App\Contracts\VerificationHandler;
use App\Http\Requests\Plan\AddUserRequest;
use App\Mail\InviteMail;
use App\Models\Invite;
use App\Models\User;
use App\Models\UsersVerification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailInviteVerificationHandler implements VerificationHandler
{

    public static $email_body = '';
    static $URL_TOKEN;

    /**
     * @param User $user
     * @param String $token
     * @param String $redirectPath
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public function send(User $user, String $token, $redirectPath)
    {
        if (!$redirectPath) return false;
        self::$URL_TOKEN = env('APP_URL').$redirectPath.'?token='.$token;
        return Mail::to($user->email)
            ->send(new InviteMail($user, $token, self::$email_body, env('APP_URL').$redirectPath));
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
            if (isset($params->plan_id)) {
                $request =  new AddUserRequest();
            }
            if ($request) {
                $request->request->add((array) $params);
                try{
                $object->$method($request);
                }catch(\Exception $e) {
                    Log::emergency($e->getMessage().'; line: '.$e->getLine());
                }
            } else {
                throw new \Exception();
            }
        }
        try{
            $invite->delete();
            $verification->delete();
        }catch(\Exception $e) {
            Log::emergency($e->getMessage().'; line: '.$e->getLine());
        }
    }
}