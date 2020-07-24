<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Exports\StocksExport;
use App\history_product_monthly;
use App\history_sell;
use App\history_sell_product;
use App\Product;
use App\Products_Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    public function index($branch = null)
    {
        if (Auth::user()->roles[0] == "Admin") {
            $id = Auth::user()->branch_code;
            $data =  Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")->leftJoin("branch", "products_stock.branch_code", "=", "branch.code")
                ->join("users", "users.branch_code", "branch.code")
                ->select(
                    "products.*",
                    "products_stock.qty",
                    "branch.code",
                    "products_stock.id",
                    "products_stock.product_id",
                    "products_stock.buy_price",
                    "branch.name as Branch_name",
                    "branch.slug as BranchSlug",
                    "users.roles"
                )
                ->where('users.branch_code', "=", $id)
                ->orderBy('products_stock.id')
                ->paginate(7);
            return view('Admin.Stock.index', compact('data'));
        } elseif (Auth::user()->roles[0] == "Master") {
            if (request()->date) {
                $date = request()->date;
                $data =  Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")
                    ->leftJoin("branch", "products_stock.branch_code", "=", "branch.code")
                    ->select(
                        "products.name",
                        "products.sell_price",
                        'products_stock.id',
                        "products_stock.qty",
                        "products_stock.product_id",
                        "products_stock.buy_price",
                        "products_stock.created_at",
                        'branch_code',
                        "branch.name as Branch_name"
                    )
                    ->where('branch.code', '=', $branch)
                    ->where('products_stock.created_at', 'like', '%' . $date . '%')
                    ->orderBy('products_stock.id')
                    ->paginate(7);
                foreach ($data as $value) {
                    $value['date'] = $date;
                }
            } else {
                $data =  Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")
                    ->leftJoin("branch", "products_stock.branch_code", "=", "branch.code")
                    ->select("products.*", "products_stock.qty", 'products_stock.id', 'branch_code', "products_stock.product_id", "products_stock.buy_price", "branch.name as Branch_name")
                    ->where('branch.code', '=', $branch)
                    ->orderBy('products_stock.id')
                    ->paginate(7);
            }
            $arr['branch'] = $branch;
            return view('Master.Stock.index', compact('data', 'arr'));
        } else {
            return abort(404);
        }
    }

    public function Excel($code, $date = null)
    {
        $name = "Stocks " . date("Y-m-d") . ".xlsx";
        return Excel::download(new StocksExport($code, $date), $name);
    }

    public function create($code = null)
    {
        $data['code'] = $code;
        if (Auth::user()->roles[0] == "Master") {
            return view('Master.Stock.create', compact('data'));
        } elseif (Auth::user()->roles[0] == "Admin") {
            $data['code'] = Auth::user()->branch_code;
            return view('Admin.Stock.create', compact('data'));
        }
    }

    public function edit($slug)
    {
        $stocks =  Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")->select("products.id", "products.name", "products.slug", "products.sell_price", "products_stock.qty", "products_stock.buy_price", "products_stock.branch_code")->where('products.slug', '=', $slug)->get();
        if (Auth::user()->roles[0] == "Admin") {
            return view('Admin.Stock.edit', compact('stocks'));
        } elseif (Auth::user()->roles[0] == "Master") {
            $branch = Branch::select("name")->get();
            return view('Master.Stock.edit', compact('stocks', 'branch'));
        } else {
            return abort(404);
        }
    }

    public function destroy()
    {
        $stock_id = request('stock_id');
        $product_id = Products_Stock::select('product_id')->where('id', '=', $stock_id)->get();
        $product = Product::join('products_stock', 'products.id', 'products_stock.product_id')->where('products.id', '=', $product_id[0]->product_id)->get();
        Products_Stock::where('id', $stock_id)->delete();
        if (Auth::user()->roles[0] == "Master") {
            if (count($product)) {
                session()->flash('success', 'The Data Was Deleted');
                return redirect()->to('/stock/branch/' . request('branch_code'));
            } else {
                Product::where('id', '=', $product_id[0]->product_id)->update(['status' => 'out_of_stock']);
                session()->flash('success', 'The Data Was Deleted');
                return redirect()->to('/stock/branch/' . request('branch_code'));
            }
        } elseif (Auth::user()->roles[0] == "Admin") {
            if (count($product)) {
                session()->flash('success', 'The Data Was Deleted');
                return redirect()->to('/stock');
            } else {
                Product::where('id', '=', $product_id[0]->product_id)->update(['status' => 'out_of_stock']);
                session()->flash('success', 'The Data Was Deleted');
                return redirect()->to('/stock');
            }
        } else {
            return abort(404);
        }
    }

    public function marketStore()
    {
        $attr = request()->all();
    }

    public function search($branch = null)
    {
        $by = request()->all();
        foreach ($by as $value) {
            $stocks =  Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")
                ->leftJoin("branch", "products_stock.branch_code", "=", "branch.code")
                ->select("products.name", "products_stock.qty", 'products_stock.id', 'branch_code', "products_stock.product_id", "products_stock.buy_price", "branch.name as Branch_name")
                ->where('products.name', 'like', '%' . $value . '%')
                ->orWhere('products.sell_price', 'like', '%' . $value . '%')
                ->orWhere('products_stock.buy_price', 'like', '%' . $value . '%')
                ->orderBy('products_stock.qty')
                ->paginate(7);
        }
        $arr['branch'] = $branch;
        return view('Master.Stock.stock', compact('stocks', 'arr'));
    }

    public function show($slug)
    {
        $product =  Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")->select("products.id", "products.name", "products.created_at", "products.modified_at", "products.slug", "products.sell_price", "products_stock.qty", "products_stock.buy_price", "products_stock.branch_code")->where('products.slug', '=', $slug)->get();
        foreach ($product as $value) {
            $product['id'] = $value->id;
            $product['slug'] = $value->slug;
            $product['name'] = $value->name;
            $product['sell_price'] = $value->sell_price;
            $product['qty'] = $value->qty;
            $product['created_at'] = $value->created_at;
            $product['modified_at'] = $value->modified_at;
            if (!$product['qty']) {
                $product['qty'] = 0;
            }
            $product['buy_price'] = $value->buy_price;
            if (!$product['buy_price']) {
                $product['buy_price'] = 0;
            }
        }
        if (Auth::user()->roles[0] == "Admin") {
            return view('Admin.Stock.show', compact('product'));
        } elseif (Auth::user()->roles[0] == "Master") {
            return view('Master.Stock.show', compact('product'));
        } else {
            return abort(404);
        }
    }

    public function store($code)
    {
        $attr = request()->all();
        $var = $attr['sell_price'];
        $var = str_replace(".", "", $var);
        $attr['sell_price'] = $var;
        $attr['slug'] = Str::slug(request()->name);
        $attr['status'] = "out_of_stock";
        $id = Product::create($attr);
        $prd['product_id'] = $id->id;
        $prd['branch_code'] = $code;
        Products_Stock::create($prd);
        session()->flash('success', 'The Data Was Added');
        if (Auth::user()->roles[0] == "Master") {
            return redirect()->to('/branch');
        } else {
            return redirect()->to('/stock');
        }
    }
    public function update(Product $product)
    {
        $attr = request()->all();
        if (request()->qty > 0) {
            $attr['status'] = "in_stock";
        } else {
            $attr['status'] = "out_of_stock";
        }
        $atrStock['product_id'] = request()->id;
        $atrStock['buy_price'] = request()->buyprice;
        $atrStock['branch_code'] = request()->code;
        $stock = Products_Stock::where('product_id', '=', $atrStock['product_id'])->get();

        if ($stock->isEmpty()) {
            Products_Stock::create($atrStock);
        } else {
            if (Auth::user()->roles[0] == "Master") {
                Products_Stock::where('product_id', $atrStock['product_id'])->update([
                    'branch_code' => $atrStock['branch_code'],
                    'buy_price' => $atrStock['buy_price']
                ]);
            } else {
                Products_Stock::where('product_id', $atrStock['product_id'])->update([
                    'branch_code' => Auth::user()->branch_code,
                    'buy_price' => $atrStock['buy_price']
                ]);
            }
        }

        $product->update($attr);
        session()->flash('success', 'The Data Was Updated');
        return back();
    }
}
