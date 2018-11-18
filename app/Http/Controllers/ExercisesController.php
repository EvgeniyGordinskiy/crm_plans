<?php
namespace App\Http\Controllers;

use App\Http\Requests\Exercise\CreateExerciseRequest;
use App\Models\Exercise;
use App\Models\ExerciseInstances;

class ExercisesController extends Controller
{
    public function index()
    {
        return $this->respondWithData(ExerciseInstances::all());
    }

    public function create_edit(CreateExerciseRequest $request)
    {
        $exercise = new Exercise();
        if ($request->id) {
            $exercise = Exercise::whereId($request->id)->firstOrFail();
//            $exIns = ExerciseInstances::whereExerciseId($exercise->id)->wherePlanId($request->plan_id)->firstOrFail();
//            $exercise->inst_id = $exIns->id;
            $exercise->update(['exercise_name' => $request->exercise_name]);
        } else {
            $exercise->exercise_name = $request->exercise_name;
            $exercise->save();
        }
        if ($exercise->id) {
            $view = view('parts.exercise', ['exercises' => [$exercise]])->render();
            return $this->respondWithData($view);
        }
        return $this->respondWithError();
    }

    public function delete($id)
    {
        $ex = Exercise::whereId($id)->firstOrFail();
        $ex->delete();
        return $this->respondWithSuccess('ok');
    }
}