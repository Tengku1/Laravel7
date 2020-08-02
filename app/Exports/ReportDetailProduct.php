<?php

namespace App\Exports;

use App\Product;
use App\Products_Stock;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportDetailProduct implements FromCollection, WithHeadings
{
    public function __construct($getCode, $productID)
    {
        $this->code = $getCode[0]->code;
        $this->id = $productID[0]->id;
    }
    public function collection()
    {
        return Products_Stock::join("branch", "products_stock.branch_code", "branch.code")
            ->join("products", "products_stock.product_id", "products.id")
            ->select(
                "products.name",
                "branch.name as BranchName",
                "qty",
                DB::raw("format(buy_price,2)"),
                DB::raw("month(products_stock.created_at)"),
                DB::raw("year(products_stock.created_at)"),
            )->where("branch.code", "=", $this->code)->where("products.id", "=", $this->id)->get();
    }
    public function headings(): array
    {
        return [
            "Product Name",
            "Branch Name",
            "Quantity",
            "Buy Price",
            "Month",
            "Year",
        ];
    }
}
