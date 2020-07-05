<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Product;
use App\Products_Stocks;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsStockController extends Controller
{
    public function index()
    {
        $stocks = Products_Stocks::paginate(5);
        return view('Admin.stocks.stock', compact('stocks'));
    }
    public function create()
    {
        $stock = Products_Stocks::rightJoin("products", "products_stock.product_id", "=", "products.id")->get();
        return view('Admin.stocks.create', compact('stock'));
    }
    public function edit(Products_Stocks $stock)
    {
        return view('Admin.stocks.edit', compact('stock'));
    }
    public function destroy(Products_Stocks $stock)
    {
        $stock->delete();
        session()->flash('success', 'The Data Was Deleted');
        return redirect()->to('/stock');
    }
    public function show(Products_Stocks $stock)
    {
        return view('Admin.stocks.show', compact('stock'));
    }
    public function store(Products_Stocks $request)
    {
        $attr = $request->all();
        $attr['slug'] = Str::slug($request->name);
        Products_Stocks::create($attr);
        session()->flash('success', 'The Data Was Added');
        return redirect()->to('/stock');
    }
    public function update(StockRequest $request, Products_Stocks $stock)
    {
        $attr = $request->all();
        $stock->update($attr);
        session()->flash('success', 'The Data Was Updated');
        return redirect()->to('/stock');
    }
}
