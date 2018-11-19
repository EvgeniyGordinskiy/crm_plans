<?php
namespace App\Http\Controllers;

use App\Http\Requests\Invite\CheckTokenRequest;
use App\Http\Requests\Invite\SendInviteRequest;
use App\Models\InviteType;
use App\Models\Plan;
use App\Models\User;
use App\Services\Invite\InviteService;
use App\Services\Verification\Handlers\EmailInviteVerificationHandler;
use App\Services\Verification\VerificationService;

class InviteController extends Controller
{
    /**
     * @param SendInviteRequest $request
     * @param InviteService $inviteService
     * @return \Illuminate\Http\JsonResponse
     */
    public function send_invite(SendInviteRequest $request, InviteService $inviteService)
    {
        $user = User::find($request->user_id);
        $plan = Plan::find($request->plan_id);
        $inviteType = InviteType::whereName(InviteType::PLAN)->firstOrFail();
        $params = [
            'school_id' => $plan->id,
            'user_id' => $request->user_id,
            'plan_name' => $plan->plan_name,
            'plan_id' => $plan->id,
        ];
        $resp = $inviteService->create($user, $inviteType, $params);
        if($resp) {
            return $this->respondWithData(['url_token' => EmailInviteVerificationHandler::$URL_TOKEN]);
        }
        return $this->respondWithError();
    }

    /**
     * @param CheckTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_token(CheckTokenRequest $request)
    {
        $result = VerificationService::checkToken($request->token);
        if (!$result) {
            return $this->respondWithError('Token is Expired', 401);
        }
        return $this->respondWithSuccess('ok');
    }
}