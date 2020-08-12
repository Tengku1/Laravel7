<?php

namespace App\Exports;

use App\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportProduct implements FromCollection, WithHeadings
{
    use Exportable;

    public function collection()
    {
        return Product::join("products_stock", "products_stock.product_id", "products.id")->join("branch", "products_stock.branch_code", "branch.code")->join("history_buy", "history_buy.branch_code", "branch.code")->select("products.name", "branch.name as BranchName", DB::raw("sum(qty) as qty"))->groupBy("products.name", "BranchName")->get();
    }
    public function headings(): array
    {
        return [
            "Product Name",
            "Branch Name",
            "Quantity",
        ];
    }
}
