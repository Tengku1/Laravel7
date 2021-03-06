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

    public function __construct()
    {
        $sell = history_sell::where("created_at", "=", date("Y-m-d", strtotime("-1 Days")))->where("has_finished", "=", "false")->get();
        $buy = history_buys::where("created_at", "=", date("Y-m-d", strtotime("-1 Days")))->where("has_finished", "=", "false")->get();
        if ($buy != null) {
            for ($i = 0; $i < sizeof($buy); $i++) {
                history_buys::where("id", "=", $buy[$i]->id)->delete();
            }
        }
        if ($sell != "null") {
            for ($i = 0; $i < sizeof($sell); $i++) {
                history_sell::where("id", "=", $buy[$i]->id)->delete();
            }
        }
    }

    public function DetailBuy($branchSlug = null)
    {
        $attr = request()->all();
        if (Auth::user()->roles[0] == "Admin") {
            $branchSlug = $attr['branch'];
        }
        if ($branchSlug != null) {
            $branch = Branch::select('code', 'slug')
                ->where('slug', 'like', '%' . $branchSlug . '%')->get();
            if (!count($branch)) {
                abort(404);
            }
        } else {
            $branch = Branch::select('code', 'slug')
                ->where('slug', 'like', '%' . $attr['branch'] . '%')->get();
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
                ->paginate(7);
        } else {
            $data = history_buy_product::join('products', 'history_buy_product.product_id', 'products.id')->join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')->select('products.name', 'history_buy_product.qty', 'history_buy_product.id as HistoryProductID', 'history_buy_product.buy_price', 'products.id', 'history_buy.id as buyId')
                ->where('history_buy.id', '=', $historyId)->paginate(7);
        }

        $product = Product::select('name', 'id')->get();

        $total = 0;
        for ($i = 0; $i < sizeof($data); $i++) {
            $total += $data[$i]->qty * $data[$i]->buy_price;
        }

        $total = explode(' ', $total);
        return view('layouts.Market.formBuy', compact('total', 'historyId', 'product', 'data', 'branch'));
    }

    public function DetailSell($branchSlug = null)
    {
        $attr = request()->all();
        if ($branchSlug != null) {
            $branch = Branch::select('code', 'slug')
                ->where('slug', 'like', '%' . $branchSlug . '%')->get();
        } else {
            $branch = Branch::select('code', 'slug')
                ->where('slug', 'like', '%' . $attr['branch'] . '%')->get();
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
                    'history_sell.id as sellId',
                    'history_sell_product.id as HistoryProductID'
                )
                ->where('history_sell.id', '=', $historyId)
                ->where('products.name', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_sell_product.buy_price', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_sell_product.sell_price', 'like', '%' . $attr['by'] . '%')
                ->orWhere('history_sell_product.qty', 'like', '%' . $attr['by'] . '%')
                ->paginate(7);
        } else {
            $data = history_sell_product::join('products', 'history_sell_product.product_id', 'products.id')
                ->join('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
                ->select(
                    'products.name',
                    'products.id',
                    'products.sell_price',
                    'history_sell_product.qty',
                    'history_sell_product.buy_price',
                    'history_sell.id as sellId',
                    'history_sell_product.id as HistoryProductID'
                )->where('history_sell.id', '=', $historyId)->paginate(7);
        }

        $product = Product::select('name', 'id')->get();
        $total = 0;
        for ($i = 0; $i < sizeof($data); $i++) {
            $total += $data[$i]->qty * $data[$i]->sell_price;
        }

        $total = explode(' ', $total);
        return view('layouts.Market.formSell', compact('total', 'historyId', 'product', 'data', 'branch'));
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
        return redirect()->back();
    }

    public function deleteSell()
    {
        $attr = request()->all();
        history_sell_product::where("id", "=", $attr['id'])->delete();
        session()->flash('success', 'The Data Was Deleted');
        return redirect()->back();
    }

    public function historyBuy($paginate = 7)
    {
        if (Auth::user()->roles[0] == "Master") {
            $data = history_buy_product::rightJoin('history_buy', 'history_buy_product.history_buy', 'history_buy.id')
                ->join('branch', 'history_buy.branch_code', '=', 'branch.code')
                ->select(
                    'history_buy.id',
                    'branch.slug as branchSlug',
                    'history_buy.has_finished',
                )
                ->where('history_buy.created_at', 'like', '%' . date('Y-m-d') . '%')
                ->where('branch.status', '=', 'active')
                ->orderBy('history_buy.id')
                ->groupBy('history_buy.id', 'branch.slug', 'history_buy.has_finished')
                ->paginate($paginate);
            $branch = Branch::select('name as branch_name', 'slug')->where('status', '=', 'active')->get();
            $getSizeData = history_buy_product::rightJoin('history_buy', 'history_buy_product.history_buy', 'history_buy.id')
                ->select(
                    'history_buy.id',
                    'history_buy.has_finished',
                )
                ->where('history_buy.created_at', 'like', '%' . date('Y-m-d') . '%')
                ->orderBy('history_buy.id')
                ->groupBy('history_buy.id', 'history_buy.has_finished')
                ->get();
        } else {
            $data = history_buy_product::rightJoin('history_buy', 'history_buy_product.history_buy', 'history_buy.id')
                ->select(
                    'history_buy.id',
                    'history_buy.has_finished',
                )
                ->where('history_buy.created_at', 'like', '%' . date('Y-m-d') . '%')
                ->where("history_buy.modified_user", "=", Auth::user()->name)
                ->orderBy('history_buy.id')
                ->groupBy('history_buy.id', 'history_buy.has_finished')
                ->paginate($paginate);
            $branch = Branch::select('slug')->where("code", "=", Auth::user()->branch_code)->get();
            $getSizeData = history_buy_product::rightJoin('history_buy', 'history_buy_product.history_buy', 'history_buy.id')
                ->select(
                    'history_buy.id',
                    'history_buy.has_finished',
                )
                ->where('history_buy.created_at', 'like', '%' . date('Y-m-d') . '%')
                ->where("history_buy.modified_user", "=", Auth::user()->name)
                ->orderBy('history_buy.id')
                ->groupBy('history_buy.id', 'history_buy.has_finished')
                ->get();
        }

        return view('layouts.Market.buy', compact('data', 'branch', 'getSizeData'));
    }

    public function historySell($paginate = 7)
    {
        if (Auth::user()->roles[0] == "Master") {
            $data = history_sell_product::rightJoin('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
                ->join('branch', 'history_sell.branch_code', '=', 'branch.code')
                ->select(
                    'history_sell.id',
                    'branch.slug as branchSlug',
                    'history_sell.has_finished',
                )
                ->where('history_sell.created_at', 'like', '%' . date('Y-m-d') . '%')
                ->where('branch.status', '=', 'active')
                ->orderBy('history_sell.id')
                ->groupBy('history_sell.id', 'branch.slug', 'history_sell.has_finished')
                ->paginate($paginate);
            $getSizeData = history_sell_product::rightJoin('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
                ->join('branch', 'history_sell.branch_code', '=', 'branch.code')
                ->select(
                    'history_sell.id',
                    'branch.slug as branchSlug',
                    'history_sell.has_finished',
                )
                ->where('history_sell.created_at', 'like', '%' . date('Y-m-d') . '%')
                ->orderBy('history_sell.id')
                ->groupBy('history_sell.id', 'branch.slug', 'history_sell.has_finished')->get();
        } else {
            $data = history_sell_product::rightJoin('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
                ->join('branch', 'history_sell.branch_code', '=', 'branch.code')
                ->select(
                    'history_sell.id',
                    'branch.slug as branchSlug',
                    'history_sell.has_finished',
                )
                ->where('history_sell.created_at', 'like', '%' . date('Y-m-d') . '%')
                ->where("history_sell.modified_user", "=", Auth::user()->name)
                ->orderBy('history_sell.id')
                ->groupBy('history_sell.id', 'branch.slug', 'history_sell.has_finished')
                ->paginate($paginate);
            $getSizeData = history_sell_product::rightJoin('history_sell', 'history_sell_product.history_sell', 'history_sell.id')
                ->join('branch', 'history_sell.branch_code', '=', 'branch.code')
                ->select(
                    'history_sell.id',
                    'branch.slug as branchSlug',
                    'history_sell.has_finished',
                )
                ->where('history_sell.created_at', 'like', '%' . date('Y-m-d') . '%')
                ->where("history_sell.modified_user", "=", Auth::user()->name)
                ->orderBy('history_sell.id')
                ->groupBy('history_sell.id', 'branch.slug', 'history_sell.has_finished')->get();
        }
        $branch = Branch::select('name as branch_name', 'slug')->where("status", "=", "active")->get();
        return view('layouts.Market.sell', compact('data', 'branch', 'getSizeData'));
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
                ->where('history_buy.id', 'like', '%' . $attr['by'] . '%')
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
                ->where('history_sell.id', 'like', '%' . $attr['by'] . '%')
                ->paginate(7);
            $branch = Branch::select('name as branch_name', 'slug')->get();
            $getSizeData = history_sell_product::get();
            return view('layouts.Market.sell', compact('data', 'branch', 'getSizeData'));
        } else {
            abort(404);
        }
    }

    public function show($page = null)
    {
        $attr = request()->all();
        if ($page == "buy") {
            $data = history_buy_product::join("history_buy", "history_buy_product.history_buy", "history_buy.id")
                ->join("products", "history_buy_product.product_id", "products.id")
                ->join("branch", "history_buy.branch_code", "branch.code")
                ->select(
                    "products.name as ProductName",
                    "qty",
                    "branch.name as BranchName",
                    "history_buy.modified_user",
                    "history_buy.id as ReffID",
                    "buy_price"
                )
                ->where("history_buy.id", "like", '%' . $attr['id'] . '%')->get();
            $total = history_buy_product::select(DB::raw("sum(qty * buy_price) as TotalBuy"))->where("history_buy", "=", $attr['id'])->get();
            if (count($data)) {
                return view("layouts.Market.showBuy", compact('data', 'total'));
            } else {
                return abort(404);
            }
        } elseif ($page == "sell") {
            $data = history_sell_product::join("history_sell", "history_sell_product.history_sell", "history_sell.id")
                ->join("products", "history_sell_product.product_id", "products.id")
                ->join("branch", "history_sell.branch_code", "branch.code")
                ->select(
                    "products.name as ProductName",
                    "qty",
                    "branch.name as BranchName",
                    "history_sell.modified_user",
                    "buy_price",
                    "history_sell.id as ReffID",
                    "products.sell_price"
                )
                ->where("history_sell.id", "like", '%' . $attr['id'] . '%')->get();
            $total = history_sell_product::select(DB::raw("sum(qty * sell_price) as TotalSell"))->where("history_sell", "=", $attr['id'])->get();
            if (count($data)) {
                return view("layouts.Market.showSell", compact('data', 'total'));
            } else {
                return abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function stockBuy()
    {
        $attr = request()->all();
        $history = history_buy_product::join('products', 'history_buy_product.product_id', 'products.id')->join('history_buy', 'history_buy_product.history_buy', 'history_buy.id')->select('products.name', 'history_buy_product.qty', 'history_buy_product.buy_price', 'products.id')->where('history_buy.id', '=', $attr['id'])->get();

        for ($i = 0; $i < sizeof($history); $i++) {
            Products_Stock::create([
                'product_id' => $history[$i]->id,
                'branch_code' => $attr['branch_code'],
                'buy_price' => $history[$i]->buy_price,
                'qty' => $history[$i]->qty,
            ]);
        }

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

        for ($i = 0; $i < sizeof($history); $i++) {
            $getStock = Products_Stock::select('qty', 'created_at', 'id')
                ->where('product_id', "like", $history[$i]->product_id)
                ->where('branch_code', '=', $attr['branch_code'])
                ->where('qty', '!=', 0)
                ->orderBy('created_at', 'asc')
                ->get();
            $stock[$i] = $getStock;
        }
        for ($i = 0; $i < sizeof($stock); $i++) {
            $qtyHistory = $history[$i]->qty;
            for ($j = 0; $j < sizeof($stock[$i]); $j++) {
                $qtyStock = $stock[$i][$j]->qty;
                $qtyNow =  $qtyStock - $qtyHistory;
                if ($qtyNow < 0) {
                    $qtyNow = 0;
                }
                $qtyStock -= $qtyHistory;
                $qtyHistory -= $stock[$i][$j]->qty;
                if ($qtyStock > 0) {
                    Products_Stock::where('id', '=', $stock[$i][$j]->id)->update([
                        'qty' => $qtyNow,
                    ]);
                } else {
                    Products_Stock::where('product_id', '=', $history[$i]->product_id)->where('branch_code', '=', $attr['branch_code'])->update([
                        'qty' => 0,
                    ]);
                }
                if ($qtyHistory <= 0) {
                    continue;
                }
            }
        }


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

        $getQty = Products_Stock::where("product_id", "=", $attr['product_id'])->get()->sum("qty");
        if ($getQty < $attr['qty']) {
            session()->flash('warning', 'Warning : the product stock you have chosen is not enough ! ');
            return redirect()->back()->with("Stock tidak mencukupi");
        } else {
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
