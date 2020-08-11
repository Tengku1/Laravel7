<?php

use App\Branch;
use App\history_buy_product;
use App\history_sell_product;
use App\Product;
use App\Products_Stock;
use App\User;
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
    Route::get('/', function () {
        if (Auth::user()->roles[0] == "Master") {
            $stock = Products_Stock::get();
            $product = Product::where('created_at', 'like', '%' . date("Y-m-d") . '%')->paginate(5);
            $user = User::paginate(5);
            $branch = Branch::get();
            $historyBuy = history_buy_product::join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')->get();
            $historySell = history_sell_product::join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')->get();
            return view('layouts.home', compact('historyBuy', 'historySell', 'product', 'stock', 'branch', 'user'));
        } else {
            $stock = Products_Stock::get();
            $product = Product::where('created_at', 'like', '%' . date("Y-m-d") . '%')->paginate(5);
            $historyBuy = history_buy_product::join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')->get();
            $historySell = history_sell_product::join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')->get();
            return view('layouts.home', compact('historyBuy', 'historySell', 'product', 'stock'));
        }
    });
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
    Route::get('/market/detail/buy', 'HistoryController@DetailBuy');
    Route::get('/market/detail/sell', 'HistoryController@DetailSell');
    Route::post('/market/storeBuy', 'HistoryController@storeBuy');
    Route::post('/market/storeSell', 'HistoryController@storeSell');
    Route::delete('/market/deleteBuy', 'HistoryController@deleteBuy');
    Route::delete('/market/deleteSell', 'HistoryController@deleteSell');
    Route::patch('/market/updateBuy', 'HistoryController@updateBuy');
    Route::patch('/market/updateSell', 'HistoryController@updateSell');
    Route::post('/market/storeStockSell', 'HistoryController@stockSell')->name('SellExecution');
    Route::post('/market/storeStockBuy', 'HistoryController@stockBuy')->name('BuyExecution');
    route::get('/market/detail/{page}/show/', 'HistoryController@show');

    Route::get('/market/buy/', 'HistoryController@historyBuy');
    Route::get('/market/buy/paginate/{limit}', 'HistoryController@historyBuy');
    Route::get('/market/sell/', 'HistoryController@historySell');
    Route::get('/market/sell/paginate/{limit}', 'HistoryController@historySell');

    Route::get('/market/detail/buy/{branchSlug}/paginate/{paginate}', 'HistoryController@DetailBuy');
    Route::get('/market/detail/sell/{branchSlug}/paginate/{paginate}', 'HistoryController@DetailSell');

    Route::get('/market/edit/{page}/{id}', 'HistoryController@edit')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);
    Route::get('/market/{page}/search/', 'HistoryController@search');
    // End Market

    // Products Only For MASTER !!
    Route::get('/product', 'ProductController@index');
    Route::get('/product/create', 'ProductController@create');
    Route::get('/product/edit/{product:slug}', 'ProductController@edit');
    Route::get('/product/excel', 'ProductController@excel');
    Route::get('/product/search', 'ProductController@search');
    Route::patch('/product/{id}', 'ProductController@update');
    Route::post('/product/store', 'ProductController@store');
    Route::delete('/product/destroy', 'ProductController@destroy');
    Route::get('/product/{product:slug}', 'ProductController@show');

    // End Products

    // Stock
    Route::get('/stock', 'StockController@index');
    Route::get('/stock/paginate/{paginate}', 'StockController@index');
    Route::get('/stock/search', 'StockController@search');
    // End Stock

    // Reports

    Route::get('/report/excel/{page}', 'ReportController@excel')->name("excelReport");
    Route::post('/report/show/excel/{page}', 'ReportController@ShowExcel')->name("excelShowReport");

    Route::get('/report/products/', 'ReportController@index');
    Route::get('/report/products/paginate/{paginate}', 'ReportController@index');
    Route::get('/report/products/{getBranchSlug}', 'ReportController@index');
    Route::get('/report/products/{getBranchSlug}/paginate/{paginate}', 'ReportController@index');

    Route::get('/report/buy/', 'ReportController@buy');
    Route::get('/report/buy/paginate/{paginate}', 'ReportController@buy');
    Route::get('/report/buy/{getBranchSlug}', 'ReportController@buy');
    Route::get('/report/buy/{getBranchSlug}/paginate/{paginate}', 'ReportController@buy');

    Route::get('/report/sell/', 'ReportController@sell');
    Route::get('/report/sell/paginate/{paginate}', 'ReportController@sell');
    Route::get('/report/sell/{getBranchSlug}', 'ReportController@sell');
    Route::get('/report/sell/{getBranchSlug}/paginate/{paginate}', 'ReportController@sell');

    Route::get('/report/product/{code}/{id}/', 'ReportController@showProduct');
    Route::get('/report/product/{code}/{id}/paginate/{paginate}', 'ReportController@showProduct');

    Route::post('/report/buy/Reff/{id}', 'ReportController@showBuy');
    Route::get('/report/buy/Reff/{id}/', 'ReportController@showBuy');
    Route::get('/report/buy/Reff/{id}/paginate/{paginate}', 'ReportController@showBuy');

    Route::get('/report/sell/Reff/{id}/', 'ReportController@showSell');
    Route::get('/report/sell/Reff/{id}/paginate/{paginate}', 'ReportController@showSell');

    // End Reports

    // Users
    Route::get('/user', 'UserController@index')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/user/create', 'UserController@create')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/user/edit/{email}', 'UserController@edit')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/user/excel', 'UserController@excel')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::get('/user/search', 'UserController@search')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::post('/user/store', 'UserController@store')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::patch('/user/{email}/update', 'UserController@update')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    Route::delete('/user/destroy', 'UserController@destroy')->middleware('check_user_role:' . \App\Role\UserRole::ROLE_MASTER);

    // End User

});
