<?php
namespace App\Http\Controllers;

use App\Http\Requests\Plan\CreatePlanRequest;
use App\Http\Requests\Plan\EditPlanRequest;
use App\Models\Plan;
use App\Models\User;
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
        $plans =  Plan::all();
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

    public function delete($id)
    {
        $plan = Plan::whereId($id)->firstOrFail();
        $plan->delete();
        return $this->respondWithSuccess('ok');
    }
}