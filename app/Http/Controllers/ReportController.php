<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Exports\ReportDetailHistoryBuys;
use App\Exports\ReportDetailHistorySells;
use App\Exports\ReportDetailProduct;
use App\Exports\ReportHistoryBuys;
use App\Exports\ReportHistorySells;
use App\Exports\ReportProduct;
use App\history_buy_product;
use App\history_buys;
use App\history_sell;
use App\history_sell_product;
use App\Product;
use App\Products_Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    public function __construct()
    {
        // Index Query

        $this->buy = history_buy_product::join("history_buy", "history_buy_product.history_buy", "history_buy.id")->join("branch", "history_buy.branch_code", "branch.code")->select("history_buy.id", "branch.name", "branch.slug", DB::raw("sum(history_buy_product.qty) as TotalQty"))->groupBy("history_buy.id", "branch.name", "branch.slug");

        $this->sell = history_sell_product::join("history_sell", "history_sell_product.history_sell", "history_sell.id")->join("branch", "history_sell.branch_code", "branch.code")->select("history_sell.id", "branch.name", "branch.slug", DB::raw("sum(history_sell_product.qty) as TotalQty"))->groupBy("history_sell.id", "branch.name", "branch.slug");

        $this->product = Product::join("products_stock", "products_stock.product_id", "products.id")->join("branch", "products_stock.branch_code", "branch.code")->join("history_buy", "history_buy.branch_code", "branch.code")->select("products.name", "branch.code as BranchCode", "products.id as ProductID", DB::raw("sum(qty) as qty"))->groupBy("products.name", "BranchCode", "ProductID");
        // End Query
    }

    public function index($page, $branch = null, $paginate = 7)
    {
        if (Auth::user()->roles[0] == "Master") {
            if ($page == "buy") {
                $data = $this->buy->paginate($paginate);
                return view("layouts.Reports.Master.Buy", compact('data'));
            } elseif ($page == "sell") {
                $data = $this->sell->paginate($paginate);
                return view("layouts.Reports.Master.Sell", compact("data"));
            } elseif ($page == "products") {
                if ($branch == null) {
                    $data = $this->product->paginate($paginate);
                    $branch = Branch::select("name", "code", "slug")->where("status", "like", "active%")->get();
                    return view("layouts.Reports.Master.Products", compact("data", "branch"));
                } else {
                    $getCode = Branch::where("slug", "=", $branch)->get("code");
                    $data = $this->product->where("products_stock.branch_code", "=", $getCode[0]->code)->paginate($paginate);
                    $branch = Branch::select("name", "code", "slug")->where("status", "like", "active%")->get();
                    return view("layouts.Reports.Master.Products", compact("data", "branch"));
                }
            } else {
                return abort(404);
            }
        } elseif (Auth::user()->roles[0] == "Admin") {
            if ($page == "buy") {
                $data = $this->buy->where("history_buy.modified_user", "=", Auth::user()->name)->groupBy("history_buy.id", "branch.name", "branch.slug")->paginate($paginate);
                return view("layouts.Reports.Admin.Buy", compact('data'));
            } elseif ($page == "sell") {
                $data = $this->sell->where("history_sell.modified_user", "=", Auth::user()->name)->groupBy("history_sell.id", "branch.name", "branch.slug")->paginate($paginate);
                return view("layouts.Reports.Admin.Sell", compact("data"));
            } elseif ($page == "products") {
                $data = $this->product->where("products_stock.branch_code", "=", Auth::user()->branch_code)->paginate($paginate);
                return view("layouts.Reports.Admin.Products", compact("data"));
            } else {
                return abort(404);
            }
        } else {
            return abort(404);
        }
    }

    public function showProduct($code, $id, $paginate = 7)
    {
        $attr = request()->all();

        $TotalQty = Products_Stock::select(DB::raw("sum(qty) as Total"))->where("branch_code", "=", $code)->where("product_id", "=", $id)->get();

        if (isset($attr['fromDate']) && isset($attr['toDate'])) {
            $arr = Products_Stock::join("branch", "products_stock.branch_code", "branch.code")
                ->join("products", "products_stock.product_id", "products.id")
                ->select(
                    "products.name",
                    "products.id as ProductID",
                    "branch.code as BranchCode",
                    "branch.name as BranchName",
                    "qty",
                    "buy_price",
                    "products_stock.created_at"
                )
                ->where("products_stock.created_at", ">=", $attr['fromDate'])
                ->where("products_stock.created_at", ">=", $attr['toDate']);
        } elseif (isset($attr['fromDate']) && !isset($attr['toDate'])) {
            $arr = Products_Stock::join("branch", "products_stock.branch_code", "branch.code")
                ->join("products", "products_stock.product_id", "products.id")
                ->select(
                    "products.name",
                    "products.id as ProductID",
                    "branch.code as BranchCode",
                    "branch.name as BranchName",
                    "qty",
                    "buy_price",
                    "products_stock.created_at"
                );
            session()->flash('warning', "From or To must be filled");
        } else {
            $arr = Products_Stock::join("branch", "products_stock.branch_code", "branch.code")
                ->join("products", "products_stock.product_id", "products.id")
                ->select(
                    "products.name",
                    "products.id as ProductID",
                    "branch.code as BranchCode",
                    "branch.name as BranchName",
                    "qty",
                    "buy_price",
                    "products_stock.created_at"
                );
        }

        if (isset($attr['by'])) {
            $data = $arr->where("buy_price", "like", "%" . $attr['by'] . "%")->where("branch.code", "=", $code)->where("products.id", "=", $id)->orWhere("qty", "like", "%" . $attr['by'] . "%")->paginate($paginate);
        } else {
            $data = $arr->where("branch.code", "=", $code)->where("products.id", "=", $id)->paginate($paginate);
        }
        return view("layouts.Reports.Master.showProduct", compact("data", "TotalQty"));
    }

    public function showBuy($id, $paginate = 7)
    {
        $attr = request()->all();
        if (isset($attr['fromDate']) && isset($attr['toDate'])) {
            $fromDate = explode(" ", $attr['fromDate']);
            $toDate = explode(" ", $attr['toDate']);
            $buy = history_buys::join("history_buy_product", "history_buy_product.history_buy", "history_buy.id")
                ->join("products", "history_buy_product.product_id", "products.id")
                ->join("branch", "history_buy.branch_code", "branch.code")
                ->select(
                    "products.name",
                    "qty",
                    "buy_price",
                    "history_buy.id as ReffID",
                    "branch.name as BranchName",
                    DB::raw("sum(qty * buy_price) as SubTotal")
                )
                ->where("history_buy.created_at", ">=", $attr['fromDate'])
                ->where("history_buy.created_at", ">=", $attr['toDate']);
        } else {
            $buy = history_buys::join("history_buy_product", "history_buy_product.history_buy", "history_buy.id")
                ->join("products", "history_buy_product.product_id", "products.id")
                ->join("branch", "history_buy.branch_code", "branch.code")
                ->select(
                    "products.name",
                    "qty",
                    "buy_price",
                    "history_buy.id as ReffID",
                    "branch.name as BranchName",
                    DB::raw("sum(qty * buy_price) as SubTotal")
                );
            $fromDate = [];
            $toDate = [];
            if (isset($attr['fromDate']) || isset($attr['toDate'])) {
                session()->flash('warning', 'From or Date must be Filled !');
            }
        }
        if (isset($attr['by'])) {

            $data = $buy->where("history_buy.id", "=", $id)->where("products.name", "like", "%" . $attr['by'] . "%")->orWhere("qty", "like", "%" . $attr['by'] . "%")->orWhere("buy_price", "like", "%" . $attr['by'] . "%")->groupBy("products.name", "qty", "buy_price", "ReffID", "BranchName")->paginate($paginate);
            $total = 0;
            for ($i = 0; $i < sizeof($data); $i++) {
                $total += $data[$i]->qty * $data[$i]->buy_price;
            }
            $total = explode(" ", $total);
        } else {
            $data = $buy->where("history_buy.id", "=", $id)->groupBy("products.name", "qty", "buy_price", "ReffID", "BranchName")->paginate($paginate);
            $total = 0;
            for ($i = 0; $i < sizeof($data); $i++) {
                $total += $data[$i]->qty * $data[$i]->buy_price;
            }
            $total = explode(" ", $total);
        }
        return view("layouts.Reports.Master.ShowBuy", compact("data", "total", "fromDate", "toDate"));
    }

    public function showSell($id, $paginate = 7)
    {
        $attr = request()->all();
        if (isset($attr['fromDate']) && isset($attr['toDate'])) {
            $sell = history_sell::join("history_sell_product", "history_sell_product.history_sell", "history_sell.id")->join("branch", "history_sell.branch_code", "branch.code")->join("products", "history_sell_product.product_id", "products.id")->select("products.name", "qty", "products.sell_price", "buy_price", "history_sell.id as ReffID", "branch.name as BranchName", DB::raw("sum(qty * buy_price) as SubTotal"))->where("history_sell.created_at", ">=", $attr['fromDate'])->where("history_sell.created_at", ">=", $attr['toDate'])->where("history_sell.id", "=", $id);
            $fromDate = explode(" ", $attr['fromDate']);
            $toDate = explode(" ", $attr['toDate']);
        } else {
            $sell = history_sell::join("history_sell_product", "history_sell_product.history_sell", "history_sell.id")->join("branch", "history_sell.branch_code", "branch.code")->join("products", "history_sell_product.product_id", "products.id")->select("products.name", "qty", "products.sell_price", "buy_price", "history_sell.id as ReffID", "branch.name as BranchName", DB::raw("sum(qty * buy_price) as SubTotal"))->where("history_sell.id", "=", $id);
            $fromDate = [];
            $toDate = [];
            if (isset($attr['fromDate']) || isset($attr['toDate'])) {
                session()->flash('warning', "From or To must be Filled !");
            }
        }

        if (isset($attr['by'])) {
            $data = $sell->where("products.name", "like", "%" . $attr['by'] . "%")->orWhere("qty", "like", "%" . $attr['by'] . "%")->orWhere("buy_price", "like", "%" . $attr['by'] . "%")->groupBy("products.name", "qty", "buy_price", "products.sell_price", "ReffID", "BranchName")->paginate($paginate);
            $total = 0;
            for ($i = 0; $i < sizeof($data); $i++) {
                $total += $data[$i]->qty * $data[$i]->sell_price;
            }
            $total = explode(" ", $total);
        } else {
            $data = $sell->groupBy("products.name", "qty", "buy_price", "products.sell_price", "ReffID", "BranchName")->paginate($paginate);
            $total = 0;
            for ($i = 0; $i < sizeof($data); $i++) {
                $total += $data[$i]->qty * $data[$i]->sell_price;
            }
            $total = explode(" ", $total);
        }
        return view("layouts.Reports.Master.ShowSell", compact("data", "total", "fromDate", "toDate"));
    }

    public function excel($page)
    {
        if ($page == "product") {
            $name = "Products_" . date('Y-m-d') . ".xlsx";
            return Excel::download(new ReportProduct, $name);
        } elseif ($page == "buy") {
            $name = "History_Buys_" . date('Y-m-d') . ".xlsx";
            return Excel::download(new ReportHistoryBuys, $name);
        } elseif ($page == "sell") {
            $name = "History_Sells_" . date('Y-m-d') . ".xlsx";
            return Excel::download(new ReportHistorySells, $name);
        } else {
            return abort(404);
        }
    }
    public function ShowExcel($page)
    {
        $attr = request()->all();
        if ($page == "product") {
            $getCode = Branch::where("slug", "=", $attr['BranchSlug'])->get("code");
            $productID = Product::where("slug", "=", $attr['slug'])->get("id");
            $name = "Products_Detail_" . date('Y-m-d') . ".xlsx";
            return Excel::download(new ReportDetailProduct($getCode, $productID), $name);
        } elseif ($page == "buy") {
            $name = "History_Buys_Detail_" . date('Y-m-d') . ".xlsx";
            $reffID = $attr['reffid'];
            if (isset($attr['fromDate']) && isset($attr['toDate'])) {
                $fromDate = $attr['fromDate'];
                $toDate = $attr['toDate'];
                return Excel::download(new ReportDetailHistoryBuys($reffID, $fromDate, $toDate), $name);
            } else {
                $fromDate = null;
                $toDate = null;
                return Excel::download(new ReportDetailHistoryBuys($reffID, $fromDate, $toDate), $name);
            }
        } elseif ($page == "sell") {
            $name = "History_Sells_Detail_" . date('Y-m-d') . ".xlsx";
            $reffID = $attr['reffid'];
            if (isset($attr['fromDate']) && isset($attr['toDate'])) {
                $fromDate = $attr['fromDate'];
                $toDate = $attr['toDate'];
                return Excel::download(new ReportDetailHistorySells($reffID, $fromDate, $toDate), $name);
            } else {
                $fromDate = null;
                $toDate = null;
                return Excel::download(new ReportDetailHistorySells($reffID, $fromDate, $toDate), $name);
            }
        } else {
            return abort(404);
        }
    }

    public function update()
    {
        //
    }
}
