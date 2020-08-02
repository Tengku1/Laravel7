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
    public function index($page, $branch = null, $paginate = 7)
    {
        if (Auth::user()->roles[0] == "Master") {
            if ($page == "buy") {
                $data = history_buy_product::join("history_buy", "history_buy_product.history_buy", "history_buy.id")
                    ->join("branch", "history_buy.branch_code", "branch.code")
                    ->select("history_buy.id", "branch.name", "branch.slug", DB::raw("sum(history_buy_product.qty) as TotalQty"))
                    ->groupBy("history_buy.id", "branch.name", "branch.slug")
                    ->paginate($paginate);
                return view("layouts.Reports.Master.Buy", compact('data'));
            } elseif ($page == "sell") {
                $data = history_sell_product::join("history_sell", "history_sell_product.history_sell", "history_sell.id")
                    ->join("branch", "history_sell.branch_code", "branch.code")
                    ->select("history_sell.id", "branch.name", "branch.slug", DB::raw("sum(history_sell_product.qty) as TotalQty"))
                    ->groupBy("history_sell.id", "branch.name", "branch.slug")
                    ->paginate($paginate);
                return view("layouts.Reports.Master.Sell", compact("data"));
            } elseif ($page == "products") {
                if ($branch == null) {
                    $data = Product::join("products_stock", "products_stock.product_id", "products.id")
                        ->join("branch", "products_stock.branch_code", "branch.code")
                        ->join("history_buy", "history_buy.branch_code", "branch.code")
                        ->select("products.name", "branch.slug as BranchSlug", "products.slug as ProductSlug", DB::raw("sum(qty) as qty"))->groupBy("products.name", "BranchSlug", "ProductSlug")
                        ->where("history_buy.created_at", "like", date("Y-m-d"))
                        ->paginate($paginate);
                    $branch = Branch::select("name", "code", "slug")->where("status", "like", "active%")->get();
                    return view("layouts.Reports.Master.Products", compact("data", "branch"));
                } else {
                    $getCode = Branch::where("slug", "=", $branch)->get("code");
                    $data = Product::join("products_stock", "products_stock.product_id", "products.id")
                        ->join("branch", "products_stock.branch_code", "branch.code")
                        ->join("history_buy", "history_buy.branch_code", "branch.code")
                        ->select("products.name", "branch.slug as BranchSlug", "products.slug as ProductSlug", DB::raw("sum(qty) as qty"))
                        ->where("history_buy.created_at", "like", date("Y-m-d"))
                        ->where("products_stock.branch_code", "=", $getCode[0]->code)->groupBy("products.name", "BranchSlug", "ProductSlug")->paginate($paginate);

                    $branch = Branch::select("name", "code", "slug")->where("status", "like", "active%")->get();
                    return view("layouts.Reports.Master.Products", compact("data", "branch"));
                }
            } else {
                return abort(404);
            }
        } elseif (Auth::user()->roles[0] == "Admin") {
            if ($page == "buy") {
            } elseif ($page == "sell") {
            } elseif ($page == "product") {
                $data = Product::join("products_stock", "products_stock.product_id", "products.id")
                    ->join("branch", "products_stock.branch_code", "branch.code")
                    ->join("history_buy", "history_buy.branch_code", "branch.code")
                    ->select("products.name", "branch.slug as BranchSlug", "products.slug as ProductSlug", DB::raw("sum(qty) as qty"))->groupBy("products.name", "BranchSlug", "ProductSlug")
                    ->where("history_buy.created_at", "like", date("Y-m-d"))
                    ->where("products_stock.branch_code", "=", Auth::user()->branch_code)
                    ->paginate($paginate);
                return view("layouts.Reports.Admin.Products", compact("data"));
            } else {
                return abort(404);
            }
        } else {
            return abort(404);
        }
    }

    public function showProduct($page, $branch, $slug, $paginate = 7)
    {
        $attr = request()->all();
        $branch = Branch::select("code")->where("slug", "=", $branch)->get();
        $product = Product::select("id")->where("slug", "=", $slug)->get();
        $TotalQty = Products_Stock::select(DB::raw("sum(qty) as Total"))->where("branch_code", "=", $branch[0]->code)->where("product_id", "=", $product[0]->id)->get();

        $arr = Products_Stock::join("branch", "products_stock.branch_code", "branch.code")
            ->join("products", "products_stock.product_id", "products.id")
            ->select(
                "products.name",
                "products.slug",
                "branch.slug as BranchSlug",
                "branch.name as BranchName",
                "qty",
                "buy_price",
                "products_stock.created_at"
            );

        if (isset($attr['by'])) {
            $data = $arr->where("buy_price", "like", "%" . $attr['by'] . "%")->where("branch.code", "=", $branch[0]->code)->where("products.id", "=", $product[0]->id)->orWhere("qty", "like", "%" . $attr['by'] . "%")->paginate($paginate);
        } else {
            $data = $arr->where("branch.code", "=", $branch[0]->code)->where("products.id", "=", $product[0]->id)->paginate($paginate);
        }
        return view("layouts.Reports.Master.showProduct", compact("data", "TotalQty"));
    }

    public function showHistory($page, $id, $paginate = 7)
    {
        $attr = request()->all();
        if ($page == "buy") {
            if (isset($attr['by'])) {
                $data = history_buys::join("history_buy_product", "history_buy_product.history_buy", "history_buy.id")
                    ->join("products", "history_buy_product.product_id", "products.id")
                    ->select("products.name", "qty", "buy_price", "history_buy.id as ReffID", DB::raw("sum(qty * buy_price) as SubTotal"))
                    ->where("history_buy.id", "=", $id)
                    ->where("products.name", "like", "%" . $attr['by'] . "%")
                    ->orWhere("qty", "like", "%" . $attr['by'] . "%")
                    ->orWhere("buy_price", "like", "%" . $attr['by'] . "%")
                    ->groupBy("products.name", "qty", "buy_price", "ReffID")
                    ->paginate($paginate);
                $total = 0;
                for ($i = 0; $i < sizeof($data); $i++) {
                    $total += $data[$i]->qty * $data[$i]->buy_price;
                }
                $total = explode(" ", $total);
            } else {
                $data = history_buys::join("history_buy_product", "history_buy_product.history_buy", "history_buy.id")
                    ->join("products", "history_buy_product.product_id", "products.id")
                    ->select("products.name", "qty", "buy_price", "history_buy.id as ReffID", DB::raw("sum(qty * buy_price) as SubTotal"))
                    ->where("history_buy.id", "=", $id)
                    ->groupBy("products.name", "qty", "buy_price", "ReffID")
                    ->paginate($paginate);
                $total = 0;
                for ($i = 0; $i < sizeof($data); $i++) {
                    $total += $data[$i]->qty * $data[$i]->buy_price;
                }
                $total = explode(" ", $total);
            }
            if (count($data)) {
                return view("layouts.Reports.Master.ShowBuy", compact("data", "total"));
            } else {
                return abort(404);
            }
        } elseif ($page == "sell") {
            if (isset($attr['by'])) {
                $data = history_sell::join("history_sell_product", "history_sell_product.history_sell", "history_sell.id")
                    ->join("products", "history_sell_product.product_id", "products.id")
                    ->select("products.name", "qty", "products.sell_price", "buy_price", "history_sell.id as ReffID", DB::raw("sum(qty * buy_price) as SubTotal"))
                    ->where("history_sell.id", "=", $id)
                    ->where("products.name", "like", "%" . $attr['by'] . "%")
                    ->orWhere("qty", "like", "%" . $attr['by'] . "%")
                    ->orWhere("buy_price", "like", "%" . $attr['by'] . "%")
                    ->groupBy("products.name", "qty", "buy_price", "products.sell_price", "ReffID")
                    ->paginate($paginate);
                $total = 0;
                for ($i = 0; $i < sizeof($data); $i++) {
                    $total += $data[$i]->qty * $data[$i]->sell_price;
                }
                $total = explode(" ", $total);
            } else {
                $data = history_sell::join("history_sell_product", "history_sell_product.history_sell", "history_sell.id")
                    ->join("products", "history_sell_product.product_id", "products.id")
                    ->select("products.name", "qty", "buy_price", "products.sell_price", "history_sell.id as ReffID", DB::raw("sum(qty * buy_price) as SubTotal"))
                    ->where("history_sell.id", "=", $id)
                    ->groupBy("products.name", "qty", "buy_price", "products.sell_price", "ReffID")
                    ->paginate($paginate);
                $total = 0;
                for ($i = 0; $i < sizeof($data); $i++) {
                    $total += $data[$i]->qty * $data[$i]->sell_price;
                }
                $total = explode(" ", $total);
            }
            if (count($data)) {
                return view("layouts.Reports.Master.ShowSell", compact("data", "total"));
            } else {
                return abort(404);
            }
        } else {
            return abort(404);
        }
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
    public function excelShow($page)
    {
        $attr = request()->all();
        $getCode = Branch::where("slug", "=", $attr['BranchSlug'])->get("code");
        $productID = Product::where("slug", "=", $attr['slug'])->get("id");

        if ($page == "product") {
            $name = "Products_Detail_" . date('Y-m-d') . ".xlsx";
            return Excel::download(new ReportDetailProduct($getCode, $productID), $name);
        } elseif ($page == "buy") {
            $name = "History_Buys_Detail_" . date('Y-m-d') . ".xlsx";
            return Excel::download(new ReportDetailHistoryBuys, $name);
        } elseif ($page == "sell") {
            $name = "History_Sells_Detail_" . date('Y-m-d') . ".xlsx";
            return Excel::download(new ReportDetailHistorySells, $name);
        } else {
            return abort(404);
        }
    }

    public function update()
    {
        //
    }
}
