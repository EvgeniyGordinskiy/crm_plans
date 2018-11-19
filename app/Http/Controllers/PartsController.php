<?php
namespace App\Http\Controllers;

use App\Http\Requests\Part\ChangeOrderRequest;
use App\Models\Exercise;
use App\Models\ExerciseInstances;
use App\Models\Plan;
use App\Models\PlanDays;
use App\Models\User;
use Illuminate\Support\Facades\DB;

Class PartsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function create_plan_exercise()
    {
        $type = $_GET['type'] ?? 'create';
        $source = $_GET['source'] ?? null;
        $id = $_GET['id'] ?? null;
        $plan_id = $_GET['plan_id'] ?? null;
        $item = null;
        if ($type === 'edit' && $id && $source) {
            switch ($source) {
                case 'plan': {
                    $item = Plan::whereId($id)->firstOrFail();
                    break;
                }
                case 'exercise': {
                    $item = Exercise::whereId($id)->firstOrFail();
                    break;
                }
            }
        }
        if ($source === 'day') {
            if (!$plan_id) {
                throw new \Exception('plan_id missed or invalid.');
            }
        }
        $view = view('parts.modals.create-plan-exercise', compact('type','source', 'item', 'plan_id'))->render();
        return $this->respondWithData($view);
    }

    /**
     * @param $plan_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit_plan($plan_id)
    {
        $item = Plan::whereId($plan_id)->firstOrFail();
        $days = $item->get_days_with_exercises();
        $exercises = Exercise::all();
        $source = 'plan';
        $view = view('parts.modals.edit-plan', compact('item', 'days', 'exercises', 'source'))->render();
        return $this->respondWithData($view);
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit_user($user_id)
    {
        $item = User::whereId($user_id)->firstOrFail();
        $plans = $item->plans;
        foreach($plans as $plan) {
            $plan->user_id = intval($user_id);
        }
        $source = 'user';
        $view = view('parts.modals.edit-plan', compact('item', 'plans', 'source'))->render();
        return $this->respondWithData($view);
    }

    /**
     * @param ChangeOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function change_order(ChangeOrderRequest $request)
    {
        if ($request->resource === 'day') {
            $item = PlanDays::whereId($request->item_id)->firstOrFail();
            $indexAfter = PlanDays::whereId($request->after_item_id)->first();
            $indexBefore = PlanDays::whereId($request->before_item_id)->first();
        } else if ($request->resource === 'exercise') {
            $item = ExerciseInstances::whereId($request->item_id)->firstOrFail();
            $indexAfter = ExerciseInstances::whereId($request->after_item_id)->first();
            $indexBefore = ExerciseInstances::whereId($request->before_item_id)->first();
        } else {
            return $this->respondWithError();
        }
        if ($indexAfter && $indexBefore) {
            $diff = $indexAfter->order  -  $indexBefore->order;
            $newIndex = $indexAfter->order - intval($diff/2*1.3);
            $item->update(['order' => $newIndex]);
        } else if ($indexBefore) {
            $item->update(['order' => $indexBefore->order + 1008008]);
        } else if ($indexAfter) {
            $item->update(['order' => $indexAfter->order  - 10000]);
        }

        if ($request->resource === 'day') {
            $days = (new Plan())->get_days_with_exercises($request->parent_id);
            $view = view('parts.day', compact('days'))->render();
        }
        if ($request->resource === 'exercise') {
            $exercises = (new PlanDays())->day_exercises($request->parent_id);
            $view = view('parts/exercise', compact('exercises'))->render();
        }
        return $this->respondWithData($view);
    }

    /**
     * @param $source
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function confirm_modal($source, $id)
    {
        if (!$source || !$id) {
            throw new \Exception('Missed required params');
        }
        $view = view('parts.modals.confirm', compact('source', 'id'))->render();
        return $this->respondWithData($view);
    }
}