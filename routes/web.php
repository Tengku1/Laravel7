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
    Route::get('/market/{path}/', 'HomeController@market');
    Route::get('/market/{path}/paginate/{limit}', 'HomeController@market');
    Route::get('/market/detail/buy', 'HomeController@DetailBuy');
    Route::get('/market/detail/buy/{branchSlug}/paginate/{paginate}', 'HomeController@DetailBuy');
    Route::get('/market/detail/sell', 'HomeController@DetailSell');
    Route::get('/market/detail/sell/{branchSlug}/paginate/{paginate}', 'HomeController@DetailSell');
    Route::post('/market/storeBuy', 'HomeController@storeBuy');
    Route::post('/market/storeSell', 'HomeController@storeSell');

    Route::delete('/market/deleteBuy', 'HomeController@deleteBuy');
    Route::delete('/market/deleteSell', 'HomeController@deleteSell');

    Route::get('/market/edit/{page}/{id}', 'HomeController@edit')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::patch('/market/updateBuy', 'HomeController@updateBuy');
    Route::patch('/market/updateSell', 'HomeController@updateSell');

    Route::post('/market/stockSell', 'HomeController@stockSell');
    Route::post('/market/stockBuy', 'HomeController@stockBuy');

    Route::get('/market/{page}/search/', 'HomeController@search');
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

    Route::get('/user/edit/{email}', 'UserController@edit')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::patch('/user/{email}/update', 'UserController@update')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/user/excel', 'UserController@excel')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/user/search', 'UserController@search')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::delete('/user/destroy', 'UserController@destroy')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    // End User

});
