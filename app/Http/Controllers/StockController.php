<?php

namespace App\Http\Controllers;


use App\Exports\StocksExport;
use App\Products_Stock;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    public function index($date = "%%", $paginate = 7)
    {
        $getSizeData = Products_Stock::get();
        $branch =  Auth::user()->branch_code;
        $data = Products_Stock::join("products", "products_stock.product_id", "products.id")
            ->join("branch", "products_stock.branch_code", "branch.code")
            ->select("products.name as ProductName", "branch.name as BranchName", "branch.slug", "buy_price", "qty")
            ->where("branch.slug", "=", $branch)->orWhere("products_stock.created_at", "like", $date)->paginate($paginate);
        return view("Admin.Stock.index", compact("data", "getSizeData"));
    }

    public function search($paginate = 7)
    {
        $attr = request()->all();
        $getSizeData = Products_Stock::get();
        $branch = Auth::user()->branch_code;
        $data = Products_Stock::join("products", "products_stock.product_id", "products.id")
            ->join("branch", "products_stock.branch_code", "branch.code")
            ->select("products.name as ProductName", "branch.name as BranchName", "branch.slug", "buy_price", "qty")
            ->where("branch.slug", "=", $branch)
            ->where("products_stock.created_at", "like", "%" . $attr['by'] . "%")
            ->orWhere("branch.name", "like", "%" . $attr['by'] . "%")
            ->orWhere("products_stock.buy_price", "like", "%" . $attr['by'] . "%")
            ->orWhere("products.name", "like", "%" . $attr['by'] . "%")
            ->paginate($paginate);
        return view("Admin.Stock.index", compact("data", "getSizeData"));
    }

    public function Excel($code, $date = null)
    {
        $name = "Stocks " . date("Y-m-d") . ".xlsx";
        return Excel::download(new StocksExport($code, $date), $name);
    }
}
