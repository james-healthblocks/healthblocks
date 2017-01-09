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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/login', 'ApiController@login');

Route::get('/test', function (Request $request) {
    return response()->json(['hello' => 'world']);
})->middleware('api-token');

Route::group([
    'prefix' => 'address',
    'middleware' => []
    ], function () {

        Route::get('/all', 'AddressApiController@all');

        Route::get('/city/{id}', 'AddressApiController@city');

        Route::get('/province/{id}', 'AddressApiController@province');

        Route::get('/region/{id}', 'AddressApiController@region');
});

Route::group([
    'prefix' => 'portal',
    'middleware' => []
    ], function () {
        Route::get('/{uic}/{hpid}/{txn_date}', 'HealthPortalApiController@record');
});