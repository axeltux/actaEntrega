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
//Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', '\App\Http\Controllers\Auth\LoginController@login');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home', 'HomeController@oficios')->name('home');

Route::get('listusers', 'HomeController@listaUsuarios')->name('listusers');

Route::delete('listusers/{id}', ['as'=>'borraUser', 'uses'=>'HomeController@destroy']);

Route::post('borraUsuario', 'HomeController@borraUsuario')->name('borraUsuario');

Route::get('firma/{oficio}', 'HomeController@efirma')->name('firma');

Route::get('editUser/{id}', 'HomeController@editUser')->name('editUser');

Route::post('updateUser/{id}', 'HomeController@updateUser')->name('updateUser');

Route::get('obtenerDatosEmpleado/{id}', 'HomeController@obtenerDatosEmpleado')->name('obtenerDatosEmpleado');

Route::get('listaLotes/{oficio}', 'HomeController@listalotes')->name('listaLotes');

Route::get('firmaLotes/{oficio}', 'HomeController@firmaLotes')->name('firmaLotes');

Route::post('sello', 'HomeController@sello')->name('sello');

Route::post('guardaEstado', 'HomeController@guardaEstado')->name('guardaEstado');

Route::post('statusOficio', 'HomeController@statusOficio')->name('statusOficio');

Route::get('pdf/{oficio}/{tipo}', 'PdfController@generaPDF')->name('pdf');

Route::get('documento/{oficio}', 'HomeController@docFirmado')->name('documento');