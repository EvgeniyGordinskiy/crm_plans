<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlanDays extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function day_exercises($dayId = null)
    {
        $dayId = $this->id ?? $dayId;
        $exercises = DB::select(DB::raw("SELECT ei.exercise_id as id, ei.day_id, ei.id as inst_id, e.exercise_name as exercise_name FROM `exercise_instances` as ei 
                                            JOIN `exercises` as e on ei.exercise_id = e.id
                                            WHERE ei.day_id = $dayId
                                            ORDER BY ei.order DESC"));
        return $exercises;
    }
}
