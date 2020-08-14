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
        $this->buy = history_buy_product::join("history_buy", "history_buy_product.history_buy", "history_buy.id")->join("branch", "history_buy.branch_code", "branch.code")->select("history_buy.id", "branch.name", "branch.slug", DB::raw("sum(history_buy_product.qty) as TotalQty"))->where("branch.status", "like", "active%")->where("history_buy.created_at", "like", "%" . date("Y-m-d") . "%");

        $this->sell = history_sell_product::join("history_sell", "history_sell_product.history_sell", "history_sell.id")->join("branch", "history_sell.branch_code", "branch.code")->select("history_sell.id", "branch.name", "branch.slug", DB::raw("sum(history_sell_product.qty) as TotalQty"))->where("branch.status", "like", "active%")->where("history_sell.created_at", "like", "%" . date("Y-m-d") . "%");


        $this->product = Product::join("products_stock", "products_stock.product_id", "products.id")
            ->join("branch", "products_stock.branch_code", "branch.code")
            ->select(
                "products.name",
                "products_stock.branch_code",
                "products.id as ProductID",
                "branch.name as BranchName",
                DB::raw("sum(qty) as qty")
            )->where('qty', '!=', 0);
        // End Query
    }

    public function index($getBranchSlug = null, $paginate = 7)
    {
        $branch = Branch::select("name", "code", "slug")->where("status", "like", "active%")->get();
        if (Auth::user()->roles[0] == "Master") {
            if ($getBranchSlug == null) {
                $getFirst = Products_Stock::join("branch", "products_stock.branch_code", "branch.code")->select("code")->where("branch.status", "=", "active")->first();
                if (isset($getFirst)) {
                    $data = $this->product->where("products_stock.branch_code", "=", $getFirst["code"])->groupBy("products_stock.branch_code", "products.name", "ProductID", "BranchName")->paginate($paginate);
                } else {
                    $data = $this->product->groupBy("products_stock.branch_code", "products.name", "ProductID", "BranchName")->paginate($paginate);
                }
            } else {
                $getBranch = Branch::where("slug", "=", $getBranchSlug)->get("code");
                $data = $this->product->where("products_stock.branch_code", "=", $getBranch[0]->code)->groupBy("products_stock.branch_code", "products.name", "ProductID", "BranchName")->paginate($paginate);
            }
            return view("layouts.Reports.Products", compact("data", "branch"));
        } elseif (Auth::user()->roles[0] == "Admin") {
            $data = $this->product->where("products_stock.branch_code", "=", Auth::user()->branch_code)->groupBy("products_stock.branch_code", "products.name", "ProductID", "BranchName")->paginate($paginate);
            $BranchName = Branch::where("code", "=", Auth::user()->branch_code)->get("name");
            return view("layouts.Reports.Products", compact("data"));
        } else {
            return abort(404);
        }
    }

    public function Buy($getBranchSlug = null, $paginate = 7)
    {
        $data = $this->buy->groupBy("history_buy.id", "branch.name", "branch.slug")->paginate($paginate);
        return view("layouts.Reports.Master.Buy", compact('data'));
    }

    public function Sell($getBranchSlug = null, $paginate = 7)
    {
        $data = $this->sell->where("history_sell.modified_user", "=", Auth::user()->name)->groupBy("history_sell.id", "branch.name", "branch.slug")->paginate($paginate);
        return view("layouts.Reports.Admin.Sell", compact("data"));
    }

    public function showProduct($code, $id, $paginate = 7)
    {
        $TotalQty = Products_Stock::select(DB::raw("sum(qty) as Total"))->where("branch_code", "=", $code)->where("product_id", "=", $id)->get();

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
        $data = $arr->where("branch.code", "=", $code)->where("products.id", "=", $id)->where('qty', '!=', 0)->paginate($paginate);
        return view("layouts.Reports.showProduct", compact("data", "TotalQty"));
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
        $data = $buy->where("history_buy.id", "=", $id)->groupBy("products.name", "qty", "buy_price", "ReffID", "BranchName")->paginate($paginate);
        $total = 0;
        for ($i = 0; $i < sizeof($data); $i++) {
            $total += $data[$i]->qty * $data[$i]->buy_price;
        }
        $total = explode(" ", $total);
        return view("layouts.Reports.ShowBuy", compact("data", "total", "fromDate", "toDate"));
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

        $data = $sell->groupBy("products.name", "qty", "buy_price", "products.sell_price", "ReffID", "BranchName")->paginate($paginate);
        $total = 0;
        for ($i = 0; $i < sizeof($data); $i++) {
            $total += $data[$i]->qty * $data[$i]->sell_price;
        }
        $total = explode(" ", $total);
        return view("layouts.Reports.ShowSell", compact("data", "total", "fromDate", "toDate"));
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
            $code = $attr['id'];
            $id = $attr['code'];
            $name = "Products_Detail_" . date('Y-m-d') . ".xlsx";
            return Excel::download(new ReportDetailProduct($id, $code), $name);
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