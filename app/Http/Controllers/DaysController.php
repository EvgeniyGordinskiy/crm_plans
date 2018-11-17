<?php
namespace App\Http\Controllers;

use App\Http\Requests\Day\CreateDayRequest;
use App\Models\PlanDays;

class DaysController extends Controller
{
    public function index($plan_id)
    {
        return $this->respondWithData(PlanDays::wherePlanId($plan_id)->get());
    }

    public function create(CreateDayRequest $request)
    {
        $day = new PlanDays();
        if ($request->id) {
            $day = PlanDays::whereId($request->id)->firstOrFail();
            $day->update(['day_name' => $request->day_name]);
        } else {
            $minOrder = PlanDays::wherePlanId($request->plan_id)->min('order');
            $day->fill([
               'plan_id' => $request->plan_id,
               'day_name' => $request->day_name,
               'order' => $minOrder ? $minOrder - 50000 : 1008008000,
            ]);
            $day->save();
        }
        if ($day->id) {
            $view = view('parts.day', ['days' => [$day]])->render();
            return $this->respondWithData($view);
        }
        return $this->respondWithError();
    }
}