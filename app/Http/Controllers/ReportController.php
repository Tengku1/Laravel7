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
                return view("layouts.Reports.Master.Buy", compact('data'));
            } elseif ($page == "sell") {
                return view("layouts.Reports.Master.Sell");
            } elseif ($page == "products") {
                if ($branch == null) {
                    $data = Product::join("products_stock", "products_stock.product_id", "products.id")
                        ->join("branch", "products_stock.branch_code", "branch.code")
                        ->select("products.name", "branch.slug as BranchSlug", "products.slug as ProductSlug", DB::raw("sum(qty) as qty"))->groupBy("products.name", "BranchSlug", "ProductSlug")->paginate($paginate);
                    $branch = Branch::select("name", "code", "slug")->where("status", "like", "active%")->get();
                    return view("layouts.Reports.Master.Products", compact("data", "branch"));
                } else {
                    $getCode = Branch::where("slug", "=", $branch)->get("code");
                    $data = Product::join("products_stock", "products_stock.product_id", "products.id")
                        ->join("branch", "products_stock.branch_code", "branch.code")
                        ->select("products.name", "branch.slug as BranchSlug", "products.slug as ProductSlug", DB::raw("sum(qty) as qty"))->where("products_stock.branch_code", "=", $getCode[0]->code)->groupBy("products.name", "BranchSlug", "ProductSlug")->paginate($paginate);

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
            } else {
                return abort(404);
            }
        } else {
            return abort(404);
        }
    }

    public function show($page, $branch, $slug, $paginate = 7)
    {
        $attr = request()->all();
        $branch = Branch::select("code")->where("slug", "=", $branch)->get();
        $product = Product::select("id")->where("slug", "=", $slug)->get();
        if ($page == "buy") {
        } elseif ($page == "sell") {
            return view("layouts.Reports.Master.ShowSell");
        } elseif ($page == "product") {
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
            return view("layouts.Reports.Master.showProduct", compact("data"));
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
