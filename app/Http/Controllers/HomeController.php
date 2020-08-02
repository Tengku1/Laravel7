<?php

namespace App\Http\Controllers;

use App\Branch;
use App\history_buy_product;
use App\history_buys;
use App\history_sell;
use App\history_sell_product;
use App\Product;
use App\Products_Stock;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
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
    }
}
