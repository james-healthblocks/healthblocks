<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('client.search', [
    	'dashboard'=> true,
    	'results'=>null,
        'health_network'=>null,
        'uic'=>null
    ]);
})->middleware('auth');

Route::get('/{origin}/database/retrieve', 'DatabaseViewController@retrieve');

Route::group([
	'prefix' => 'client',
	'middleware' => ['auth']
	], function(){
		Route::match(['get', 'post'], '/records', 'ClientController@search');

		Route::match(['get', 'post'], '/records/{uic}/new', 'ClientController@newClient')->name('newClient');

		Route::match(['get', 'post'], '/records/{uic}/{id}', 'ClientController@listConsult')->name('listConsult');

		Route::match(['get', 'post'], '/records/{uic}/{id}/new', 'ClientController@newConsult')->name('newConsult');

		Route::match(['get', 'post'], '/records/{uic}/{id}/{pk}', 'ClientController@editConsult')->name('editConsult');

		Route::get('/database', 'ClientController@database');

		Route::get('/duplicates/list', 'ClientController@listDuplicates');

		Route::post('/duplicates/resolve', 'ClientController@resolveDuplicates');

		Route::get('/duplicates/marked', 'ClientController@markedDuplicates');

		Route::get('/duplicates', 'ClientController@duplicates');


		Route::get('/reports', 'ClientController@reports');
		Route::post('/reports/display', 'ClientController@displayReport');
		Route::post('/reports/display/download', 'ReportsController@clientDownload');
});

Route::group([
	'prefix' => 'inventory',
	'middleware' => ['auth']
	], function(){

		Route::get('/encode', 'InventoryController@encode');
		Route::post('/encode', 'InventoryController@save');
		Route::get('/encode/retrieve/{year}/{month}/{category}', 'InventoryController@retrieve');

		Route::get('/reports', 'InventoryController@reports');
		Route::post('/reports/display', 'InventoryController@displayReport');
		Route::post('/reports/display/download', 'ReportsController@InventoryDownload');

		Route::get('/database', 'InventoryController@database');
		// Route::get('/database/retrieve', 'InventoryController@databaseRetrieve');
});

Route::group([
	'prefix' => 'services',
	'middleware' => ['auth']
	], function(){

		Route::get('/encode', 'ServicesController@encode');
		Route::post('/encode', 'ServicesController@save');

		Route::get('/reports', 'ServicesController@reports');
		Route::post('/reports/display', 'ServicesController@displayReport');
		Route::post('/reports/display/download', 'ReportsController@ServiceDownload');

		Route::get('/database', 'ServicesController@database');
});

Route::group([
	'prefix' => 'reached',
	'middleware' => ['auth']
	], function() {

		Route::get('/import', 'ReachedController@import');

		Route::get('/reports', 'ReachedController@reports');
		Route::post('/reports/display', 'ReachedController@displayReport');
		Route::post('/reports/display/download', 'ReachedController@download');
});

Route::group([
	'prefix' => 'sti_graphs',
	'middleware' => ['auth']
	], function(){

		Route::get('/', 'STIGraphsController@view');
		Route::post('/display', 'STIGraphsController@display');
		Route::post('/download', 'STIGraphsController@download');
		Route::post('/downloadPDF', 'STIGraphsController@downloadPDF');
});

Route::group([
	'prefix' => 'account',
	'middleware' => ['auth']
	], function() {
		// Manage current user information
		Route::get('/profile', 'AccountController@profile');
		Route::post('/profile', 'AccountController@updateProfile');

		// For Admin only: ciew all users
		Route::get('/users', 'AccountController@users');

		// For Admin only: edit user 
		Route::get('/user/{mode}/{id?}', 'AccountController@user');
		Route::post('/user/{id}', 'AccountController@updateUser');

		// For Admin only: create user 
		Route::post('/create', 'AccountController@create');

		// For Admin only: delete user 
		Route::get('/delete/{id?}', 'AccountController@delete');

		Route::match(['get', 'post'], '/clinic', 'AccountController@clinic');
});

Route::group([
	'prefix' => 'case-management'
	], function() {
		Route::group([
			'prefix' => 'sti'
			], function() {
				Route::get('/', function () {
				    return view('case.sti-home');
				});

				Route::group([
					'prefix' => 'client',
					'middleware' => ['auth']
					], function(){
						Route::match(['get', 'post'], '/records', 'ClientController@search');

						Route::match(['get', 'post'], '/records/{uic}/new', 'ClientController@newClient')->name('newClient');

						Route::match(['get', 'post'], '/records/{uic}/{id}', 'ClientController@listConsult')->name('listConsult');

						Route::match(['get', 'post'], '/records/{uic}/{id}/new', 'ClientController@newConsult')->name('newConsult');

						Route::match(['get', 'post'], '/records/{uic}/{id}/{pk}', 'ClientController@editConsult')->name('editConsult');

						Route::get('/database', 'ClientController@database');

						Route::get('/duplicates/list', 'ClientController@listDuplicates');

						Route::post('/duplicates/resolve', 'ClientController@resolveDuplicates');

						Route::get('/duplicates/marked', 'ClientController@markedDuplicates');

						Route::get('/duplicates', 'ClientController@duplicates');


						Route::get('/reports', 'ClientController@reports');
						Route::post('/reports/display', 'ClientController@displayReport');
						Route::post('/reports/display/download', 'ReportsController@clientDownload');
				});

				Route::group([
					'prefix' => 'inventory',
					'middleware' => ['auth']
					], function(){

						Route::get('/encode', 'InventoryController@encode');
						Route::post('/encode', 'InventoryController@save');
						Route::get('/encode/retrieve/{year}/{month}/{category}', 'InventoryController@retrieve');

						Route::get('/reports', 'InventoryController@reports');
						Route::post('/reports/display', 'InventoryController@displayReport');
						Route::post('/reports/display/download', 'ReportsController@InventoryDownload');

						Route::get('/database', 'InventoryController@database');
						// Route::get('/database/retrieve', 'InventoryController@databaseRetrieve');
				});

				Route::group([
					'prefix' => 'services',
					'middleware' => ['auth']
					], function(){

						Route::get('/encode', 'ServicesController@encode');
						Route::post('/encode', 'ServicesController@save');

						Route::get('/reports', 'ServicesController@reports');
						Route::post('/reports/display', 'ServicesController@displayReport');
						Route::post('/reports/display/download', 'ReportsController@ServiceDownload');

						Route::get('/database', 'ServicesController@database');
				});

				Route::group([
					'prefix' => 'reached',
					'middleware' => ['auth']
					], function() {

						Route::get('/import', 'ReachedController@import');

						Route::get('/reports', 'ReachedController@reports');
						Route::post('/reports/display', 'ReachedController@displayReport');
						Route::post('/reports/display/download', 'ReachedController@download');
				});

				Route::group([
					'prefix' => 'sti_graphs',
					'middleware' => ['auth']
					], function(){

						Route::get('/', 'STIGraphsController@view');
						Route::post('/display', 'STIGraphsController@display');
						Route::post('/download', 'STIGraphsController@download');
						Route::post('/downloadPDF', 'STIGraphsController@downloadPDF');
				});

				Route::get('/{origin}/database/retrieve', 'DatabaseViewController@retrieve');
		});
});

// GET route
// Route::get('login', function() {
//   return View::make('auth.login');
// })->middleware('guest');

Route::group([
	'prefix' => 'portal',
	'middleware' => ['auth']
	], function() {

		Route::get('/{uic}/{hpid}', 'HealthNetworkController@portal');

		Route::get('/{uic}/{hpid}/{txn_date}', 'HealthNetworkController@record');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->middleware('auth');

Route::post('account', 'AccountController@register');

Route::post('/export/{mode}', 'FileController@export');
Route::post('/{origin}/import/{ext}', 'FileController@importCsv');

Route::get('/password/email', 'AccountController@showEmailForm');
Route::post('/password/email', 'AccountController@checkEmail');
Route::get('/password/resetform/{email}', 'AccountController@showResetForm')->name('resetForm');
Route::post('/password/reset', 'AccountController@resetPassword');