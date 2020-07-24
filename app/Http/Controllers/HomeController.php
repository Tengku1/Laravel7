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
use Illuminate\Support\Facades\Auth;

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

    public function DetailBuy()
    {
        $attr = request()->all();
        $branch = Branch::select('code')->where('slug', '=', $attr['branch'])->get();

        $historyBuy = history_buys::where('branch_code', '=', $branch[0]->code)->where('modified_user', '=', Auth::user()->name)->get();
        if (!count($historyBuy)) {
            history_buys::create([
                'branch_code' => $branch[0]->code,
                'modified_user' => Auth::user()->name,
            ]);
            $historyBuy = history_buys::where('branch_code', '=', $branch[0]->code)->where('modified_user', '=', Auth::user()->name)->get();
        }
        $history = history_buy_product::join('products', 'history_buy_product.product_id', 'products.id')->join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')->select('products.name', 'history_buy_product.qty', 'history_buy_product.buy_price', 'products.id', 'history_buy.id as buyId')->where('history_buy.id', '=', $historyBuy[0]->id)->get();

        $data = Product::select('name', 'id')->get();

        $qty = history_buy_product::sum("qty");
        $price = history_buy_product::sum("buy_price");
        $total = $qty * $price;
        $total = explode(' ', $total);
        return view('layouts.Market.formBuy', compact('total', 'historyBuy', 'data', 'history', 'branch'));
    }

    public function DetailSell()
    {
        $attr = request()->all();
        $branch = Branch::select('code')->where('slug', '=', $attr['branch'])->get();

        $historySell = history_sell::where('branch_code', '=', $branch[0]->code)->where('modified_user', '=', Auth::user()->name)->get();
        if (!count($historySell)) {
            history_sell::create([
                'branch_code' => $branch[0]->code,
                'modified_user' => Auth::user()->name,
            ]);
            $historySell = history_sell::where('branch_code', '=', $branch[0]->code)->where('modified_user', '=', Auth::user()->name)->get();
        }
        $history = history_sell_product::join('products', 'history_sell_product.product_id', 'products.id')->join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')->select('products.name', 'history_sell_product.qty', 'products.sell_price', 'products.id', 'history_sell.id as sellId')->where('history_sell.id', '=', $historySell[0]->id)->get();

        $data = Product::select('name', 'id')->get();

        $qty = history_sell_product::sum("qty");
        $price = history_sell_product::sum("buy_price");
        $total = $qty * $price;
        $total = explode(' ', $total);
        return view('layouts.Market.formSell', compact('total', 'historySell', 'data', 'history', 'branch'));
    }

    public function market($path)
    {
        if ($path == "buy") {
            $data = history_buy_product::join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')->select('history_buy.id', 'history_buy_product.qty', 'history_buy_product.buy_price')->paginate(7);
            $branch = Branch::select('name as branch_name', 'slug')->get();
            return view('layouts.Market.buy', compact('data', 'branch'));
        } else {
            $data = history_sell_product::join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')->select('history_sell.id', 'history_sell_product.qty', 'history_sell_product.sell_price')->paginate(7);
            $branch = Branch::select('name as branch_name', 'slug')->get();
            return view('layouts.Market.sell', compact('data', 'branch'));
        }
    }

    public function marketBuy()
    {
        $attr = request()->all();
        history_buy_product::create([
            'history_buy' => $attr['buyId'],
            'product_id' => $attr['product_id'],
            'qty' => $attr['qty'],
            'buy_price' => $attr['buy_price'],
        ]);
        return redirect()->back();
    }

    public function stockBuy()
    {
        $attr = request()->all();
        $history = history_buy_product::join('products', 'history_buy_product.product_id', 'products.id')->join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')->select('products.name', 'history_buy_product.qty', 'history_buy_product.buy_price', 'products.id')->where('history_buy.id', '=', $attr['id'])->get();

        foreach ($history as $value) {
            Products_Stock::create([
                'product_id' => $value->id,
                'branch_code' => $attr['branch_code'],
                'buy_price' => $value->qty * $value->buy_price,
                'qty' => $value->qty,
            ]);
        }
        return redirect()->to('/market/buy');
    }

    public function stockSell()
    {
        $attr = request()->all();
        Products_Stock::create([
            'product_id',
            'branch_code',
            'buy_price',
            'qty',
        ]);
        return redirect()->to('/market/sell');
    }

    public function store()
    {
        $attr = request()->all();
        dd($attr);
    }
}
