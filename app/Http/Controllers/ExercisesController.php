<?php
namespace App\Http\Controllers;

use App\Http\Requests\Exercise\CreateExerciseRequest;
use App\Models\Exercise;
use App\Models\ExerciseInstances;

class ExercisesController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->respondWithData(ExerciseInstances::all());
    }

    /**
     * @param CreateExerciseRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function create_edit(CreateExerciseRequest $request)
    {
        $exercise = new Exercise();
        if ($request->id) {
            $exercise = Exercise::whereId($request->id)->firstOrFail();
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

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $ex = Exercise::whereId($id)->firstOrFail();
        $ex->delete();
        return $this->respondWithSuccess('ok');
    }
}