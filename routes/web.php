<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    // Home Routes 
    Route::get('/', 'HomeController@index');
    Route::get('/history', 'HomeController@history');
    Route::get('/history/{$id}', 'HomeController@historyShow');
    // End Home

    // Stock Routes
    // Route::get('/stock', 'StockController@index');

    Route::get('/branch', 'StockController@index');
    Route::get('/stock/branch/{code}', 'StockController@stock');
    Route::post('/stock/branch/{code}', 'StockController@stock');
    Route::get('/stock/excel/{code}', 'StockController@Excel');
    Route::get('/stock/excel/{code}/{date}', 'StockController@Excel');
    Route::get('/stock/create/{code}', 'StockController@create');
    Route::post('/stock/store/{code}', 'StockController@store');

    Route::get('/stock/{product:slug}', 'StockController@show');
    Route::delete('/stock/delete', 'StockController@destroy');
    Route::get('/stock/{product:id}/edit', 'StockController@edit');
    Route::patch('/stock/{product:id}/update', 'StockController@update');
    Route::get('/stock/{product:id}/market', 'StockController@market');
    // End Stock

    // Market
    Route::get('/market', 'StockController@market');
    Route::get('/market/buy', 'StockController@buy');
    Route::get('/market/sell', 'StockController@sell');
    Route::patch('/market/marketex', 'StockController@marketex');
    // End Market

    Route::get('/branch/create', 'BranchController@create')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::post('/branch/store', 'BranchController@store')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::get('/branch/{branch:code}', 'BranchController@show')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::delete('/branch/{branch}/delete', 'BranchController@destroy')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::get('/branch/{branch:code}/edit', 'BranchController@edit')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::patch('/branch/{branch:code}/edit', 'BranchController@update')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    // End Branch
});
