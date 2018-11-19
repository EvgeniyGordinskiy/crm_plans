<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 *
 * Routes for Plans
 *
 */

Route::group([
    'as' => 'plans.',
    'prefix' => 'plans',
], function(){
    Route::get('', ['as' => 'index', 'uses' => 'PlansController@index']);
    Route::get('preview/{user_id?}', ['as' => 'index', 'uses' => 'PlansController@preview']);
    Route::get('user/{user_id}', ['as' => 'index', 'uses' => 'PlansController@users_plans']);
    Route::post('', ['as' => 'create', 'uses' => 'PlansController@create']);
    Route::put('', ['as' => 'edit', 'uses' => 'PlansController@edit']);
    Route::delete('{id}', ['as' => 'deletePlan', 'uses' => 'PlansController@delete']);
    Route::delete('user/{pla_id}/{user_id}', ['as' => 'deleteUserPlan', 'uses' => 'PlansController@delete_user']);
});

/**
 *
 * Routes for Days
 *
 */

Route::group([
    'as' => 'days.',
    'prefix' => 'days',
], function(){
    Route::get('', ['as' => 'index', 'uses' => 'DaysController@index']);
    Route::post('', ['as' => 'create', 'uses' => 'DaysController@create_edit']);
    Route::post('exercise', ['as' => 'addExercise', 'uses' => 'DaysController@add_exercise']);
    Route::delete('{id}', ['as' => 'deleteDAy', 'uses' => 'DaysController@delete_day']);
    Route::delete('exercise/{id}', ['as' => 'deleteDayExercise', 'uses' => 'DaysController@delete_day_exercise']);
    Route::put('', ['as' => 'edit', 'uses' => 'DaysController@create_edit']);
});

/**
 *
 * Routes for Exercises
 *
 */

Route::group([
    'as' => 'exercises.',
    'prefix' => 'exercises',
], function(){
    Route::get('', ['as' => 'index', 'uses' => 'ExercisesController@index']);
    Route::post('', ['as' => 'create', 'uses' => 'ExercisesController@create_edit']);
    Route::put('', ['as' => 'edit', 'uses' => 'ExercisesController@create_edit']);
    Route::delete('{exercise_id}', ['as' => 'edit', 'uses' => 'ExercisesController@delete']);
});

/**
 *
 * Routes for Users
 *
 */

Route::group([
    'as' => 'users.',
    'prefix' => 'users',
], function(){
    Route::get('', ['as' => 'index', 'uses' => 'UsersController@index']);
    Route::post('', ['as' => 'index', 'uses' => 'UsersController@create']);
    Route::put('', ['as' => 'edit', 'uses' => 'UsersController@edit']);
    Route::delete('{id}', ['as' => 'index', 'uses' => 'UsersController@delete']);
});


/**
 *
 * Routes for Parts
 *
 */

Route::group([
    'as' => 'part.',
    'prefix' => 'part',
], function(){
    Route::get('createPlanExercise', ['as' => 'createPlanExercise', 'uses' => 'PartsController@create_plan_exercise']);
    Route::get('editPlan/{plan_id}', ['as' => 'editPlan', 'uses' => 'PartsController@edit_plan']);
    Route::get('editUser/{user_id}', ['as' => 'editPlan', 'uses' => 'PartsController@edit_user']);
    Route::get('confirm/{source}/{id}', ['as' => 'openConfirmModal', 'uses' => 'PartsController@confirm_modal']);
    Route::post('order', ['as' => 'changeOrder', 'uses' => 'PartsController@change_order']);
});


/**
 *
 * Routes for Invites
 *
 */

Route::group([
    'as' => 'invite.',
    'prefix' => 'invite',
], function(){
    Route::post('', ['as' => 'sendInvite', 'uses' => 'InviteController@send_invite']);
    Route::post('check', ['as' => '.check', 'uses' => 'InviteController@check_token']);
});
