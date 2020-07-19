<?php

namespace App\Exports;

use App\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StocksExport implements FromCollection, WithHeadings
{

    use Exportable;

    protected $code;

    public function __construct($code, $date)
    {
        $this->code = $code;
        $this->date = $date;
    }
    public function collection()
    {
        if ($this->date != null || $this->date != '') {
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
                ->where('products_stock.created_at', 'like', '%' . $this->date . '%')
                ->get();
        } else {
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
