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
    Route::get('/stock', 'HomeController@stock');
    Route::get('/history', 'HomeController@history');
    Route::get('/history/{$id}', 'HomeController@historyShow');
    // End Home

    // Product Routes
    Route::get('/product', 'ProductController@index');
    Route::get('/product/branch/{order}', 'ProductController@index');
    Route::get('/product/excel', 'ProductController@Excel');
    Route::get('/product/create', 'ProductController@create');
    Route::get('/product/addStock', 'ProductController@addStock');
    Route::post('/product/store', 'ProductController@store');

    Route::get('/product/{product:slug}', 'ProductController@show');
    Route::delete('/product/delete', 'ProductController@destroy');
    Route::get('/product/{product:id}/edit', 'ProductController@edit');
    Route::patch('/product/{product:id}/update', 'ProductController@update');
    Route::get('/product/{product:id}/market', 'ProductController@market');
    Route::patch('/product/{product:id}/marketex', 'ProductController@marketex');
    // End Product


    // Branch Routes
    Route::get('/branch', 'BranchController@index');
    Route::get('/branch/create', 'BranchController@create');
    Route::post('/branch/store', 'BranchController@store');
    Route::get('/branch/{branch:code}', 'BranchController@show');
    Route::delete('/branch/{branch}/delete', 'BranchController@destroy');
    Route::get('/branch/{branch:code}/edit', 'BranchController@edit');
    Route::patch('/branch/{branch:code}/edit', 'BranchController@update');
    // End Branch
});
