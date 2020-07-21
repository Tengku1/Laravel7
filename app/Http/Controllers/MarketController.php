<?php

namespace App\Http\Controllers;

use App\history_sell;
use App\history_sell_product;
use App\Product;
use App\Products_Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketController extends Controller
{
    public function index()
    {
        return view('layouts.Market.index');
    }

    public function create()
    {
        //
    }

    public function store()
    {
        $attr = request()->all();
        // check and set Null
        if (!isset($attr['sellqty'])) {
            $attr['sellqty'] = null;
        } elseif (!isset($attr['buyqty'])) {
            $attr['buyqty'] = null;
        } else {
            abort(404);
        }
        // End

        // Buy Stocks
        if ($attr['sellqty'] == null) {
            $stock = Products_Stock::join("products", "products_stock.product_id", "=", "products.id")->where('products.name', '=', $attr['ProductName'])->get();
            foreach ($stock as $value) {
                $attr['qty'] = $attr['buyqty'];
                $attr['product_id'] = $value->product_id;
                $attr['branch_code'] = $value->branch_code;
                $attr['buy_price'] = $value->buy_price;
                if (count($stock) == 1 && $stock[0]->qty == 0) {
                    $data = Products_Stock::where('products_stock.product_id', '=', $attr['product_id'])->update([
                        'qty' => $attr['buyqty'],
                    ]);
                    session()->flash('success', 'The Stock Has Increased or Created');
                } else {
                    $data = Products_Stock::where('product_id', '=', $attr['product_id'])->create($attr);
                    session()->flash('success', 'The Data Was Updated');
                }
            }
            if (Auth::user()->roles[0] == "Master") {
                return redirect()->to('/product');
            } else {
                return redirect()->to('/stock');
            }

            // Sell Stocks
        } elseif ($attr['buyqty'] == null) {

            $productId = Products_Stock::join("products", "products_stock.product_id", "=", "products.id")->where('products.name', '=', $attr['ProductName'])->get();


            $stock = Product::leftJoin('products_stock', 'products.id', '=', 'product_id')
                ->select('qty', 'product_id', 'products_stock.id as stockId', 'products.sell_price', 'branch_code', 'products_stock.created_at', 'buy_price')
                ->where('products_stock.product_id', '=', $productId[0]->product_id)
                ->orderBy('products_stock.created_at', "asc")
                ->get();

            $total = 0;
            $total += $attr['sellqty'];


            for ($i = 0; $i < sizeof($stock); $i++) {
                $qty = $stock[$i]->qty;
                $qty -= $attr['sellqty'];
                $attr['sellqty'] -= $qty;
                if ($qty <= 0) {
                    $attr['sellqty'] = abs($qty);
                    $qty = 0;
                } else {
                    $attr['sellqty'] = 0;
                }
                Products_Stock::where('id', '=', $stock[$i]->stockId)
                    ->update([
                        'products_stock.qty' => $qty,
                    ]);
            }


            // history sell product

            $history_sell = history_sell::get();
            if (count($history_sell)) {
                history_sell::where('modified_user', '=', Auth::user()->name)->update([
                    'modified_at' => now()->toDateTimeString(),
                ]);
            } else {
                $historysellattr['modified_user'] = Auth::user()->name;
                history_sell::create($historysellattr);
            }
            $history_product = history_sell_product::where('product_id', '=', $productId[0]->product_id)->get();

            $historyattr['product_id'] = $productId[0]->product_id;
            $historyattr['branch_code'] = $stock[0]->branch_code;
            $historyattr['buy_price'] = $stock[0]->buy_price;
            $historyattr['sell_price'] = $productId[0]->sell_price;
            if (count($history_product)) {
                $historyattr['qty'] = $history_product[0]->qty;
                $historyattr['qty'] += $total;
                history_sell_product::where('product_id', $productId[0]->product_id)->update([
                    'sell_price' => $historyattr['sell_price'],
                    'buy_price' => $historyattr['buy_price'],
                    'qty' => $historyattr['qty'],
                ]);
            } else {
                $historyattr['qty'] = $total;
                $historyattr['history_sell'] = $history_sell[0]->id;
                history_sell_product::create($historyattr);
            }
            if (Auth::user()->roles[0] == "Master") {
                return redirect()->to('/product');
            } else {
                return redirect()->to('/stock');
            }
        } else {
            abort(404);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function sell($id)
    {
        //
    }
}
