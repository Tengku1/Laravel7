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

    // Branch Only For Master !!
    Route::get('/branch', 'BranchController@index')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::get('/branch/create', 'BranchController@create')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::get('/branch/excel', 'BranchController@excel')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::post('/branch/store', 'BranchController@store')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::get('/branch/{branch:code}', 'BranchController@show')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::delete('/branch/{branch}/delete', 'BranchController@destroy')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::get('/branch/{branch:code}/edit', 'BranchController@edit')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::patch('/branch/{branch:code}/edit', 'BranchController@update')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    // End Branch

    // Market
    Route::get('/market', 'StockController@market');
    Route::get('/market/buy', 'StockController@buy');
    Route::get('/market/sell', 'StockController@sell');
    Route::patch('/market/marketex', 'StockController@marketex');
    // End Market

    // Products Only For MASTER !!
    Route::get('/product', 'ProductController@index')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/product/create', 'ProductController@create')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);


    Route::get('/product/edit/{product:slug}', 'ProductController@edit')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/product/excel', 'ProductController@excel')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/product/search', 'ProductController@search')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::patch('/product/{id}', 'ProductController@update')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::post('/product/store', 'ProductController@store')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::delete('/product/destroy', 'ProductController@destroy')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/product/{product:slug}', 'ProductController@show')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    // End Products

    // Stock Routes
    Route::get('/stock', 'StockController@index');
    Route::get('/stock/create', 'StockController@create');
    Route::get('/stock/create/{code}', 'StockController@create');
    Route::delete('/stock/delete', 'StockController@destroy');
    Route::get('/stock/detail/{product:slug}', 'StockController@show');
    Route::get('/stock/edit/{product:slug}', 'StockController@edit');
    Route::get('/stock/excel/{code}', 'StockController@Excel');
    Route::get('/stock/excel/{code}/{date}', 'StockController@Excel');
    Route::get('/stock/{slug}/search', 'StockController@search');
    Route::post('/stock/store/{code}', 'StockController@store');

    Route::get('/stock/{code}', 'StockController@index');
    Route::post('/stock/{code}', 'StockController@index');

    Route::patch('/stock/{product:id}/update', 'StockController@update');
    Route::get('/stock/{product:id}/market', 'StockController@market');
    // End Stock

    // Users

    Route::get('/user', 'UserController@index')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/user/excel', 'UserController@excel')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/user/search', 'UserController@search')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::delete('/user/destroy', 'UserController@destroy')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    // End User

});
