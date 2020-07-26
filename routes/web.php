<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect(route('calls.index'));
});

// Route::get('/test', 'TestController@index');
Route::get('/hard', 'TestController@hardRefresh');

Route::get('companies', 'CompanyController@index')->name('companies.index');
Route::post('companies', 'CompanyController@store')->name('companies.store');
Route::put('companies', 'CompanyController@update')->name('companies.update');
Route::delete('companies', 'CompanyController@destroy')->name('companies.destroy');

Route::get('calls', 'TestController@index')->name('calls.index');
Route::post('calls', 'CallController@store')->name('calls.store');
Route::put('calls', 'CallController@update')->name('calls.update');
Route::delete('calls', 'CallController@destroy')->name('calls.destroy');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');