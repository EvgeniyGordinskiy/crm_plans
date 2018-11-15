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
});
