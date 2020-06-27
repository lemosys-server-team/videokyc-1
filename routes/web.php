<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



//Auth::routes(['verify' => false,'register' => false]);

// Route::get('/{url?}', function(){
// 	return redirect('register');
// })->where(['url' => '|home'])->name('home');

if(env('API_URL')=="" || env('API_URL')==url('/')){
	Auth::routes(['register'=>false]);
	Route::get('index', 'HomeController@index')->name('index');
	Route::post('register', 'HomeController@register')->name('register');
}

Route::group(['middleware' => ['frontend']], function(){	
	Route::get('/{url?}', 'HomeController@index')->where(['url' => '|home'])->name('home');
});

Route::group(['middleware' => ['auth']], function(){
	Route::get('access-denied', function(){
		return view('access-denied');
	})->name('access-denied');
        Route::resource('profile', 'ProfileController')->only(['index', 'store']);
		Route::group(['middleware' => ['check_permission'],'namespace'=>'Admin','prefix'=>'admin', 'as' => 'admin.'], function(){	

		Route::get('dashboard', 'DashboardController@index')->name('dashboard');
		Route::resource('profile', 'ProfileController')->only(['index', 'store']);
        Route::resource('settings', 'SettingsController')->only(['index', 'store']);

		// For Users
		Route::resources([
			'users' => 'UsersController',
			'product_categories' => 'ProductCategories',
		]);
		
        Route::post('users/getUsers', 'UsersController@getUsers')->name('users.getUsers');
		Route::get('users/status/{user_id}', 'UsersController@status')->name('users.status');	

		// For product_categories sub_contractor
		Route::post('product_categories/getProductCategories', 'ProductCategories@getProductCategories')->name('product_categories.getProductCategories');
        Route::get('product_categories/status/{id}', 'ProductCategories@status')->name('product_categories.status');

       // For Countries
		Route::resource('countries', 'Countries')->except(['show']);
		Route::post('countries/list', 'Countries@getCountries')->name('countries.getCountries');
		Route::get('countries/status/{id}', 'Countries@status')->name('countries.status');	

         // For roles
		Route::resource('roles','RoleController');
		Route::get('roles/destroy/{id}', 'RoleController@destroy')->name('roles.destroy');
		Route::get('roles/status/{id}', 'RoleController@status')->name('roles.status');
	
        // For Cities
		Route::resource('cities', 'Cities')->except(['show']);
		Route::post('cities/list', 'Cities@getCities')->name('cities.getCities');
		Route::get('cities/status/{id}', 'Cities@status')->name('cities.status');
		Route::get('cities/updateDLS/{id}', 'Cities@updateDLS')->name('cities.updateDLS');	

		Route::resource('components','ComponentController');
    	Route::get('components/destroy/{id}', 'ComponentController@destroy')->name('components.destroy');
    	Route::get('components/status/{id}', 'ComponentController@status')->name('components.status');

    	Route::resource('schedules','Schedules');
    	Route::post('schedules/getSchedules', 'Schedules@getSchedules')->name('schedules.getSchedules');
    	Route::get('schedules/status/{id}', 'Schedules@status')->name('schedules.status');

	});

});