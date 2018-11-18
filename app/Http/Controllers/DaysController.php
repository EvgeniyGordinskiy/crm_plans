<?php
namespace App\Http\Controllers;

use App\Http\Requests\Day\AddExerciseRequest;
use App\Http\Requests\Day\CreateDayRequest;
use App\Models\Exercise;
use App\Models\ExerciseInstances;
use App\Models\PlanDays;
use Illuminate\Support\Facades\DB;

class DaysController extends Controller
{
    public function index($plan_id)
    {
        return $this->respondWithData(PlanDays::wherePlanId($plan_id)->get());
    }

    public function create_edit(CreateDayRequest $request)
    {
        $day = new PlanDays();
        if ($request->id) {
            $day = PlanDays::whereId($request->id)->firstOrFail();
            $day->update(['day_name' => $request->day_name]);
        } else {
            if (!$request->plan_id) {
                throw new \Exception('Missed required parameter - plan_id');
            }
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

    public function add_exercise(AddExerciseRequest $request)
    {
        $exerciseInst = new ExerciseInstances();
        $minOrder = ExerciseInstances::whereDayId($request->day_id)->min('order');
        $exerciseInst->fill([
            'day_id' => $request->day_id,
            'exercise_id' =>  $request->exercise_id,
            'order' => $minOrder ? $minOrder - 50000 : 1008008000,
        ]);
        if($exerciseInst->save()) {
            $exercises = (new PlanDays())->day_exercises($request->day_id);
            $view = view('parts/exercise', compact('exercises'))->render();
            return $this->respondWithData($view);
        }
        return $this->respondWithError();
    }

    public function delete_day_exercise($id)
    {
        $dayE = ExerciseInstances::whereId($id)->firstOrFail();
        $dayE->delete();
        return $this->respondWithSuccess('ok');
    }

    public function delete_day($id)
    {
        $day = PlanDays::whereId($id)->firstOrFail();
        $day->delete();
        return $this->respondWithSuccess('ok');
    }
}