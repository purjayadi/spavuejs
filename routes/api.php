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
// Auth
Route::post('auth/signin', 'AuthController@authenticate');
Route::post('auth/register', 'AuthController@register');
Route::get('logout',  'AuthController@logout')->middleware('jwt.refresh');
Route::group(['middleware' => 'jwt'], function () {
   // Protected routes
  Route::resource('index', 'IndexController');
  Route::resource('/menu', 'MenuController', ['except' => 'show','store','update']);
  Route::get('/editmenu/{id}','MenuController@edit');
  Route::delete('/menu/{id}', 'MenuController@destroy');
  Route::resource('/submenu', 'SubmenuController', ['except' => 'show', 'store']);
  Route::get('/editsubmenu/{id}','SubmenuController@edit');
  Route::delete('/submenu/{id}','SubmenuController@destroy');
  Route::resource('/halaman', 'HalamanController', ['except' => 'show','store','update']);
  Route::get('/edithalaman/{id}', 'HalamanController@edit');
  Route::delete('/halaman/{id}', 'HalamanController@destroy');

});

  Route::get('/getmenu','MenuController@show');
  Route::get('/searchmenu','MenuController@search');

  Route::get('/getsubmenu','SubmenuController@show');
  Route::get('/getidmenu','SubmenuController@getmenu');
  Route::get('/searchsubmenu','SubmenuController@search');
  Route::get('/gethalaman', 'HalamanController@show');
  Route::get('/searchhalaman', 'HalamanController@search');



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
