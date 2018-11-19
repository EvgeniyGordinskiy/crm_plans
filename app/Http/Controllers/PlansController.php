<?php
namespace App\Http\Controllers;

use App\Http\Requests\Plan\AddUserRequest;
use App\Http\Requests\Plan\CreatePlanRequest;
use App\Http\Requests\Plan\EditPlanRequest;
use App\Models\Plan;
use App\Models\User;
use App\UserPlans;
use Illuminate\Support\Facades\DB;

class PlansController extends Controller
{
    public function index()
    {
        $plans =  DB::table('plans')->get();
        $view = view('pages.plans', ['plans' => $plans])->render();
        return $this->respondWithData($view);
    }

    public function preview($user_id = null)
    {
        $usersPlans = [];
        if ($user_id) {
            $usersPansSubsjects = DB::select(DB::raw("SELECT plan_id as id, user_id from user_plans where user_id = $user_id"));
            foreach ($usersPansSubsjects as $plan) {
                $usersPlans[] = $plan->id;
            }
        }
        $plans = Plan::whereNotIn('id', $usersPlans)->get();

        $view = view('parts.plan-view-body', ['plans' => $plans, 'user_id' => $user_id])->render();
        return $this->respondWithData($view);
    }


    public function users_plans($user_id)
    {
        $user = User::whereId($user_id)->firstOrFail();
        $plans =  $user->plans;
        $view = view('parts.plan-view-body', ['plans' => $plans])->render();
        return $this->respondWithData($view);
    }

    public function create(CreatePlanRequest $request)
    {
        $plan = new Plan();
        $plan->fill([
            'plan_name' => $request->plan_name,
            'plan_description' => $request->plan_description,
            'plan_difficulty' => $request->plan_difficulty
        ]);
        if ($plan->save()) {
            $view = view('parts.plan', ['plans' => [$plan]])->render();
            return $this->respondWithData($view);
        }
        return $this->respondWithError();
    }

    public function edit(EditPlanRequest $request)
    {
        $plan = Plan::find($request->id);
        $data = [];
        if ($request->plan_name) {
            $data['plan_name'] = $request->plan_name;
        }
        if ($request->plan_description) {
            $data['plan_description'] = $request->plan_description;
        }
        if ($request->plan_difficulty) {
            $data['plan_difficulty'] = $request->plan_difficulty;
        }
        if ($data) {
            $plan->update($data);
        }

        $view = view('parts.plan', ['plans' => [$plan]])->render();
        return $this->respondWithData($view);
    }

    public function add_user(AddUserRequest $request)
    {
        UserPlans::create([
           'user_id' => $request->get('user_id'),
           'plan_id' => $request->get('plan_id'),
        ]);

        return $this->respondCreated();
    }

    public function delete_user($planId, $userId)
    {
        $planUser = UserPlans::wherePlanId($planId)->whereUserId($userId)->firstOrFail();
        DB::table('user_plans')->where('plan_id', $planId)->where('user_id', $userId)->delete();
        return $this->respondWithSuccess('ok');
    }

    public function delete($id)
    {
        $plan = Plan::whereId($id)->firstOrFail();
        $plan->delete();
        return $this->respondWithSuccess('ok');
    }
}