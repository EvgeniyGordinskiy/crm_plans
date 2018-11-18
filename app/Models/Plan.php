<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    /**
     * @param null $plan_id
     * @return mixed
     */
    public function get_days_with_exercises($plan_id = null)
    {
        $plan_id = $this->id ?? $plan_id;
        $days = PlanDays::wherePlanId($plan_id)->orderBy('order', 'desc')->orderBy('updated_at', 'desc')->get();
        $exercisesDays = DB::select(DB::raw("SELECT ei.exercise_id as id, ei.day_id, ei.id as inst_id, pd.plan_id as plan_id, e.exercise_name as exercise_name FROM `exercise_instances` as ei 
                                            JOIN `exercises` as e on ei.exercise_id = e.id
                                            JOIN `plan_days` as pd on ei.day_id = pd.id
                                            WHERE pd.plan_id = $plan_id
                                            ORDER BY ei.order DESC"));
        $sortedExercisesDays = [];
        foreach($exercisesDays as $ex) {
            if (!isset($sortedExercisesDays[$ex->day_id])) {
                $sortedExercisesDays[$ex->day_id] = [];
            }
            $sortedExercisesDays[$ex->day_id][] = $ex;
        }
        foreach($days as $day) {
            $day->exercises = $sortedExercisesDays[$day->id] ?? [];
        }
        return $days;
    }
}
