<?php

namespace App\Http\Controllers;

use App\Exports\ProductExport;
use App\Product;
use App\Products_Stock;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function __construct()
    {
        // 
    }

    public function create()
    {
        return view('Master.Product.create');
    }

    public function destroy()
    {
        $attr = request()->all();
        Product::where('id', '=', $attr['id'])->update([
            'status' => "inactive",
        ]);
        session()->flash('success', 'The Data Was Deleted');
        return redirect('/product');
    }

    public function edit($slug)
    {
        $data = Product::select('name', 'id', 'sell_price', 'created_at', 'modified_at', 'status')->where('slug', '=', $slug)->get();
        return view('Master.Product.edit', compact('data'));
    }

    public function excel()
    {
        $name = "Products.xlsx";
        return Excel::download(new ProductExport, $name);
    }

    public function index()
    {
        $data = Product::select('name', 'sell_price', 'modified_at', 'slug', 'status', 'id')->where('status', '!=', 'inactive')->paginate(7);
        return view('Master.Product.index', compact('data'));
    }

    public function search()
    {
        $attr = request()->all();
        $data = Product::where('name', 'like', '%' . $attr['by'] . '%')
            ->orWhere('name', 'like', '%' . $attr['by'] . '%')
            ->orWhere('sell_price', 'like', '%' . $attr['by'] . '%')
            ->orWhere('created_at', 'like', '%' . $attr['by'] . '%')
            ->orWhere('modified_at', 'like', '%' . $attr['by'] . '%')
            ->orWhere('status', 'like', '%' . $attr['by'] . '%')
            ->paginate(7);
        return view('Master.Product.index', compact('data'));
    }

    public function store()
    {
        $attr = request()->all();
        $attr['slug'] = Str::slug($attr['name']);
        $attr['status'] = "out of stock";
        Product::create($attr);
        session()->flash('success', 'New Product !');
        return redirect()->to('/product');
    }

    public function show($slug)
    {
        $data = Product::select('name', 'id', 'sell_price', 'created_at', 'modified_at', 'status')->where('slug', '=', $slug)->get();
        $qty = Products_Stock::where('product_id', '=', $data[0]->id)->where('qty', '!=', 0)->sum('qty');
        return view('Master.Product.show', compact('data', 'qty'));
    }

    public function update($id)
    {
        $attr = request()->all();
        Product::where('id', '=', $id)->update([
            'name' => $attr['name'],
            'sell_price' => $attr['sell_price'],
        ]);
        session()->flash('success', 'The Data Was Updated');
        return redirect('/product');
    }
}
