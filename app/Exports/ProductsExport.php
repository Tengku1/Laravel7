<?php

namespace App\Exports;

use App\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{

    use Exportable;

    protected $code;

    public function __construct($code)
    {
        $this->code = $code;
    }
    public function collection()
    {
        return Product::leftJoin("products_stock", "products.id", "=", "products_stock.product_id")
            ->leftJoin("branch", "products_stock.branch_code", "=", "branch.code")
            ->select(
                "products.name as Name",
                "branch.name",
                'products_stock.qty',
                'products.sell_price',
                "products_stock.buy_price",
                DB::raw(
                    "month(products_stock.created_at)",
                ),
                DB::raw(
                    "year(products_stock.created_at)",
                )
            )
            ->where('branch_code', '=', $this->code)
            ->get();
    }
    public function headings(): array
    {
        return [
            "Product Name",
            "Branch Name",
            "Quantity",
            "Sell Price",
            "Buy Price",
            'Month',
            'Year'
        ];
    }
}

// namespace App\Exports;

// use App\Products_Stock;
// use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;

// class ProductsExport implements FromView
// {

//     protected $code;

//     public function __construct($code)
//     {
//         $this->code = $code;
//     }

//     public function view(): View
//     {
//         return view('Admin.Stock.table', [
//             'stocks' => Products_Stock::where('branch_code', '=', $this->code)->get()
//         ]);
//     }
// }
