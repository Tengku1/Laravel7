<?php

namespace App\Http\Controllers;

use App\Branch;
use App\history_sell_product;
use App\Product;
use App\Products_Stock;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::user()->roles[0] == "Master") {
            $data['Stock'] = Products_Stock::get();
            $data['productThisDay'] = Product::where('created_at', 'like', '%' . date("Y-m-d") . '%')->paginate(5);
            $data['User'] = User::paginate(5);
            $data['Branch'] = Branch::get();
            $data['historySells'] = history_sell_product::join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')->get();
            $data['historyThisDay'] = history_sell_product::join("products", "history_sell_product.product_id", "=", "products.id")->paginate(5);
        } else {
            $data['Stock'] = Products_Stock::get();
            $data['productThisDay'] = Product::where('created_at', 'like', '%' . date("Y-m-d") . '%')->paginate(5);
            $data['historySells'] = history_sell_product::join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')->where('history_sell_product.branch_code', '=', Auth::user()->branch_code)->get();
            $data['historyThisDay'] = history_sell_product::join("products", "history_sell_product.product_id", "=", "products.id")->where('history_sell_product.branch_code', '=', Auth::user()->branch_code)->paginate(5);
        }
        return view('layouts.home', compact('data'));
    }
}
