<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Exports\ProductsExport;
use App\history_sell;
use App\history_sell_product;
use App\Http\Requests\ProductRequest;
use App\Product;
use App\Products_Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    public function index()
    {
        if (Auth::user()->roles[0] == "Master") {
            $data = Branch::paginate(7);
            return view('Admin.Stock.index', compact('data'));
        } elseif (Auth::user()->roles[0] == "Admin") {
            return redirect()->to("/stock/branch/" . Auth::user()->branch_code);
        } else {
            return abort(404);
        }
    }

    public function Excel($code)
    {
        return Excel::download(new ProductsExport($code), 'Stocks.xlsx');
    }
    public function market($id)
    {
        $data = Product::where('products.id', '=', $id)->get();
        if (Auth::user()->roles[0] == "Master") {
            return view('Admin.Stock.market', compact('data'));
        } elseif (Auth::user()->roles[0] == "Admin") {
            return view('Master.Stock.market', compact('data'));
        } else {
            return abort(404);
        }
    }
    public function marketex($id)
    {
        $attr = request()->all();
        if ($attr['sellqty'] == null && $attr['buyqty'] == null) {
            return redirect()->to('/stock');
        } elseif ($attr['sellqty'] == null) {
            $stock = Products_Stock::where('product_id', '=', $id)->get();
            foreach ($stock as $value) {
                if ($value->qty != 0) {
                    $attr['qty'] = $attr['buyqty'];
                    $attr['product_id'] = $id;
                    $attr['branch_code'] = $value->branch_code;
                    $attr['buy_price'] = $value->buy_price;
                    $data = Products_Stock::where('product_id', '=', $id)->create($attr);
                    session()->flash('success', 'The Stock Has Increased or Created');
                } else {
                    $data = Products_Stock::where('product_id', '=', $id)->update([
                        'qty' => $attr['buyqty'],
                    ]);
                    session()->flash('success', 'The Data Was Updated');
                }
            }
            return redirect()->to('/stock');
        } elseif ($attr['buyqty'] == null) {
            $data = Product::where('id', '=', $id)->get();

            $stock = Product::leftJoin('products_stock', 'products.id', '=', 'product_id')
                ->select('qty', 'product_id', 'products_stock.id as stockId', 'products.sell_price', 'branch_code', 'products_stock.created_at', 'buy_price')
                ->where('products_stock.product_id', '=', $data[0]->id)
                ->orderBy('products_stock.created_at', "asc")
                ->get();

            $total = 0;
            $total += $attr['sellqty'];


            for ($i = 0; $i < sizeof($stock); $i++) {
                $qty = $stock[$i]->qty;
                $qty -= $attr['sellqty'];
                if ($qty <= 0) {
                    $attr['sellqty'] += $qty;
                    $qty = 0;
                } else {
                    $attr['sellqty'] -= $qty;
                }
                Products_Stock::where('id', '=', $stock[$i]->stockId)
                    ->update([
                        'products_stock.qty' => $qty,
                    ]);
            }


            // // history sell product
            $history_sell = history_sell::get();
            if (count($history_sell)) {
                history_sell::where('modified_user', '=', Auth::user()->name)->update([
                    'modified_at' => now()->toDateTimeString(),
                ]);
            } else {
                $historysellattr['modified_user'] = Auth::user()->name;
                history_sell::create($historysellattr);
            }
            $history_product = history_sell_product::where('product_id', '=', $data[0]->id)->get();

            $historyattr['product_id'] = $id;
            $historyattr['branch_code'] = $stock[0]->branch_code;
            $historyattr['buy_price'] = $stock[0]->buy_price;
            $historyattr['sell_price'] = $data[0]->sell_price;
            if (count($history_product)) {
                $historyattr['qty'] = $history_product[0]->qty;
                $historyattr['qty'] += $total;
                history_sell_product::where('product_id', $data[0]->id)->update([
                    'sell_price' => $historyattr['sell_price'],
                    'buy_price' => $historyattr['buy_price'],
                    'qty' => $historyattr['qty'],
                ]);
            } else {
                $historyattr['qty'] = $total;
                $historyattr['history_sell'] = $history_sell[0]->id;
                history_sell_product::create($historyattr);
            }
            return redirect()->to('/stock');
        }
    }

    public function create($code)
    {
        $data['code'] = $code;
        if (Auth::user()->roles[0] == "Master") {
            return view('Admin.Stock.create', compact('data'));
        } elseif (Auth::user()->roles[0] == "Admin") {
            return view('Master.Stock.create', compact('data'));
        } else {
            return abort(404);
        }
    }

    public function edit($id)
    {
        $stocks =  Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")->select("products.id", "products.name", "products.slug", "products.sell_price", "products_stock.qty", "products_stock.buy_price", "products_stock.branch_code")->where('products.id', '=', $id)
            ->get();
        foreach ($stocks as $value) {
            $stocks['id'] = $value->id;
            $stocks['slug'] = $value->slug;
            $stocks['name'] = $value->name;
            $stocks['sell_price'] = $value->sell_price;
            $stocks['qty'] = $value->qty;
            $stocks['buy_price'] = $value->buy_price;
            if (!$stocks['buy_price']) {
                $product['buy_price'] = 0;
            }
        }
        $branch = Branch::get();
        $stocks['branch'] = $branch;
        if (Auth::user()->roles[0] == "Master") {
            return view('Admin.Stock.edit', compact('stocks'));
        } elseif (Auth::user()->roles[0] == "Admin") {
            return view('Master.Stock.edit', compact('stocks'));
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
                return redirect()->to('/stock/branch/' . Auth::user()->branch_code);
            } else {
                Product::where('id', '=', $product_id[0]->product_id)->update(['status' => 'out_of_stock']);
                session()->flash('success', 'The Data Was Deleted');
                return redirect()->to('/stock/branch/' . Auth::user()->branch_code);
            }
        } else {
            return abort(404);
        }
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
        if (Auth::user()->roles[0] == "Master") {
            return view('Admin.Stock.show', compact('product'));
        } elseif (Auth::user()->roles[0] == "Admin") {
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
        return redirect()->to('/stock');
    }

    public function stock($branch = null)
    {
        if (Auth::user()->roles[0] == "Admin") {
            $id = Auth::user()->branch_code;
            $stocks =  Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")->leftJoin("branch", "products_stock.branch_code", "=", "branch.code")
                ->join("users", "users.branch_code", "branch.code")
                ->select("products.*", "products_stock.qty", 'branch.code', 'products_stock.id', "products_stock.product_id", "products_stock.buy_price", "branch.name as Branch_name", "users.roles")
                ->where('users.branch_code', "=", $id)
                ->orderBy('products_stock.id')
                ->paginate(7);
        } else {
            if ($branch == null) {
                $stocks =  Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")
                    ->leftJoin("branch", "products_stock.branch_code", "=", "branch.code")
                    ->select("products.*", "products_stock.qty", 'products_stock.id', 'branch_code', "products_stock.product_id", "products_stock.buy_price", "branch.name as Branch_name")
                    ->orderBy('products_stock.id')
                    ->paginate(7);
            } else {
                $stocks =  Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")
                    ->leftJoin("branch", "products_stock.branch_code", "=", "branch.code")
                    ->select("products.*", "products_stock.qty", 'products_stock.id', 'branch_code', "products_stock.product_id", "products_stock.buy_price", "branch.name as Branch_name")
                    ->where('branch.code', '=', $branch)
                    ->orderBy('products_stock.id')
                    ->paginate(7);
            }
        }
        if (Auth::user()->roles[0] == "Master") {
            return view('Admin.Stock.stock', compact('stocks'));
        } elseif (Auth::user()->roles[0] == "Admin") {
            return view('Master.Stock.stock', compact('stocks'));
        } else {
            return abort(404);
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        $attr = $request->all();
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
            Products_Stock::where('product_id', $atrStock['product_id'])->update([
                'branch_code' => $atrStock['branch_code'],
                'buy_price' => $atrStock['buy_price']
            ]);
        }

        $product->update($attr);
        session()->flash('success', 'The Data Was Updated');
        return redirect()->to('/stock');
    }
}
