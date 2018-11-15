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

/*Route::get('/', function () {
    return view('home');
});*/

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::post('/oficios', 'HomeController@oficios')->name('oficios');

Route::get('/listusers', 'HomeController@listaUsuarios')->name('listusers');

Route::delete('listusers/{id}', ['as'=>'borraUser', 'uses'=>'HomeController@destroy']);

Route::get('firma/{oficio}', 'HomeController@efirma')->name('firma');

Route::get('/listaLotes/{lotes}/{oficio}/{cerys}', 'HomeController@listalotes')->name('listaLotes');

Route::post('sello', 'HomeController@sello')->name('sello');

Route::post('statusOficio', 'HomeController@statusOficio')->name('statusOficio');

Route::get('pdf/{oficio}/{tipo}', 'PdfController@generaPDF')->name('pdf');

Route::get('documento/{oficio}', 'HomeController@docFirmado')->name('documento');