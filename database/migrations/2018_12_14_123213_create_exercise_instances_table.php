<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExerciseInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercise_instances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exercise_id', false, true);
            $table->foreign('exercise_id')->references('id')->on('exercises')->onDelete('cascade');
            $table->integer('day_id', false, true);
            $table->foreign('day_id')->references('id')->on('plan_days')->onDelete('cascade');
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exercise_instances', function(Blueprint $t) {
           $t->dropForeign(['exercise_id']);
        });
        Schema::dropIfExists('exercise_instances');
    }
}
