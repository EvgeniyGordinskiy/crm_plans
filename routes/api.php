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
    Route::post('', ['as' => 'create', 'uses' => 'PlansController@create']);
    Route::put('', ['as' => 'create', 'uses' => 'PlansController@edit']);
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
    Route::post('', ['as' => 'create', 'uses' => 'DaysController@create']);
    Route::put('', ['as' => 'create', 'uses' => 'DaysController@edit']);
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
});
