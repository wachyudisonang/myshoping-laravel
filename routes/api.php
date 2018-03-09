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

Route::group(['middleware' => 'api'], function() {
    Route::get('user', 'UserController@getUser');
	Route::get('all', 'ProductController@getAllPurchases');



	
	// api/get?type=purchase
	Route::get('get', 'RequestsController@getEntity');
	// api/add/purchase?product=5&UnitPrice=7987897&qty=8&payment=9
	Route::post('add/{type}', 'RequestsController@addEntity');
	// api/edit?type=product&key=FITTI_56&someupdate=2
	Route::get('edit', 'RequestsController@editEntity');
	// api/delete?type=bank&key=BCA
	Route::get('delete', 'RequestsController@deleteEntity');
	// api/filter?type=product&key=FITTI_56
	Route::get('filter', 'RequestsController@filterEntity');
		



    Route::get('cart', 'ProductController@getCart');
    Route::post('add-cart', 'ProductController@addToCart');
    Route::get('remove-cart', 'ProductController@removeCart');
});