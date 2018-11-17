<?php
namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Plan;
use App\Models\PlanDays;

Class PartsController extends Controller
{
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

    public function edit_plan($plan_id)
    {
        $plan = Plan::whereId($plan_id)->firstOrFail();
        $days = PlanDays::wherePlanId($plan_id)->get();
        $view = view('parts.modals.edit-plan', compact('plan', 'days'))->render();
        return $this->respondWithData($view);
    }
}