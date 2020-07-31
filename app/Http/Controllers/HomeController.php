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
    public function report($page, $paginate = 7, $fromDate = null, $toDate = null)
    {
        $attr = request()->all();

        if (isset($attr['fromDate']) && isset($attr['toDate'])) {
            $fromDate = $attr['fromDate'];
            $toDate = $attr['toDate'];
            $unRecognizeBuy = history_buys::join("branch", "history_buy.branch_code", "branch.code")->select("history_buy.id as ReffID", "branch.name", "history_buy.created_at as CreatedAt")->where("history_buy.created_at", ">=", $fromDate)->where("history_buy.created_at", "<=", $toDate);

            $unRecognizeSell = history_sell::join("branch", "history_sell.branch_code", "branch.code")->select("history_sell.id as ReffID", "branch.name", "branch.slug", "history_sell.created_at as CreatedAt")->where("history_sell.created_at", ">=", $fromDate)->where("history_sell.created_at", "<=", $toDate);

            $data = Products_Stock::join("products", "products_stock.product_id", "products.id")->select(
                'product_id',
                'products.name as ProductName',
                DB::raw('sum(buy_price) as BuyPrice'),
                DB::raw('SUM(qty) as Qty')
            )->groupBy('product_id', 'ProductName',)->where("products_stock.created_at", ">=", $fromDate)->where("products_stock.created_at", "<=", $toDate)->paginate($paginate);
        } else {
            $unRecognizeBuy = history_buys::join("branch", "history_buy.branch_code", "branch.code")->select("history_buy.id as ReffID", "branch.name", "history_buy.created_at as CreatedAt");

            $unRecognizeSell = history_sell::join("branch", "history_sell.branch_code", "branch.code")->select("history_sell.id as ReffID", "branch.name", "branch.slug", "history_sell.created_at as CreatedAt");

            $data = Products_Stock::join("products", "products_stock.product_id", "products.id")->select(
                'product_id',
                'products.name as ProductName',
                DB::raw('sum(buy_price) * sum(qty) as Total'),
            )->groupBy('product_id', 'ProductName')->paginate($paginate);
        }
        // Total Proses
        $sell = history_sell_product::get();
        $totalSell = 0;
        for ($i = 0; $i < sizeof($sell); $i++) {
            $totalSell += $sell[$i]->qty * $sell[$i]->sell_price;
        }
        $totalSell = explode(" ", $totalSell);

        $buy = history_buy_product::get();
        $totalBuy = 0;
        for ($i = 0; $i < sizeof($buy); $i++) {
            $totalBuy += $buy[$i]->qty * $buy[$i]->buy_price;
        }
        $totalBuy = explode(" ", $totalBuy);

        $totalProducts = 0;
        for ($i = 0; $i < sizeof($data); $i++) {
            $totalProducts += $data[$i]->Total;
        }

        // End Proses

        if ($fromDate == null && $toDate == null) {
            if ($page == "buy") {
                $data = $unRecognizeBuy->paginate($paginate);
                $getSizeData = history_buy_product::get();
                if (!count($data)) {
                    $totalBuy[0] = 0;
                }
                return view("layouts.Reports.Buy", compact("data", "getSizeData", "totalBuy"));
            } elseif ($page == "sell") {
                $data = $unRecognizeSell->paginate($paginate);
                $getSizeData = history_sell_product::get();
                if (!count($data)) {
                    $totalSell[0] = 0;
                }
                return view("layouts.Reports.Sell", compact("data", "getSizeData", "totalSell"));
            } elseif ($page == "products") {
                $getSizeData = Product::get();
                return view("layouts.Reports.Products", compact("data", "getSizeData", "totalProducts"));
            } else {
                return abort(404);
            }
        } elseif ($fromDate == null && $toDate != null || $fromDate != null && $toDate == null) {
            session()->flash('warning', 'Please enter the date of entry and exit !');
            return redirect()->back();
        } else {
            if ($page == "buy") {
                $data = $unRecognizeBuy->paginate($paginate);
                $getSizeData = history_buy_product::get();
                if (!count($data)) {
                    $totalBuy[0] = 0;
                }
                return view("layouts.Reports.Buy", compact("data", "getSizeData", "totalBuy"));
            } elseif ($page == "sell") {
                $data = $unRecognizeSell->paginate($paginate);
                $getSizeData = history_sell_product::get();
                if (!count($data)) {
                    $totalSell[0] = 0;
                }
                return view("layouts.Reports.Sell", compact("data", "getSizeData", "totalSell"));
            } elseif ($page == "products") {
                $getSizeData = Product::get();
                return view("layouts.Reports.Products", compact("data", "getSizeData", "totalProducts"));
            } else {
                return abort(404);
            }
        }
    }
    public function search($page, $paginate = 7)
    {
        $attr = request()->all();
        // Total Proses
        $sell = history_sell_product::get();
        $totalSell = 0;
        for ($i = 0; $i < sizeof($sell); $i++) {
            $totalSell += $sell[$i]->qty * $sell[$i]->sell_price;
        }
        $totalSell = explode(" ", $totalSell);

        $buy = history_buy_product::get();
        $totalBuy = 0;
        for ($i = 0; $i < sizeof($buy); $i++) {
            $totalBuy += $buy[$i]->qty * $buy[$i]->buy_price;
        }
        $totalBuy = explode(" ", $totalBuy);
        // End Proses


        if ($page == "buy") {
            $data = history_buys::join("branch", "history_buy.branch_code", "branch.code")->select(
                "branch.name",
                "branch.slug",
                "history_buy.created_at as CreatedAt",
            )
                ->where("history_buy.id", "like", "%" . $attr['by'] . "%")
                ->orWhere("branch.name", "like", "%" . $attr['by'] . "%")
                ->orWhere("history_buy.created_at", "like", "%" . $attr['by'] . "%")
                ->paginate($paginate);
            $getSizeData = clone $data;

            return view("layouts.Reports.Buy", compact("data", "totalBuy", "getSizeData"));
        } elseif ($page == "sell") {
            $data = history_sell::join("branch", "history_sell.branch_code", "branch.code")->select(
                "history_sell.id as ReffID",
                "branch.name",
                "branch.slug",
                "history_sell.created_at as CreatedAt",
            )
                ->where("history_sell.id", "like", "%" . $attr['by'] . "%")
                ->orWhere("branch.name", "like", "%" . $attr['by'] . "%")
                ->orWhere("history_sell.created_at", "like", "%" . $attr['by'] . "%")
                ->paginate($paginate);
            $getSizeData = clone $data;

            return view("layouts.Reports.Sell", compact("data", "totalSell", "getSizeData"));
        } elseif ($page == "product") {
        } else {
            return abort(404);
        }
    }
}
