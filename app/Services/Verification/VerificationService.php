<?php

namespace App\Services\Verification;

use App\Contracts\VerificationHandler;
use App\Models\User;
use App\Models\UsersVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class VerificationService
{
    /**
     * Life time token
     * @var int
     */
    private static $expiration_time = 500;

    /**
     * Default verification handler
     * @var string
     */
    private static $defaultHandler = 'App\Services\Verification\Handlers\EmailVerificationHandler';

    /**
     * Current verification handler
     * @var null
     */
    private static $currentHandler = null;

    /**
     * Class which called Verification Service
     * @var string
     */
    private static $calledClass = '';

    /**
     * @var string
     */
    private static $playload = '';

    /**
     *  Statuses of the service
     */
    const SUCCESSFULLY_SEND = 1;
    const ERROR_WHILE_SEND = 0;

    public static function send(User $user, VerificationHandler $handler = null, $redirectPath = '')
    {
        self::$calledClass = get_called_class();
        self::$currentHandler = $handler ?? new self::$defaultHandler();
        $token = self::$currentHandler->createToken();

        if(self::saveNewVerification($token, $user) && self::$currentHandler->send($user, $token, $redirectPath)) {
            return self::SUCCESSFULLY_SEND;
        }
        return self::ERROR_WHILE_SEND;
    }

    /**
     * Check token
     * @param $token
     * @return user.id or false.
     */
    public static function checkToken($token)
    {
        if (Cache::has($token)) {
            $verification = UsersVerification::whereToken($token)->whereUserId(Cache::get($token))->first();
            if($verification) {
                $user = $verification->user;
                $currentHandler = app($verification->class_name);
                $currentHandler->confirm($user);
                return $user;
            }
        }
        return false;
    }

    /**
     * @param array $playload
     */
    public static function setPlayload( Array $playload)
    {
        self::$playload = json_encode($playload);
    }

    /**
     * @param String $token
     * @param User $user
     * @return bool
     */
    private static function saveNewVerification(String $token, User $user)
    {
        $expiresAt = Carbon::now()
            ->addMinutes(self::$expiration_time);
        Cache::put($token, $user->id, $expiresAt);
        UsersVerification::whereUserId($user->id)->whereClassName(get_class(self::$currentHandler))->delete();
        $verification = new UsersVerification([
            'user_id'    => $user->id,
            'token'      => $token,
            'class_name' => get_class(self::$currentHandler),
            'playload'   => self::$playload,
        ]);
       return (boolean) $verification->save();
    }
}