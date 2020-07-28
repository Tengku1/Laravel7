<?php

namespace App\Http\Controllers;

use App\Branch;
use App\history_buy_product;
use App\history_buys;
use App\history_sell;
use App\history_sell_product;
use App\Product;
use App\Products_Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function DetailBuy($branchSlug = null, $paginate = 7)
    {
        $attr = request()->all();
        if ($branchSlug != null) {
            $branch = Branch::select('code', 'slug')
                ->where('slug', 'like', '%' . $branchSlug . '%')->get();
        } else {
            $branch = Branch::select('code', 'slug')
                ->where('slug', 'like', '%' . $branchSlug . '%')->get();
        }
        if (!count($branch)) {
            return abort(404);
        }
        $checkBuy = history_buys::where('branch_code', '=', $branch[0]->code)->where('modified_user', '=', Auth::user()->name)->where('has_finished', '=', 'false')->get();

        if (count($checkBuy)) {
            $checkBuy = history_buys::where('branch_code', '=', $branch[0]->code)->where('modified_user', '=', Auth::user()->name)->orderBy('id', 'desc')->first();
            $historyId = $checkBuy['id'];
        } else {
            $historyBuy = history_buys::create([
                'branch_code' => $branch[0]->code,
                'modified_user' => Auth::user()->name,
            ]);
            $historyId = $historyBuy->id;
        }

        if (isset($attr['by'])) {
            $data = history_buy_product::join('products', 'history_buy_product.product_id', 'products.id')
                ->join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')
                ->select(
                    'products.name',
                    'history_buy_product.qty',
                    'history_buy_product.id as HistoryProductID',
                    'history_buy_product.buy_price',
                    'products.id',
                    'history_buy.id as buyId'
                )
                ->where('history_buy.id', '=', $historyId)
                ->where('products.name', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_buy_product.buy_price', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_buy_product.qty', 'like', '%' . $attr['by'] . '%')
                ->paginate($paginate);
        } else {
            $data = history_buy_product::join('products', 'history_buy_product.product_id', 'products.id')->join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')->select('products.name', 'history_buy_product.qty', 'history_buy_product.id as HistoryProductID', 'history_buy_product.buy_price', 'products.id', 'history_buy.id as buyId')
                ->where('history_buy.id', '=', $historyId)->paginate($paginate);
        }

        $product = Product::select('name', 'id')->get();

        $qty = history_buy_product::where('history_buy', $historyId)->sum("qty");
        $price = history_buy_product::where('history_buy', $historyId)->sum("buy_price");
        $total = $qty * $price;
        $total = explode(' ', $total);
        $getSizeData = history_buy_product::join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')->where('history_buy.id', '=', $historyId)->get();
        return view('layouts.Market.formBuy', compact('total', 'getSizeData', 'historyId', 'product', 'data', 'branch'));
    }

    public function DetailSell($branchSlug = null, $paginate = 7)
    {
        $attr = request()->all();
        if ($branchSlug != null) {
            $branch = Branch::select('code', 'slug')
                ->where('slug', 'like', '%' . $branchSlug . '%')->get();
        } else {
            $branch = Branch::select('code', 'slug')
                ->where('slug', 'like', '%' . $branchSlug . '%')->get();
        }
        if (!count($branch)) {
            return abort(404);
        }
        $checkSell = history_sell::where('branch_code', '=', $branch[0]->code)->where('modified_user', '=', Auth::user()->name)->where('has_finished', '=', 'false')->get();

        if (count($checkSell)) {
            $checkSell = history_sell::where('branch_code', '=', $branch[0]->code)->where('modified_user', '=', Auth::user()->name)->orderBy('id', 'desc')->first();
            $historyId = $checkSell['id'];
        } else {
            $historySell = history_sell::create([
                'branch_code' => $branch[0]->code,
                'modified_user' => Auth::user()->name,
            ]);
            $historyId = $historySell->id;
        }

        if (isset($attr['by'])) {
            $data = history_sell_product::join('products', 'history_sell_product.product_id', 'products.id')
                ->join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
                ->select(
                    'products.name',
                    'products.id',
                    'products.sell_price',
                    'history_sell_product.qty',
                    'history_sell_product.buy_price',
                    'history_sell.id as sellId'
                )
                ->where('history_sell.id', '=', $historyId)
                ->where('products.name', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_sell_product.buy_price', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_sell_product.sell_price', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_sell_product.qty', 'like', '%' . $attr['by'] . '%')
                ->paginate($paginate);
        } else {
            $data = history_sell_product::join('products', 'history_sell_product.product_id', 'products.id')
                ->join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
                ->select(
                    'products.name',
                    'products.id',
                    'products.sell_price',
                    'history_sell_product.qty',
                    'history_sell_product.buy_price',
                    'history_sell.id as sellId'
                )->where('history_sell.id', '=', $historyId)->paginate($paginate);
        }

        $product = Product::select('name', 'id')->get();

        $qty = history_sell_product::sum("qty");
        $price = history_sell_product::sum("buy_price");
        $total = $qty * $price;
        $total = explode(' ', $total);
        $getSizeData = history_sell_product::join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')->where('history_sell.id', '=', $historyId)->get();
        return view('layouts.Market.formSell', compact('getSizeData', 'total', 'historyId', 'product', 'data', 'branch'));
    }

    public function edit($page = null, $id)
    {
        if ($page == "buy") {
            $data = history_buy_product::join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')
                ->join('products', 'history_buy_product.product_id', 'products.id')
                ->select(
                    'history_buy_product.*',
                    'history_buy.branch_code',
                    'products.name',
                )->where('history_buy_product.id', '=', $id)->get();

            return view('layouts.Market.BuyEdit', compact('data'));
        } elseif ($page == "sell") {
            $data = history_sell_product::join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
                ->join('products', 'history_sell_product.product_id', 'products.id')
                ->select(
                    'history_sell_product.*',
                    'history_sell.branch_code',
                    'products.name',
                )->where('history_sell_product.id', '=', $id)->get();

            return view('layouts.Market.SellEdit', compact('data'));
        } else {
            return abort(404);
        }
    }


    public function deleteBuy()
    {
        $attr = request()->all();
        history_buy_product::where('id', '=', $attr['id'])->delete();
        session()->flash('success', 'The Data Was Deleted');
        return redirect('/market/buy');
    }

    public function deleteSell()
    {
        $attr = request()->all();
        history_sell_product::where("id", "=", $attr['id'])->delete();
        session()->flash('success', 'The Data Was Deleted');
        return redirect('/market/sell');
    }

    public function history($path, $paginate = 7)
    {
        if ($path == "buy") {
            $data = history_buy_product::join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')
                ->join('branch', 'history_buy.branch_code', '=', 'branch.code')
                ->select(
                    'history_buy.id',
                    'history_buy_product.qty',
                    'branch.slug as branchSlug',
                    'history_buy_product.buy_price',
                    'history_buy_product.id as historyProductID',
                    'history_buy.has_finished',
                )->where('history_buy.created_at', 'like', '%' . date('Y-m-d') . '%')->paginate($paginate);
            $branch = Branch::select('name as branch_name', 'slug')->get();
            $getSizeData = history_buy_product::get();
            return view('layouts.Market.buy', compact('data', 'branch', 'getSizeData'));
        } else {
            $data = history_sell_product::join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
                ->join('branch', 'history_sell.branch_code', '=', 'branch.code')
                ->select(
                    'history_sell.id',
                    'history_sell_product.qty',
                    'branch.slug as branchSlug',
                    'history_sell_product.sell_price',
                    'history_sell_product.id as historyProductID',
                    'history_sell.has_finished',
                )->where('history_sell.created_at', 'like', '%' . date('Y-m-d') . '%')->paginate($paginate);
            $branch = Branch::select('name as branch_name', 'slug')->get();
            $getSizeData = history_sell_product::get();
            return view('layouts.Market.sell', compact('data', 'branch', 'getSizeData'));
        }
    }

    public function search($page)
    {
        $attr = request()->all();
        if ($page == "buy") {
            $data = history_buy_product::join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')
                ->join('branch', 'history_buy.branch_code', '=', 'branch.code')
                ->select(
                    'history_buy.id',
                    'history_buy_product.qty',
                    'branch.slug as branchSlug',
                    'history_buy_product.buy_price',
                    'history_buy_product.id as historyProductID',
                    'history_buy.has_finished',
                )
                ->Where('history_buy.created_at', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_buy.id', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_buy_product.buy_price', 'like', '%' . $attr['by'] . '%')
                ->paginate(7);
            $branch = Branch::select('name as branch_name', 'slug')->get();
            $getSizeData = history_buy_product::get();
            return view('layouts.Market.buy', compact('data', 'branch', 'getSizeData'));
        } elseif ($page == "sell") {
            $data = history_sell_product::join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
                ->join('branch', 'history_sell.branch_code', '=', 'branch.code')
                ->select(
                    'history_sell.id',
                    'history_sell_product.qty',
                    'branch.slug as branchSlug',
                    'history_sell_product.sell_price',
                    'history_sell_product.id as historyProductID',
                    'history_sell.has_finished',
                )
                ->Where('history_sell.created_at', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_sell.id', 'like', '%' . $attr['by'] . '%')
                ->orWhere('buy_price', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_sell_product.sell_price', 'like', '%' . $attr['by'] . '%')
                ->paginate(7);
            $branch = Branch::select('name as branch_name', 'slug')->get();
            $getSizeData = history_sell_product::get();
            return view('layouts.Market.sell', compact('data', 'branch', 'getSizeData'));
        } else {
            abort(404);
        }
    }

    public function stockBuy()
    {
        $attr = request()->all();
        $history = history_buy_product::join('products', 'history_buy_product.product_id', 'products.id')->join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')->select('products.name', 'history_buy_product.qty', 'history_buy_product.buy_price', 'products.id')->where('history_buy.id', '=', $attr['id'])->get();

        for ($i = 0; $i < sizeof($history); $i++) {
            $stock = Products_Stock::create([
                'product_id' => $history[$i]->id,
                'branch_code' => $attr['branch_code'],
                'buy_price' => $history[$i]->qty * $history[$i]->buy_price,
                'qty' => $history[$i]->qty,
            ]);
        }
        $product = Product::join('products_stock', 'products_stock.product_id', 'products.id')->select(DB::raw('SUM(products_stock.qty) as totalQty'))->where('products.id', '=', $stock->product_id)->get();

        if ($product[0]->totalQty > 20) {
            $status = "in stock";
        } elseif ($product[0]->totalQty <= 20 && $product[0]->totalQty > 0) {
            $status = "running low";
        } else {
            $status = "out of stock";
        }
        Product::where("id", "=", $stock->product_id)->update([
            'status' => $status,
        ]);

        history_buys::where('id', '=', $attr['id'])->update([
            'has_finished' => "true",
        ]);
        return redirect()->to('/market/buy/');
    }

    public function stockSell()
    {
        $attr = request()->all();
        $history = history_sell_product::join('products', 'history_sell_product.product_id', 'products.id')
            ->join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
            ->select(
                'history_sell_product.qty',
                'history_sell_product.product_id',
            )
            ->where('history_sell.id', '=', $attr['id'])->get();

        $stock = Products_Stock::select('qty', 'created_at', 'id')->where('product_id', '=', $history[0]->product_id)->where('branch_code', '=', $attr['branch_code'])->orderBy('created_at', 'asc')->get();
        $qtyHistory = $history[0]->qty;
        for ($i = 0; $i < sizeof($stock); $i++) {
            $qtyStock = $stock[$i]->qty;
            $qtyNow =  $qtyStock - $qtyHistory;
            $qtyStock -= $qtyHistory;
            $qtyHistory -= $stock[$i]->qty;
            if ($qtyStock > 0) {
                Products_Stock::where('id', '=', $stock[$i]->id)->update([
                    'qty' => $qtyNow,
                ]);
                break;
            } else {
                Products_Stock::where('product_id', '=', $history[0]->product_id)->where('branch_code', '=', $attr['branch_code'])->update([
                    'qty' => 0,
                ]);
            }
        }

        $product = Product::join('products_stock', 'products_stock.product_id', 'products.id')->select(DB::raw('SUM(products_stock.qty) as totalQty'))->where('products.id', '=', $history[0]->id)->get();

        if ($product[0]->totalQty > 20) {
            $status = "in stock";
        } elseif ($product[0]->totalQty <= 20 && $product[0]->totalQty > 0) {
            $status = "running low";
        } else {
            $status = "out of stock";
        }
        Product::where("id", "=", $stock[0]->product_id)->update([
            'status' => $status,
        ]);

        history_sell::where('id', '=', $attr['id'])->update([
            'has_finished' => "true",
        ]);
        return redirect()->to('/market/sell/');
    }

    public function storeBuy()
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

    public function storeSell()
    {
        $attr = request()->all();
        $branch = history_sell::select('branch_code')->where('id', '=', $attr['id'])->get();
        $product = Product::select('sell_price')->where('id', '=', $attr['product_id'])->get();
        $stock = Products_Stock::select("buy_price")->where("product_id", "=", $attr['product_id'])->orderBy('created_at', 'asc')->get();

        $attr['sell_price'] = $product[0]->sell_price;
        $attr['buy_price'] = $stock[0]->buy_price;
        history_sell_product::create([
            'history_sell' => $attr['id'],
            'product_id' => $attr['product_id'],
            'qty' => $attr['qty'],
            'buy_price' => $attr['buy_price'],
            'sell_price' => $attr['sell_price'],
        ]);
        return redirect()->back();
    }

    public function updateBuy()
    {
        $attr = request()->all();
        history_buy_product::where('id', '=', $attr['id'])->update([
            'buy_price' => $attr['buy_price'],
            'qty' => $attr['qty'],
        ]);
        return redirect()->to('/market/buy');
    }

    public function updateSell()
    {
        $attr = request()->all();
        history_sell_product::where('id', '=', $attr['id'])->update([
            'buy_price' => $attr['buy_price'],
            'sell_price' => $attr['sell_price'],
            'qty' => $attr['qty'],
        ]);
        return redirect()->to('/market/sell');
    }
}
